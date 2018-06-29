<template>
	<div class="graph-container">
		<div v-if="!dataGraph" class="loading">
			<v-progress-circular indeterminate :size="50" color="primary"></v-progress-circular>
		</div>
		<div v-show="dataGraph" id="graph" ref="graph"></div>
	</div>
</template>

<script>
	export default {
		props: ['dataGraph', 'filter', 'dataGraphIndex'],
		watch: {
			'$props':{
				handler: function (newVal, oldVal) { 
					if (!this.rendered && this.dataGraph) {
						this.renderGraph()
						this.rendered = true
					}
					this.apply_filters(newVal, oldVal)
				},
				deep: true
			}
		},
		methods: {
			filter_interaction(interaction, filter) {
				if (filter) {
					if (filter.startDate)
						if (interaction.fecha && new Date(interaction.fecha) < new Date(filter.startDate))
							return false
					if (filter.startDate)
						if (interaction.fecha && new Date(interaction.fecha) < new Date(filter.startDate))
							return false
					if (filter.endDate)
						if (interaction.fecha && new Date(interaction.fecha) > new Date(filter.endDate))
							return false
					if (filter.courses)
						if (interaction.curso && !filter.courses.includes(interaction.curso))
							return false
				}
				return true
			},
			combine(filter) {
				console.log('Combine START...')
				var t0 = performance.now();

				if (this.edges) {
					this.combined_edges_index = {}
					for (var interaction of this.edges) {
						if (interaction.origen && interaction.destino && this.filter_interaction(interaction, filter)) { // discard the ones that have null
							// {
							//     "id_origen": "2",
							//     "fecha": 1273959928359,
							//     "origen": 57,
							//     "destino": 34,
							//     "curso": 1,
							//     "tipo": "com",
							//     "polaridad": "n"
							// }
							var id_comb = `${interaction.origen}_${interaction.destino}`
							if (!(id_comb in this.combined_edges_index)) {
								this.combined_edges_index[id_comb] = {
									id: id_comb,
									source: interaction.origen,
									target: interaction.destino,
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
 							return `rgb(255, 255, 0)`
						else
 							return `rgb(${polarity_negative * 255}, ${polarity_positive * 255}, 0)`
							


					}
					for (const combined_edge of Object.values(this.combined_edges_index)) {
						this.combined_edges.push({
							id: combined_edge.id,
							source: combined_edge.source,
							target: combined_edge.target,
							size: combined_edge.value,
							color: polarity_color(combined_edge.polarity)

						})
					}
				}
				this.dataGraphIndex.combined_edges_index = this.combined_edges_index
				var t1 = performance.now();
				console.log(`Combine END: ${t1 - t0} milliseconds.`)
				return this.combined_edges
			},
			renderGraph() {
				this.nodes = this.dataGraph.nodes
				this.edges = this.dataGraph.interactions
				var g = {
				      nodes: [],
				      edges: []
					}

				var node_images = {
					d: 'img/img0.png', // docente
					e: 'img/img1.png', //alumno
					m: 'img/img2.png', // material
					a: 'img/img2.png' // actvidad
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
						label: node.nombre.length <= 10? node.nombre : node.nombre.substring(0,9) + '...',
						type: 'image',
						url: node_images[node.tipo],
						x: Math.random(),
						y: Math.random(),

						size: 1
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
						minEdgeSize: 0.5,
						maxEdgeSize: 4,
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
			apply_filters(newVal, oldVal) {
				console.log('Apply_filters START...')
				var t0 = performance.now();
				var thisVue = this
				if (this.filter) {
					debugger
					if (this.filter.students) {
						this.filters.undo('students')
							.nodesBy(function(n) {
								return thisVue.dataGraphIndex.nodes[n.id].tipo != 'e' || thisVue.filter.students.includes(n.id)
						}, 'students')
						.apply()
					}

					if (this.filter.courses) {
						this.filters.undo('node_by_course')
							.nodesBy(function(n) {
								return !thisVue.dataGraphIndex.nodes[n.id].cursos || thisVue.filter.courses.filter((node) => thisVue.dataGraphIndex.nodes[n.id].cursos.includes(node)).length > 0
						}, 'node_by_course')
						.apply()
					}

					this.graph.graph.edges().map(a => this.graph.graph.dropEdge(a.id))
					this.combine(this.filter).map(a => this.graph.graph.addEdge(a))

					if (this.filter.interactionsType) {
						if (this.filter.interactionsType == 'todas') {
							this.filters.undo('interactions_by_type').apply()
						} else {
							var short = {
								'alumno': {e: true},
								'docente': {d: true},
								'material/actividad': {m: true, a: true}
							}
							var interactions = this.filter.interactionsType.split('-')
							var originType = short[interactions[0]]
							var targetType = short[interactions[1]]
							this.filters.undo('interactions_by_type')
								.edgesBy(function(e) {
									return originType[thisVue.dataGraphIndex.nodes[e.source].tipo] &&
										targetType[thisVue.dataGraphIndex.nodes[e.target].tipo]
							}, 'interactions_by_type')
							.apply()
						}	
					}
					this.graph.refresh()
				}
				var t1 = performance.now();
				console.log(`Apply_filters END: ${t1 - t0} milliseconds.`)
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
				graph: undefined
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