<template>
	<div class="graph-container">
		<div style="width:100%; text-align: center; color: gray" v-if="!dataGraph" class="loading">
			<!-- <v-progress-linear :indeterminate="true" color="primary"></v-progress-linear> -->
			<v-progress-circular class="mb-3" indeterminate :size="50" color="primary"></v-progress-circular>
			<div ref="loadingMessage">Cargando datos de curso</div>
		</div>
		<div v-show="dataGraph" id="graph" ref="graph"></div>
	</div>
</template>

<script>
	export default {
		props: ['dataGraph', 'filter', 'dataGraphIndex', 'rearange'],
		watch: {
			'$props':{
				handler: function () {
					if (!this.rendered && this.dataGraph) {
						this.renderGraph()
						this.rendered = true
					}
					if (this.rendered) {
						console.log(this.filter)

						this.apply_filters()
						this.apply_visualization(this.filter.metric)
						if (this.rearange) {
							this.rearanged = true
							var thisScoped = this
							setTimeout(function() {
								thisScoped.startGravity()
							}, 0)
						} else {
							if (this.rearanged) {
								this.stopGravity()
							}
						}
					}
				},
				deep: true
			}
		},
		methods: {
			scale(min, max, val) {
				let maxSize = 16
				let minSize = 4
				let scaleVal = minSize + [(Math.abs(val)-min)/(max-min)]*(maxSize-minSize)
				console.log(scaleVal)
				return scaleVal
			},
			apply_visualization(vizualizationInfo) {
				if (vizualizationInfo && this.lastVisualization != vizualizationInfo.querry) {
					this.$http.get(`http://179.27.71.27/${vizualizationInfo.querry}`)
					.then(
						data => {
							let max = Math.abs(parseFloat(data.body.valorMaximo))
							let min = Math.abs(parseFloat(data.body.valorMinimo))
							let values = data.body.metricas
							for (var [nodeId, value] of Object.entries(values)) {
								this.graph.graph.nodes(nodeId).size = this.scale(min, max, value)
							}
							this.lastVisualization = vizualizationInfo.querry
							this.graph.refresh()
						}, error => {
							console.log('Error at retrieving metric:')
							console.log(error)
						}	
					)
				} else {
					if (this.lastVisualization) {
					    for (var node of this.graph.graph.nodes()) {
					        node.size = this.DEFAULT_SIZE
					    }
					    this.graph.refresh()
						this.lastVisualization = undefined
					}
				}
			},
			filter_interaction(interaction, filter) {
				if (filter) {
					if (filter.startDate)
						if (interaction.fecha && new Date(interaction.fecha) < new Date(filter.startDate))
							return false
					if (filter.endDate)
						if (interaction.fecha && new Date(interaction.fecha) > new Date(filter.endDate))
							return false
					
					// filter by interaction terminal nodes
					if (filter.interactions) {
						var originType = this.dataGraphIndex.nodes[interaction.origen].tipo
						var targetType = this.dataGraphIndex.nodes[interaction.destino].tipo
						var currentInteraction = `${originType}${targetType}`
						var reversedCurrentInteraction = `${targetType}${originType}`
						if (!(filter.interactions[currentInteraction] || filter.interactions[reversedCurrentInteraction]))
							return false
					}

					// filter by interaction type
					if (filter.interactionTypes) {
						if (!filter.interactionTypes[interaction.tipo])
							return false
					}
				}
				return true
			},
			startGravity() {
				var forceAtlasConf = {
					slowDown: 100000, 
					barnesHutTheta: 1, 
					linLogMode: false, 
					gravity: 10, 
					worker: true, 
					strongGravityMode: true, 
					edgeWeightInfluence: 1, 
					startingIterations: 0
				}

				this.graph.startForceAtlas2(forceAtlasConf)
			},
			stopGravity() {
				this.graph.killForceAtlas2()
			},
			combine(filter) {
				var t0 = performance.now();

				if (this.edges) {
					this.combined_edges_index = {}
					for (var interaction of this.edges) {
						if (interaction.origen && interaction.destino && this.filter_interaction(interaction, filter)) {
							var id_comb
							if (interaction.origen > interaction.destino) {
								id_comb = `${interaction.origen}_${interaction.destino}`
							} else {
								id_comb = `${interaction.destino}_${interaction.origen}`
							}
							if (!(id_comb in this.combined_edges_index)) {
								this.combined_edges_index[id_comb] = {
									id: id_comb,
									source: interaction.origen > interaction.origen? interaction.origen: interaction.destino,
									target: interaction.origen > interaction.origen? interaction.destino: interaction.origen,
									value: 0,
									interactions: [],
									polarity: {
										positive: 0,
										negative: 0,
										neutral: 0
									}
								}
							}

							var combined_edge = this.combined_edges_index[id_comb]
							combined_edge.value += 1
							combined_edge.interactions.push(interaction)
							if (interaction.polaridad == 'p') {
								combined_edge.polarity.positive += 1
							}
							if (interaction.polaridad == 'e') {
								combined_edge.polarity.negative += 1
							}
							if (interaction.polaridad == 'n') {
								combined_edge.polarity.neutral += 1
							}
						}

					}
					this.combined_edges = []
					function polarity_color(polarity) {

						var total_polarity = polarity.positive + polarity.negative + polarity.neutral
						var polarity_positive = polarity.positive / total_polarity
						var polarity_negative = polarity.negative / total_polarity

						var polarity_val = 0 - polarity_negative + polarity_positive
						if (total_polarity == 0)
 							return `rgb(150, 150, 150)`
						else
 							return `rgb(${polarity_negative * 255}, ${polarity_positive * 255}, 0)`
					}
					function qty_color(value) {
							var color_val = (8 - Math.min(value, 8)) * 20 + 50
							return `rgb(${color_val}, ${color_val}, ${color_val})`
					}
					for (const combined_edge of Object.values(this.combined_edges_index)) {
						this.combined_edges.push({
							id: combined_edge.id,
							source: combined_edge.source,
							target: combined_edge.target,
							size: combined_edge.value,
							color: filter && filter.polarity? polarity_color(combined_edge.polarity): qty_color(combined_edge.value)

						})
					}
				}
				this.dataGraphIndex.combined_edges_index = this.combined_edges_index
				var t1 = performance.now();
				console.log(`Combine END: ${t1 - t0} milliseconds.`)
				return this.combined_edges
			},
			renderGraph() {
				// this.$refs.loadingMessage.innerText = 'Dibujando el grafo del curso'
				this.nodes = this.dataGraph.nodes
				this.edges = this.dataGraph.interactions
				var g = {
				      nodes: [],
				      edges: []
					}

				var node_images = {
					d: 'img/docente.png', // docente
					e: 'img/estudiante.png', //estudiante
					m: 'img/recurso.svg', // recurso
					a: 'img/actividad.png' // actvidad
				}

				sigma.utils.pkg('sigma.canvas.nodes');
				sigma.canvas.nodes.image = (function() {
					var _cache = {},
							_loading = {},
							_callbacks = {};

					var renderer = function(node, context, settings) {
						var args = arguments,
								prefix = settings('prefix') || '',
								size = node[prefix + 'size'],
								color = node.color || settings('defaultNodeColor'),
								url = node.url;

						if (_cache[url]) {
							context.save();

							context.drawImage(
								_cache[url],
								node[prefix + 'x'] - size,
								node[prefix + 'y'] - size,
								2 * size,
								2 * size
							);

						} else {
							sigma.canvas.nodes.image.cache(url);
							sigma.canvas.nodes.def.apply(
								sigma.canvas.nodes,
								args
							);
						}
					};

					renderer.cache = function(url, callback) {
						if (callback)
							_callbacks[url] = callback;

						if (_loading[url])
							return;

						var img = new Image();

						img.onload = function() {
							_loading[url] = false;
							_cache[url] = img;

							if (_callbacks[url]) {
								_callbacks[url].call(this, img);
								delete _callbacks[url];
							}
						};

						_loading[url] = true;
						img.src = url;
					};
					return renderer;
				})();

				for (var node of this.nodes) {
					g.nodes.push({
						id: node.id,
						label: node.nombre.length <= 10? node.nombre : node.nombre.substring(0,11) + '...',
						type: 'image',
						url: node_images[node.tipo],
						x: Math.random(),
						y: Math.random(),
						size: this.DEFAULT_SIZE
					})
				}

				g.edges = this.combine()

				var forceAtlasConf = {
					slowDown: 1000000000, 
					barnesHutTheta: 1, 
					linLogMode: false, 
					gravity: 20, 
					worker: true, 
					strongGravityMode: true, 
					edgeWeightInfluence: 1, 
					startingIterations: 0
				}

				// Instantiate sigma:
				var s = new sigma({
					renderer: {
						container: this.$refs.graph,
						type: 'canvas'
					},
					graph: g,
					settings: {
						doubleClickEnabled: false,
						minEdgeSize: 1,
						maxEdgeSize: 8,
						minNodeSize: 4,
						maxNodeSize: 16,
						enableEdgeHovering: true,
						edgeHoverColor: '#8e44ad',
						defaultEdgeHoverColor: '#8e44ad',
						edgeHoverSizeRatio: 1,
						edgeHoverExtremities: true
					}
				})

				this.graph = s

				var justDragged = false

				var vueInstance = this
				s.startForceAtlas2(forceAtlasConf)
					.bind('overNode', function(e) {
							vueInstance.$emit('focused', {
								id: e.data.node.id,
								isNode: true
							})
						}
					)
					.bind('clickNode', function(e) {
						if (!justDragged) {
							vueInstance.$emit('clicked',  {
								id: e.data.node.id,
								isNode: true
							})
						} else {
							justDragged  = false
						}
					})
					.bind('overEdge', function(e) {
							vueInstance.$emit('focused', {
								id: e.data.edge.id,
								isNode: false
							})
						}
					)					
					.bind('clickEdge', function(e) {
						justDragged  = false
						vueInstance.$emit('clicked', {
							id: e.data.edge.id,
							isNode: false
						})
					})

				this.drag = sigma.plugins.dragNodes(s, s.renderers[0])



				this.drag.bind('startdrag', function(event) {
					s.killForceAtlas2()
				})

				this.drag.bind('drop', function(event) {
					justDragged = true
					// s.startForceAtlas2(forceAtlasConf)
				})

				s.startForceAtlas2(forceAtlasConf)
					.bind('overNode', function(e) {
							vueInstance.$emit('focused', {
								id: e.data.node.id,
								isNode: true
							})
						}
					)
				var iterations = 5
				var slowDown = 10000
				var refreshIntervalId = setInterval(() => {
					if(iterations == 0) {
						s.killForceAtlas2();
						clearInterval(refreshIntervalId);
					} else {
						slowDown *= 5
						s.configForceAtlas2({slowDown: slowDown});
					}
					iterations -= 1
				}, 1000)
				this.filters = new sigma.plugins.filter(s)
			},
			apply_filters() {
				var thisVue = this
				if (this.filter) {
					if (this.filter.nodes) {
						this.filters.undo('node')
							.nodesBy(function(n) {
								let tipoNodo = thisVue.dataGraphIndex.nodes[n.id].tipo
								return thisVue.filter.nodes && thisVue.filter.nodes[tipoNodo] 
									&& (Object.keys(thisVue.filter.individualNodes).length == 0 || thisVue.filter.individualNodes[tipoNodo][n.id].selected)
						}, 'node')
						.apply()
					}

					this.graph.graph.edges().map(a => this.graph.graph.dropEdge(a.id))
					this.combine(this.filter).map(a => this.graph.graph.addEdge(a))

					this.graph.refresh()
				}
			}
		},
		data() {
			return {
				rendered: false,
				nodes: undefined,
				edges: undefined,
				combined_edges: undefined,
				drag: undefined,
				filters: undefined,
				graph: undefined,
				rearanged: false,

				DEFAULT_SIZE: 6,
				lastVisualization: undefined
			}
		}
	}
</script>

<style>
	#graph {
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      position: absolute;
    }

    .graph-container {
    	width: 100%;
    }

    .loading {
    	margin: 0pt auto;
    }
</style>