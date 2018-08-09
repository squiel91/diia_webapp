<template>
	<div class="filters">
		<h2><v-icon style="color: black !important;">local_library</v-icon>Cursos</h2>
		<v-select
        :items="courses"
        item-text="titulo"
        item-value="id_curso"
        v-model="course"
        solo
        >
        </v-select>
		<h2><v-icon style="color: black !important;">timeline</v-icon>Nodos e interacciones</h2>
		<touchFilter ref="touchFilter" :nodes="index.nodes" @change="assignTouchFilter($event)"></touchFilter>
		<h2><v-icon style="color: black !important;">filter_list</v-icon>Tipos de interacciones</h2>
		<interactionFilter @change="interactionTypes=$event"></interactionFilter>
		<h2><v-icon style="color: black !important;">event</v-icon>Periodo</h2>
		<v-menu
			lazy
			v-model="startDateField"
			transition="scale-transition"
			offset-y
			:close-on-content-click="false"
			style="width: 110pt"
		>
			<v-text-field
			slot="activator"
			label="Inicio"
			v-model="startDateFormatted"
			solo
			clearable
			readonly
			>
			</v-text-field>
			<v-date-picker v-model="startDate" 
			:max="new Date().toISOString().substr(0, 10)"
			@input="startDateField = false"
			locale="es-419"
			no-title
			></v-date-picker>
		</v-menu>
		<span style="position: relative; bottom: 8pt; font-size: x-large;">-</span>
		<v-menu
			lazy
			v-model="endDateField"
			prepend-icon="event"
			offset-y
			transition="scale-transition"
			:close-on-content-click="false"
			style="width: 110pt"
		>
			<v-text-field
			slot="activator"
			label="Fin"
			v-model="endDateFormatted"
			clearable
			readonly
			solo
			>
			</v-text-field>
			<v-date-picker v-model="endDate" 
			:min="startDate"
			@input="endDateField = false"
			locale="es-419"
			no-title
			></v-date-picker>
		</v-menu>
		<h2><v-icon style="color: black !important;">data_usage</v-icon>Medida</h2>
		<div class="course">
			<v-select
			solo
			:items="metrics"
			item-text="name"
			v-model="metric"
			label="Ninguna metrica seleccionada"
			:hint="metric? metric.explanation : ''"
	        persistent-hint
	        return-object
	        single-line
	        clearable
			></v-select>
		</div>
		<v-tooltip top>
			<v-switch slot="activator" class="ma-0" color="primary" label="Polaridad de sentimiento" v-model="polarity"></v-switch>
			<span>para los mensajes</span>
		</v-tooltip>


		<v-btn color="primary" class="ma-0 mt-4 wide" :disabled="!changed" @click="filter()" full-width>Aplicar</v-btn>
		<v-snackbar
	  	bottom
      	v-model="sending"
    	>
    	{{ sendingUrl }}
    	<v-btn dark flat @click.native="sending = false">Close</v-btn>
    </v-snackbar>
	</div>
</template>

<script>
	import TouchFilter from './TouchFilter.vue'
	import InteractionFilter from './InteractionFilter.vue'

	export default {
		props: ['courses', 'index'],
		data() {
			return {
				// General
				lastCourse: undefined,
				changed: false,

				// Courses
				course: undefined,

				// TouchFilter
				nodes: undefined,
				interactions: undefined,
				individualNodes: undefined,

				// Interaction types
				interactionTypes: undefined,

				// Date				
				startDateField: false,
				endDateField: false,
				startDate: undefined,
				endDate: undefined,

				// Metrics
				metric: undefined,
				metrics: [
					{
						name: 'Grado de entrada',
						id: 'indegree',
						explanation: 'Recibe muchas interacciones'
					},	
					{
						name: 'Grado de salida',
						id: 'outdegree',
						explanation: 'Inicia muchas interacciones'
					},					
					{
						name: 'Cercania',
						id: 'closeness',
						explanation: 'Grado de influencia en el grupo'
					},
					{
						name: 'Egen vector',
						id: 'pagerank',
						explanation: 'Influencia por la importancia de sus contactos'
					},
					{
						name: 'Excentricity',
						id: 'eccentricity',
						explanation: 'Envia muchas interacciones'
					},
					{
						name: 'Betweenes',
						id: 'betweenes',
						explanation: 'Persona estratégica para diseminar información'
					}
				],
				polarity: false,
				sending: false,
				sendingUrl: ''
			}
		},
		computed: {
			startDateFormatted: {
				get() {
					if (!this.startDate) return null
	        		const [year, month, day] = this.startDate.split('-')
	        		return `${day}/${month}/${year}`
				},
				set(newDate) {
					this.startDate = newDate
					if (newDate == null) this.startDate = undefined 
				}
      		},
			endDateFormatted: {
				get() {
					if (!this.endDate) return null
	        		const [year, month, day] = this.endDate.split('-')
	        		return `${day}/${month}/${year}`			
	        	},
				set(newDate) {
					this.endDate = newDate
					if (newDate == null) this.endDate = undefined 
				}
      		}
		},
		methods: {
			clone(obj) {
				return JSON.parse(JSON.stringify(obj))
			},
			mergeSelected(dictionary) {
				return Object.entries(dictionary).filter(e=>e[1]).map(e=>e[0]).join('')
			},
			assignTouchFilter(data) {
				this.changed = true
				this.nodes = data.nodeTypes
				this.interactions = data.interactionTypes
				this.individualNodes = data.individualNodes
			},

			filter() {
				var metricInfo = undefined
				var metricId = this.metric? this.metric.id : undefined

				if (metricId) {
					let interactions = this.mergeSelected(this.interactions)
					let interactionTypes = this.mergeSelected(this.interactionTypes)
					let nodos = this.mergeSelected(this.nodes)
					let startDate = this.startDate? this.startDate : '*'
					let endDate = this.endDate? this.endDate : '*'
					
					let metricsQuerry = `calculo/${this.course}/${metricId}/${interactions}/${interactionTypes}/${nodos}/${startDate}/${endDate}`

					this.sendingUrl = metricsQuerry
					this.sending = true

					metricInfo = {
						id: metricId,
						querry: metricsQuerry
					}
				}

				if (this.lastCourse && (this.lastCourse != this.course)) {
					var touchFilter = this.$refs.touchFilter
					touchFilter.clearIndividualNodes()
					this.individualNodes = undefined
				}

				this.lastCourse = this.course

				this.$emit('filter', {
					course: this.course,
					nodes: this.clone(this.nodes),
					individualNodes: this.individualNodes? this.clone(this.individualNodes) : undefined,
					interactions: this.clone(this.interactions),
					interactionTypes: this.clone(this.interactionTypes),
					startDate: this.startDate,
					endDate: this.endDate,
					metric: metricInfo,
					polarity: this.polarity
				})
				this.changed = false	
			},
		},
		mounted() {
			if (this.courses && this.courses[0]) {
				this.course = this.courses[0].id_curso 
			}
			this.filter()
			this.changed = false
		},
		watch: {
			course() {
				this.changed = true
			},
			polarity() {
				this.changed = true
			},
			interactionTypes: {
				handler() {
					this.changed = true
				},
				deep: true
			},
			startDate() {
				this.changed = true
			},
			endDate() {
				this.changed = true
			},
			metric() {
				this.changed = true
			}
		},
		components: {
			touchFilter: TouchFilter,
			interactionFilter: InteractionFilter
		}
	}
</script>

<style scoped>

	h2 {
		border-bottom: 2pt solid black;
		padding-left: 5pt;

	}
	h2 i {
		margin-right: 5pt;
		float: right;
	}

	button.wide {
		width: 100%;
	}
</style>
