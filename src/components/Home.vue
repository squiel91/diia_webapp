<template>
	<v-app>
		<v-navigation-drawer
			:width="360"
      		fixed
      		v-model="drawer"
      		right
      		app
			clipped
			style="z-index: 200;"
    	>
			<filters class="ma-3" @filter="changeFilter($event)" 
			:courses="courses? courses : []" :index="dataGraphIndex"
			></filters>
		</v-navigation-drawer>
		<v-toolbar color="primary" dark fixed app clipped-right>
			<mainheader :courses="dataGraph? dataGraph.courses : []"></mainheader>
		</v-toolbar>
		<v-content>
      		<v-container fluid fill-height>
				<v-btn
				:key="activeFab"
				class="mb-5 ml-5"
				fab
				fixed
				bottom
				left
				@click.stop="rearange = !rearange">
    			<v-icon>{{ rearangeFab }}</v-icon>
				</v-btn>
				<v-fab-transition>
				  <v-btn
					dark
					color="primary"
					:key="activeFab"
					class="mb-5 ml-1"
					fab
					fixed
					bottom
					left
					@click.stop="(drawer = !drawer) && (rearange = false)">
        			<v-icon>{{ activeFab }}</v-icon>
					</v-btn>
				</v-fab-transition>
				<nodedetailed ref="nodedetailed" :info="clicked" :curso="filterConditions && filterConditions.course" :index="dataGraphIndex"></nodedetailed>
				<graph
				ref="graph"
				:dataGraph="dataGraph"
				:dataGraphIndex="dataGraphIndex"
				:rearange="rearange"
				:filter="filterConditions"
				@focused="focused = $event"
				@clicked="clicked = $event"
				></graph>
				<detailed :focused="focused"
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
			changeFilter(evt) {
				this.filterConditions = evt
			},
			fetchData() {
				this.$http.get(`http://179.27.71.27/consulta/docente/${this.$store.getters.docente}/${this.fetchedCourse}`) // , this.$store.getters.sign) 
				.then(data => {
					this.dataGraph = {}
					this.dataGraph.nodes = data.body.nodos
					this.dataGraph.interactions = data.body.interacciones
					for (var node of this.dataGraph.nodes) {
						this.dataGraphIndex.nodes[node.id] = node
					}
					for (var edge of this.dataGraph.interactions) {
						this.dataGraphIndex.edges[edge.id] = edge
					}
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
			nodedetailed: NodeDetails
		},
		watch: {
			filterConditions: {
				handler() {
					if (this.filterConditions.course != this.fetchedCourse) {
						this.fetchedCourse = this.filterConditions.course
						this.dataGraph = undefined
						this.$refs.nodedetailed.reset()
						this.$refs.graph.reset()
						this.fetchData()
					}
				},
				deep: true
			}
		},
		data() {
			return {
				courses: this.$store.state.courses,
				fetchedCourse: undefined,
				clicked: undefined,
				drawer: false,
				rearange: false,
				dataGraph: undefined,
				dataGraphIndex: { 
						courses: {},
						nodes: {},
						edges: {}
				},
				filterConditions: {},
				focused: undefined
			}
		}
	}
</script>

<style scoped>
	button.wide {
		width: 100%;
	}
</style>