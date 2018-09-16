<template>
	<div class="analisis">
		<v-tabs left
			style="width: 410px"
	      v-model="active"
	      slider-color="primary"
	    >
	      <v-tab ripple>Curso</v-tab>
	      <v-tab ripple><v-badge><span slot="badge">{{(nodeNumbers && nodeNumbers.e) || '-'}}</span><span>Estudiantes</span></v-badge></v-tab>
	      <v-tab ripple><v-badge><span slot="badge">{{(nodeNumbers && nodeNumbers.m) || '-'}}</span><span>Recursos</span></v-badge></v-tab>
	      <v-tab ripple><v-badge><span slot="badge">{{(nodeNumbers && nodeNumbers.a) || '-'}}</span><span>Actividades</span></v-badge></v-tab>
	      <v-tab-item>
	        <v-card flat>
	          <v-card-text class="pr-0 pt-0" :class="{loading: !data.c}" ref="load_c">
	          	<v-progress-circular class="topPadding" v-if="!data.c" indeterminate :size="50" color="primary"/>
	          	<div else class="graphs" ref="c">
	          	</div>
	          </v-card-text>
	        </v-card>
	      </v-tab-item>
	      <v-tab-item>
	        <v-card flat>
	          <v-card-text class="pr-0 pt-0" :class="{loading: !data.e}">
	          	<v-progress-circular class="topPadding" v-if="!data.e" indeterminate :size="50" color="primary"/>
	          	<div else class="graphs" ref="e">
	          	</div>
	          </v-card-text>
	        </v-card>
	      </v-tab-item>
	      <v-tab-item>
	        <v-card flat>
	          <v-card-text class="pr-0 pt-0" :class="{loading: !data.m}">
	          	<v-progress-circular class="topPadding" v-if="!data.m" indeterminate :size="50" color="primary"/>
	          	<div else class="graphs" ref="m">
	          	</div>
	          </v-card-text>
	        </v-card>
	      </v-tab-item>
	      <v-tab-item>
	        <v-card flat>
	          <v-card-text class="pr-0 pt-0" :class="{loading: !data.a}">
	            <v-progress-circular class="topPadding" v-if="!data.a" indeterminate :size="50" color="primary"/>
	          	<div else class="graphs" ref="a">
	          	</div>
	          </v-card-text>
	        </v-card>
	      </v-tab-item>
	    </v-tabs>
	</div>
</template>

<script>
	function filterDict(dictionary) {
		if (!dictionary) return undefined
		return Object.entries(dictionary).filter(e => e[1]).map(e => e[0])
	}

	import Vue from 'vue'
	import NumberGraph from './graphs/Number.vue'
	import NodeGraph from './graphs/Node.vue'
	import PieGraph from './graphs/Pie.vue'
	import BarGraph from './graphs/Bar.vue'

	let NumberGraphClass = Vue.extend(NumberGraph)
	let NodeGraphClass = Vue.extend(NodeGraph)
	let PieGraphClass = Vue.extend(PieGraph)
	let BarGraphClass = Vue.extend(BarGraph)

	let graficCodes = {
		numero: NumberGraphClass,
		nodo: NodeGraphClass,
		torta: PieGraphClass,
		barra: BarGraphClass
	}

	export default {
		props: ['nodeNumbers', 'filter', 'course'],
		data () {
      		return {
      			heightSeted: false,
		        active: 0,
		        data: {
		        	c: undefined,
		        	e: undefined,
		        	m: undefined,
		        	a: undefined
		        }
      		}
    	},
    	watch: {
    		course() {
    			this.clear()
    		},
    		filter() {
    			this.clear()
    		},
    		active() {
    			this.update()
    			// this.adjust_height()
    		}
    	},
    	computed: {
    		entity() {
    			return 'cema'[this.active]
    		}
    	},
    	methods: {
			adjust_height() { 
				if (!this.heightSeted && this.entity) {
					// this.heightSeted = true
					let newHeight = `${window.innerHeight - this.$refs.load_c.getBoundingClientRect().y }px`
					this.$refs.c.style.height = newHeight
					this.$refs.e.style.height = newHeight
					this.$refs.m.style.height = newHeight
					this.$refs.a.style.height = newHeight
				}
			},
    		clear() {
    			for (var entity of 'cema') {
    				this.$refs[entity].innerHTML = ''
    				this.data[entity] = undefined
    			}
    			this.update()
    		},
    		initilize() {
    			this.update()
    		},
	    	update() {
	    		if (!this.entity || this.data[this.entity]) return
				this.data[this.entity] = undefined
				this.$http.get(`http://179.27.71.27/consulta/analisis/${this.entity}`, {
					params: {
						id_docente: this.$store.getters.docente,
						id_curso: this.course,
						fecha_inicio: this.filter.startDate,
						fecha_fin: this.filter.endDate,
						nodos: filterDict(this.filter.nodes),
						interacciones: filterDict(this.filter.interactions),
						tipo_ineracciones: filterDict(this.filter.interactionTypes),
						plataformas: filterDict(this.filter.platforms),
						// seleccion_estudiantes: this.filter,
						// seleccion_recursos: this.filter,
						// seleccion_actividades: this.filter
					}
				})
				.then(dataIn => {
					let data = dataIn.body
					this.data[this.entity] = data
					for (var graphData of this.data[this.entity]) {
						let newGraph = new graficCodes[graphData.tipo]({
					    	propsData: { data: graphData }
						})
						newGraph.$mount()
						if (graphData.tipo == 'nodo') {
							newGraph.$on('clicked', event => {
								this.$emit('clicked', event)
							})
						}
						this.$refs[this.entity].appendChild(newGraph.$el) 
					}
				}, error => {
					console.log(error)
				})
			}
    	}
	}
</script>

<style>
	.graphs {
	    overflow-y: scroll;
	}

	.topPadding {
		padding-top: 300px;
	}

	.analisis {
		margin-top: 7pt;
		z-index: 100;
	}

	.loading {
		text-align: center;
		font-style: italic;
		margin-top: 170pt;
	}
</style>
