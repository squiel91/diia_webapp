<template>
	<div class="graph-container">
		<div style="width:100%; text-align: center; color: gray" v-if="!dataGraph" class="loading">
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
						this.$emit('loadingLess')
						this.rendered = true
					} else {
						if (this.rendered && this.graph) {
							this.apply_filters()
							this.graph.refresh()
							this.apply_visualization()
							this.$emit('loadingLess')
							if (this.rearange) {
								this.rearanged = true
								var self = this
								setTimeout(function() {
									self.startGravity()
								}, 0)
							} else {
								if (this.rearanged) {
									this.stopGravity()
								}
							}
						}
					}
				},
				deep: true
			}
		},
		methods: {
			individualSelection(value) {
				let entity = value.entity
				let showNodes = value.nodes
				this.nodeNumbers[entity] = Object.values(showNodes).filter(a=>a.selected).length
				let self = this
				this.filters.undo(`mask${entity}`)
					.nodesBy(function(n) {
						let node = self.dataGraphIndex.nodes[n.id]
						if (node.tipo != entity) return true
						return showNodes[node.id].selected
				}, `mask${entity}`)
				.apply()
				this.$emit('nodeNumbers', this.nodeNumbers)
			},
			reset() {
				if (this.graph) {
					if (this.graph.graph) this.graph.graph.clear()
					this.graph.refresh()
					this.graph.graph.kill()
				}
				this.rendered = false
				this.nodes = undefined
				this.edges = undefined
				this.filters = undefined
				this.combined_edges = undefined
				this.drag = undefined
				this.graph = undefined
				this.rearanged = false
			},
			scale(min, max, val) {
				let maxSize = 16
				let minSize = 4
				let scaleVal = minSize + [(Math.abs(val)-min)/(max-min)]*(maxSize-minSize)
				return scaleVal
			},
			apply_visualization() {
				if (this.filter) {
					let vizualizationInfo = this.filter.metric
					if (vizualizationInfo && this.lastVisualization != vizualizationInfo.querry) {
						this.$emit('loadingMore')
						this.$http.get(`http://179.27.71.27/${vizualizationInfo.querry}`)
						.then(
							data => {
								let max = Math.abs(parseFloat(data.body.max))
								let min = Math.abs(parseFloat(data.body.min))
								let values = data.body.nodes
								for (var [nodeId, value] of Object.entries(values)) {
									this.graph.graph.nodes(nodeId).size = this.scale(min, max, value)
								}
								this.lastVisualization = vizualizationInfo.querry
								this.$emit('loadingLess')
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

					if (filter.platforms && interaction.plataforma) {
						if (!filter.platforms[interaction.plataforma])
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
								combined_edge.polarity.neutral += 1
							}
							if (interaction.polaridad == 'n') {
								combined_edge.polarity.negative += 1
							}
						}

					}
					this.combined_edges = []
					function qty_color(value) {
							var color_val = (8 - Math.min(value, 8)) * 20 + 50
							return `rgb(${color_val}, ${color_val}, ${color_val})`
					}
					function polarity_color(polarity, value, alternativeFunction) {
							let color_pallet = {
								horror: '#eb5a46',
								bad: '#f2d600',
								good: '#61bd4f',
								amazing: '#1f8f4e' 
							}
							var polarity_saldo = polarity.positive - polarity.negative
							if (polarity_saldo >= 2) return color_pallet.amazing
							if (polarity_saldo >= 1) return color_pallet.good
							if (polarity_saldo >= 0) return alternativeFunction(value)
							if (polarity_saldo >= -1) return color_pallet.bad
							return color_pallet.horror
					}
					for (const combined_edge of Object.values(this.combined_edges_index)) {
						this.combined_edges.push({
							id: combined_edge.id,
							source: combined_edge.source,
							target: combined_edge.target,
							size: combined_edge.value,
							color: filter && filter.polarity? polarity_color(combined_edge.polarity, combined_edge.value, qty_color): qty_color(combined_edge.value)

						})
					}
				}
				this.dataGraphIndex.combined_edges_index = this.combined_edges_index
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
					a: 'img/actividad.png', // actvidad
					m_vid: 'img/vid.png',
					m_img: 'img/img.png',
					m_tex: 'img/tex.png',
					m_gif: 'img/gif.png',
					m_des: 'img/unknown.png',
					m_lik: 'img/recurso.png'
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

				var typePos = {
					'm': { x: 0.7, y: 0.7},
					'e': { x: -0.7, y: -0.7},
					'd': { x: 0, y: 0},
					'a': { x: 0.7, y: -0.7}
				}

				this.nodeNumbers = {
					d: 0,
					e: 0,
					m: 0,
					a: 0
				}
				for (var node of this.nodes) {
					this.nodeNumbers[node.tipo] += 1
					g.nodes.push({
						id: node.id,
						label: node.nombre.length <= 10? node.nombre : node.nombre.substring(0,11) + '...',
						type: 'image',
						url: node.tipo == 'm'? node_images[`${node.tipo}_${node.cont}`] : node_images[node.tipo],
						x: typePos[node.tipo].x + (node.tipo != 'd'? Math.random(): 0),
						y: typePos[node.tipo].y + (node.tipo != 'd'? Math.random(): 0),
						size: this.DEFAULT_SIZE
					})
				}

				this.$emit('nodeNumbers', this.nodeNumbers)

				g.edges = this.combine(this.filter)

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

				this.filters = new sigma.plugins.filter(s)
				this.graph = s

				// this.apply_filters(g.edges)
				// this.apply_visualization()

				var justDragged = false

				var self = this

				s.startForceAtlas2(forceAtlasConf)
					.bind('overNode', function(e) {
							self.$emit('focused', {
								id: e.data.node.id,
								isNode: true
							})
						}
					)
					.bind('clickNode', function(e) {
						if (!justDragged) {
							self.$emit('clicked',  {
								id: e.data.node.id,
								isNode: true
							})
						} else {
							justDragged  = false
						}
					})
					.bind('overEdge', function(e) {
							self.$emit('focused', {
								id: e.data.edge.id,
								isNode: false
							})
						}
					)					
					.bind('clickEdge', function(e) {
						justDragged  = false
						self.$emit('clicked', {
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
							self.$emit('focused', {
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
			},
			apply_filters(combined) {
				var self = this
				this.nodeNumbers = {
					d: 0,
					e: 0,
					m: 0,
					a: 0
				}
				if (this.filter && this.filters) {
					if (this.filter.nodes) {
						this.filters.undo('node')
							.nodesBy(function(n) {
								let tipoNodo = self.dataGraphIndex.nodes[n.id].tipo
								let platform = self.dataGraphIndex.nodes[n.id].plat
								if (platform && !self.filter.platforms[platform]) return false
								let willShow = self.filter.nodes[tipoNodo]
									&& (!self.filter.individualNodes ||
											!self.filter.individualNodes[tipoNodo] || Object.keys(self.filter.individualNodes).length == 0 || 
											self.filter.individualNodes[tipoNodo][n.id].selected)
								if (willShow) self.nodeNumbers[tipoNodo] += 1
								return willShow
						}, 'node')
						.apply()
						this.$emit('nodeNumbers', this.nodeNumbers)
					}

					if (!combined) {
						combined = this.combine(this.filter)
						this.graph.graph.edges().map(a => this.graph.graph.dropEdge(a.id))
						combined.map(a => this.graph.graph.addEdge(a))
					}
				}
			}
		},
		data() {
			return {
				nodeNumbers: undefined,
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