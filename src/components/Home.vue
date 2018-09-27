<template>
	<v-app>
		<v-navigation-drawer
			:width="374"
      		fixed
      		v-model="drawer"
      		right
      		clipped
      		app
			:mobile-break-point="500"
			style="z-index: 200; width: 390px; overflow: hidden;"
			class="pa-0"
    	>
    		<div class="filtersDrawer" @click.stop="toggleFilterDrawer()"><div class="textFilter">{{drawer? '&gt;':'&lt;'}}<br><br>F<br>I<br>L<br>T<br>R<br>O<br>S<br><br>{{drawer? '&gt;':'&lt;'}}</div></div>
			<filters 
			 style="padding-left: 14px" 
			 class="ml-3 mt-0 mr-0"
			 ref="filter" 
			 :course="course" 
			 :index="dataGraphIndex"
			 @individualSelection="individualSelection($event)"
			 @filter="changeFilter($event)" 
			></filters>
		</v-navigation-drawer>
		<v-navigation-drawer
			:width="424"
      		fixed
      		v-model="analisisDrawer"
      		clipped
      		left
      		app
			:mobile-break-point="500"
			style="z-index: 200; width: 440px; overflow-y: hidden;"
			class="pa-0"
			:class="{closed: !analisisDrawer}"
    	>
    		<div :class="{open: analisisDrawer}" class="analisisDrawer" @click.stop="toggleAnalisisDrawer()"><div class="textFilter">{{!analisisDrawer? '&gt;':'&lt;'}}<br><br>E<br>S<br>T<br>A<br>D<br>Í<br>S<br>T<br>I<br>C<br>A<br>S<br><br>{{!analisisDrawer? '&gt;':'&lt;'}}</div></div>
			<analisis 
			 ref="analisis"
			 @clicked="clicked = $event"
			 :filter="filterConditions"
			 :nodeNumbers="nodeNumbers"
			 :course="course"
			/>
		</v-navigation-drawer>
		<v-toolbar color="primary" dark fixed app clipped-right>
			<mainheader :courses="courses? courses : []" @change="course = $event"></mainheader>
		</v-toolbar>
		<v-content>
      		<v-container fluid fill-height>
				<v-btn
				:key="activeFab"
				class="mb-5 ml-4"
				fab
				fixed
				bottom
				left
				@click.stop="rearange = !rearange">
    			<v-icon>{{ rearangeFab }}</v-icon>
				</v-btn>
				<nodedetailed ref="nodedetailed" :info="clicked" :course="course" :index="dataGraphIndex"></nodedetailed>
				<graph
				ref="graph"
				:dataGraph="dataGraph"
				:dataGraphIndex="dataGraphIndex"
				:rearange="rearange"
				:filter="filterConditions"
				@nodeNumbers="nodeNumbers=$event"
				@focused="focused = $event"
				@clicked="clicked = $event"
				@loadingLess="loadingLess()"
				@loadingMore="loadingMore()"
				></graph>
				<detailed class="ml-4" :focused="focused"
				:dataGraphIndex="dataGraphIndex"></detailed>
      		</v-container>
   		</v-content>
	</v-app>
</template>

<script>
	import TopHeader from './TopHeader.vue'
	import Graph from './Graph.vue'
	import Filters from './Filters.vue'
	import Details from './Details.vue'
	import NodeDetails from './NodeDetails.vue'
	import Analisis from './Analisis.vue'

	export default {
		computed: {
			activeFab () {
				return !this.drawer? 'mode_edit' : 'close'
			},
			rearangeFab () {
				return !this.rearange? 'cached' : 'close'
			}
		},
		created() {
			if (!this.$store.state.email) {
	            this.$router.push('/')
			}
		},
		methods: {
			toggleAnalisisDrawer() {
				 this.$refs.analisis.adjust_height()
				 this.analisisDrawer = !this.analisisDrawer
			},
			toggleFilterDrawer() {
				this.$refs.filter.adjust_height()
				this.drawer = !this.drawer
				this.rearange = false
			},
			individualSelection(event) {
				this.$refs.graph.individualSelection(event)
			},
			loadingLess() {
				this.$refs.filter.loading(-1)
			},
			loadingMore() {
				this.$refs.filter.loading(1)
			},
			changeFilter(evt) {
				this.filterConditions = evt
			},
			fetchData() {
				this.loadingMore()
				this.$http.get(`http://179.27.71.27/consulta/docente/${this.$store.getters.docente}/${this.fetchedCourse}`) // , this.$store.getters.sign) 
				.then(data => {
					this.dataGraph = {}
					this.dataGraph.nodes = data.body.nodos
					this.dataGraph.interactions = data.body.interacciones
					this.dataGraphIndex.nodes = {} 
					this.dataGraphIndex.edges = {} 
					for (var node of this.dataGraph.nodes) {
						this.dataGraphIndex.nodes[node.id] = node
					}
					for (var edge of this.dataGraph.interactions) {
						this.dataGraphIndex.edges[edge.id] = edge
					}
					this.loadingLess()
				}, error => {
					console.log(error)
				})
			}
		},
		components: {
			mainheader: TopHeader,
			graph: Graph,
			filters: Filters,
			detailed: Details,
			nodedetailed: NodeDetails,
			analisis: Analisis
		},
		watch: {
			course() {
				if (this.course != this.fetchedCourse) {
					this.fetchedCourse = this.course
					this.focused = undefined 
					this.dataGraph = undefined
					this.$refs.nodedetailed.reset()
					this.$refs.graph.reset()
					this.$refs.filter.softReset()
					this.filterConditions = {}
					this.fetchData()
				}
			}
		},
		data() {
			return {
				nodeNumbers: undefined,
				courses: this.$store.state.courses,
				course: undefined,
				fetchedCourse: undefined,
				clicked: undefined,
				drawer: true,
				rearange: false,
				dataGraph: undefined,
				dataGraphIndex: { 
						courses: {},
						nodes: {},
						edges: {}
				},
				filterConditions: {},
				focused: undefined,
				analisisDrawer: false
			}
		}
	}
</script>

<style scoped>
	button.wide {
		width: 100%;
	}

	.filtersDrawer, .analisisDrawer {
		overflow-x: hidden;
		overflow-y: hidden;
		position: absolute;
		top: 0pt;
		width: 12pt;
		height: 940pt;
		background-color: #8e44ad;
		color: white;
		font-weight: bold;
  		padding-top: 150pt;
  		cursor: pointer;
  		z-index: 250;
	}

	.analisisDrawer {
		height: 100%;
		padding-top: 100pt;
		left: 424px;
	}

	.filtersDrawer:hover, .analisisDrawer:hover {
		background-color: #81379f;
	}

	.textFilter {
		text-align: center
	}
</style>