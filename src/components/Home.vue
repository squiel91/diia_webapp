<template>
	<v-app>
		<v-navigation-drawer
      		fixed
      		v-model="drawer"
      		right
      		app
			clipped
			style="z-index: 200;"
    	>
			<filters class="ma-3" @changeFilter="changeFilter($event)" 
			:nodes="dataGraph? dataGraph.nodes : []"
			:courses="dataGraph? dataGraph.courses : []"
			></filters>
		</v-navigation-drawer>
		<v-toolbar color="primary" dark fixed app clipped-right>
			<img class="small-logo" src="img/white.png">
			<v-spacer></v-spacer>
			<mainheader></mainheader>
			</v-toolbar>
		<v-content>
      		<v-container fluid fill-height>
				<v-fab-transition>
				  <v-btn
					dark
					color="primary"
					:key="activeFab"
					class="mb-5 ml-3"
					fab
					fixed
					bottom
					left
					@click.stop="drawer = !drawer">
        			<v-icon>{{ activeFab }}</v-icon>
					<!-- <v-icon>close</v-icon> -->
					</v-btn>
				</v-fab-transition>
				<nodedetailed :info="clicked" :index="dataGraphIndex"></nodedetailed>
				<graph
				:dataGraph="dataGraph"
				:dataGraphIndex="dataGraphIndex"
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
		props: {
			source: String
		},
		computed: {
			activeFab () {
				return !this.drawer? 'mode_edit' : 'close'
			}
		},
		created() {
			if (!this.$store.state.email) {
	            this.$router.push('/')
			}
			this.fetchData()
		},
		methods: {
			changeFilter(evt) {
				this.filterConditions = evt
			},
			fetchData() {
				// this.$http.get(`http://179.27.71.27/grafoDocente/${this.$store.getters.docente}`, this.$store.getters.sign)
				this.$http.get(`api/interactions.php`, this.$store.getters.sign)
				.then(data => {
					this.dataGraph = {}
					this.dataGraph.courses = data.body.cursos
					this.dataGraph.nodes = data.body.nodos
					this.dataGraph.interactions = data.body.interacciones
					for (var curso of this.dataGraph.courses) {
						this.dataGraphIndex.courses[curso.id_curso] = curso
					}
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
		data() {
			return {
				clicked: undefined,
				drawer: false,
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

<style>
	img.small-logo {
		width: 100pt;
	}

	button.wide {
		width: 100%;
	}
</style>