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
		<touchFilter :nodes="index.nodes" @change="assignTouchFilter($event)"></touchFilter>
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

		<v-btn color="primary" class="ma-0 mt-4 wide" :disabled="!changed" @click="filter()" full-width>Aplicar</v-btn>
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
				changed: false,

				// Courses
				course: undefined,

				// TouchFilter
				nodes: undefined,
				interactions: undefined,

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
						id: 'ge',
						explanation: 'Recibe muchas interacciones'
					},					
					{
						name: 'Cercania',
						id: 'cl',
						explanation: 'Grado de influencia en el grupo'
					},
					{
						name: 'Egen vector',
						id: 'ev',
						explanation: 'Influencia por la importancia de sus contactos'
					},
					{
						name: 'Excentricity',
						id: 'ex',
						explanation: 'Envia muchas interacciones'
					},
					{
						name: 'Betweenes',
						id: 'be',
						explanation: 'Persona estratégica para diseminar información'
					},
					{
						name: 'Polaridad de sentimiento',
						id: 'po',
						explanation: 'Para los mensajes entre personas'
					}
				]
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
			assignTouchFilter(data) {
				this.changed = true
				this.nodes = data.nodeTypes
				this.interactions = data.interactionTypes
			},
			filter() {
				console.log('filter')
				this.$emit('filter', {
					course: this.course,
					nodes: Object.assign({},this.nodes),
					interactions: Object.assign({},this.interactions),
					interactionTypes: Object.assign({},this.interactionTypes),
					startDate: this.startDate,
					endDate: this.endDate,
					metric: this.metric? this.metric.id : undefined
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
