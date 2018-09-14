<template>
	<div class="filters">
		<h2><v-icon style="color: black !important;">event</v-icon>Período</h2>
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
		<h2><v-icon style="color: black !important;">timeline</v-icon>Nodos e interacciones</h2>
		<touchFilter @individualSelection="individualSelection($event)" ref="touchFilter" :nodes="index.nodes" @change="assignTouchFilter($event)"></touchFilter>
		<h2><v-icon style="color: black !important;">filter_list</v-icon>Tipos de interacciones</h2>
		<interactionFilter @change="interactionTypes=$event"></interactionFilter>
		<h2><v-icon style="color: black !important;">web</v-icon>Plataformas</h2>
		<platformFilter @change="platforms=$event"></platformFilter>
		<h2><v-icon style="color: black !important;">data_usage</v-icon>Métricas sociales</h2>
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
			<v-switch slot="activator" class="ma-0" color="primary" label="Sentimiento de interacciones" v-model="polarity"></v-switch>
			<span>Solo entre enstudiantes</span>
		</v-tooltip>


		<v-btn :loading="loadingCount > 0" color="primary" class="ma-0 mt-4 wide" :disabled="(loadingCount <= 0) && !changed" @click="filter()" full-width>Aplicar</v-btn>
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
	import PlatformFilter from './Platforms.vue'
	import TouchFilter from './TouchFilter.vue'
	import InteractionFilter from './InteractionFilter.vue'

	export default {
		props: ['course', 'index'],
		data() {
			return {
				// General
				changed: false,
				loadingCount: 0,

				// TouchFilter
				nodes: undefined,
				interactions: undefined,
				individualNodes: undefined,

				// Interaction types
				interactionTypes: undefined,
				platforms: undefined,

				// Date				
				startDateField: false,
				endDateField: false,
				startDate: undefined,
				endDate: undefined,

				// Metrics
				metric: undefined,
				metrics: [
					{
						name: 'Popularidad',
						id: 'idc',
						explanation: 'Recibe muchas interacciones'
					},	
					{
						name: 'Participación',
						id: 'odc',
						explanation: 'Inicia muchas interacciones'
					},					
					{
						name: 'Cercanía',
						id: 'clc',
						explanation: 'Llega a muchos en comunicación'
					},
					{
						name: 'Influencia',
						id: 'prc',
						explanation: 'Está conectado a otros que son influyentes'
					},
					{
						name: 'Distancia',
						id: 'ecc',
						explanation: 'Interactúa poco'
					},
					{
						name: 'Puente',
						id: 'bec',
						explanation: 'Es el enlace para alcanzar un grupo'
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
			softReset() {
				this.$refs.touchFilter.softReset()
				this.changed = true
			},
			individualSelection(event) {
				this.$emit('individualSelection', event)
			},
			loading(modifyBy) {
				this.loadingCount += modifyBy
				if (this.loadingCount < 0) this.loadingCount = 0
			},
			clone(obj) {
				return JSON.parse(JSON.stringify(obj))
			},
			mergeSelected(dictionary) {
				return Object.entries(dictionary).filter(e=>e[1]).map(e=>e[0]).join('')
			},
			assignTouchFilter(data) {
				this.changed = true
				this.nodes = data.nodeTypes
				this.interactions = data.interactionTypes // carefull, is not the same top level interactionTypes
				this.individualNodes = data.individualNodes
			},

			filter() {
				var metricInfo = undefined
				var metricId = this.metric? this.metric.id : undefined

				if (metricId) {
					let interactions = this.mergeSelected(this.interactions)
					let interactionTypes = this.mergeSelected(this.interactionTypes)
					let platforms = this.mergeSelected(this.platforms)
					let nodos = this.mergeSelected(this.nodes)
					let startDate = this.startDate? this.startDate : '*'
					let endDate = this.endDate? this.endDate : '*'
					
					let metricsQuerry = `calculo/${this.$store.getters.docente}/${this.course}/${metricId}/${interactions}/${interactionTypes}/${nodos}/${startDate}/${endDate}`

					this.sendingUrl = metricsQuerry
					this.sending = true

					metricInfo = {
						id: metricId,
						querry: metricsQuerry
					}
				}

				this.loadingCount = 1

				this.$emit('filter', {
					nodes: this.clone(this.nodes),
					individualNodes: this.individualNodes? this.clone(this.individualNodes) : undefined,
					interactions: this.clone(this.interactions),
					interactionTypes: this.clone(this.interactionTypes),
					platforms: this.clone(this.platforms),
					startDate: this.startDate,
					endDate: this.endDate,
					metric: metricInfo,
					polarity: this.polarity
				})
				this.changed = false	
			},
		},
		mounted() {
			this.filter()
			this.changed = false
		},
		watch: {
			course() {
				var touchFilter = this.$refs.touchFilter
				touchFilter.clearIndividualNodes()
				this.individualNodes = undefined
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
			platforms: {
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
			platformFilter: PlatformFilter,
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
