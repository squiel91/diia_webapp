<template>
	<div class="tableFilters">
		<div v-if="entity == 'e'" class="selectedInfo">{{ selected['e'].length }} de {{ items? items.length : 0 }} seleccionados</div>
		<v-data-table
		 v-model="selected.e"
		 :rows-per-page="10"
		 select-all
		 rows-per-page-text="Alumnos por página"
		 :rows-per-page-items="[10,20,30, {text:'Todos', value: -1}]"
		 item-key="id"
		 v-if="entity == 'e'"
		 :headers="headers"
		 :items="items"
		 class="elevation-1"
		>
			<template slot="items" slot-scope="props">
				<tr :active="props.selected" @click="props.selected = !props.selected">
					<td>
						<v-checkbox
			             :input-value="props.selected"
			             color="primary"
			             primary
			             hide-details
			        	></v-checkbox>
	      			</td>
					<td>{{ props.item.nombre }}</td>
					<td class="text-xs-right">{{ props.item.notaprom }}</td>
					<td class="text-xs-right">{{ props.item.cantinter }}</td>
					<td class="text-xs-right">{{ props.item.ultimainterFormated }}</td>
				</tr>
			</template>
			<template slot="no-data">
		    	<v-alert :value="true" color="error" icon="warning">
		        No hay ningún estudiante para mostrar :(
		      </v-alert>
		    </template>
		</v-data-table>
		<div v-if="entity == 'm'" class="selectedInfo">{{ selected['m'].length }} de {{ items? items.length : 0 }} seleccionados</div>
		<v-data-table
		 rows-per-page-text="Recursos por página"
		 :rows-per-page-items="[10,20,30, {text:'Todos', value: -1}]"
		 v-model="selected.m"
		 select-all
		 item-key="id"
		 v-if="entity == 'm'"
		 :headers="headers"
		 :items="items"
		 class="elevation-1"
		>
			<template slot="items" slot-scope="props">
				<tr :active="props.selected" @click="props.selected = !props.selected">
					<td>
						<v-checkbox
			             :input-value="props.selected"
			             color="primary"
			             primary
			             hide-details
			        	></v-checkbox>
	      			</td>
	      		</tr>
				<td>{{ props.item.nombre }}</td>
				<td class="text-xs-right">{{ props.item.cont }}</td>
				<td class="text-xs-right">{{ props.item.plat }}</td>
				<td class="text-xs-right">{{ props.item.cantinter }}</td>
				<td class="text-xs-right">{{ props.item.publicFormated }}</td>
			</template>
			<template slot="no-data">
		    	<v-alert :value="true" color="error" icon="warning">
		        No hay ningún recurso para mostrar :(
		      </v-alert>
		    </template>
		</v-data-table>
		<div v-if="entity == 'a'" class="selectedInfo">{{ selected['a'].length }} de {{ items? items.length : 0 }} seleccionados</div>
		<v-data-table
		 v-model="selected.a"
		 rows-per-page-text="Actividades por página"
		 :rows-per-page-items="[10,20,30, {text:'Todos', value: -1}]"
		 select-all
		 item-key="id"
		 v-if="entity == 'a'"
		 :headers="headers"
		 :items="items"
		 class="elevation-1"
		>
			<template slot="items" slot-scope="props">
				<tr :active="props.selected" @click="props.selected = !props.selected">
					<td>
						<v-checkbox
			             :input-value="props.selected"
			             color="primary"
			             primary
			             hide-details
			        	></v-checkbox>
	      			</td>
	      		</tr>
				<td>{{ props.item.nombre }}</td>
				<td class="text-xs-right">{{ props.item.cont }}</td>
				<td class="text-xs-right">{{ props.item.notaprom }}</td>
				<td class="text-xs-right">{{ props.item.plat }}</td>
				<td class="text-xs-right">{{ props.item.cantinter }}</td>
				<td class="text-xs-right">{{ props.item.publicFormated }}</td>
			</template>
			<template slot="no-data">
		    	<v-alert :value="true" color="error" icon="warning">
		        No hay ninguna actividad para mostrar :(
		      </v-alert>
		    </template>
		</v-data-table>
	</div>
</template>

<script>

	let operatorsDict = {
		'<': (a, b) => a < b,
		'<=': (a, b) => a <= b,
		'==': (a, b) => a == b,
		'>=': (a, b) => a >= b,
		'>': (a, b) => a > b,
		'!=': (a, b) => a != b
	}

	export default {
		props: ['entity', 'index', 'allConditions'],
		watch: {
			entity() {
				if (!this.initialized) {
					this.initialized = true
					this.initialize()
				}
			},
			selected: {
				handler(newValue) {
					this.$emit('change', newValue)
				},
				deep: true
			},
			allConditions: {
				handler(newValue) {
					var selectedTemp = []
					for (var item of this.items) {
						if (this.compliesCondition(item, this.conditions)) {
							selectedTemp.push(item)
						}
					}
					this.selected[this.entity] = selectedTemp
				},	
				deep: true
			}
		},
		methods: {
			initialize() {
				for (var entity of 'eam')
					for (var record of Object.values(this.index[entity]))
						if (record.selected) this.selected[entity].push(record.info)
			},
			compliesCondition(item, conditions) {
				function compliesCondition(item, condition) {
					if (['public', 'ultimainter'].indexOf(condition.name) >= 0) {
						var [year, month, day] = condition.value.split('-')
						var morning = new Date(parseInt(year), parseInt(month) - 1, parseInt(day)).getTime()
						var night = new Date(parseInt(year), parseInt(month) -1, parseInt(day) + 1).getTime()
						
						switch (condition.operator) {
							case '==': return item[condition.name] > morning && item[condition.name] < night
							case '<': return item[condition.name] < morning
							case '<=': return item[condition.name] < night
							case '>': return item[condition.name] > night
							case '>=': return item[condition.name] > morning
						}
					} else {
						return operatorsDict[condition.operator](item[condition.name], condition.value)
					}
				}

				for (var condition of conditions) {
					if (!compliesCondition(item, condition)) return false
				}
				return true
			},
			dateFormatted(timestamp) {
				if (!timestamp) return null

				var d = new Date(timestamp)
				var hour = String(d.getHours())
				if (hour.length == 1) hour = '0' + hour
				var minutes = String(d.getMinutes())
				if (minutes.length == 1) minutes = '0' + minutes
				return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()} ${hour}:${minutes}`
				return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()} ${d.getHours()}:${minutes}`

      		}
		},
		computed: {
			conditions() {
				return this.allConditions[this.entity]
			},
			headers() {
				return this.entityHeaders[this.entity]
			},
			items() {
				let platformDict = {
					f: 'Facebook',
					p: 'PAM',		
					c: 'CREA 2',
					m: 'Moodle'
				}

				let contentTypeDict = {
						vid: 'Video',
						img: 'Imágen',
						tex: 'Texto',
						gif: 'GIF animado',
						des: 'Desconocido',
						lik: 'Enlace'
				}

				var preprocesedItems = JSON.parse(JSON.stringify(Object.values(this.index[this.entity]).map(n => n.info)))

				for (var preprocesedItem of preprocesedItems) {
					if (preprocesedItem.cont) {
						preprocesedItem.cont = contentTypeDict[preprocesedItem.cont]
					}
					if (preprocesedItem.plat) {
						preprocesedItem.plat = platformDict[preprocesedItem.plat]
					}
					if (preprocesedItem.notaprom) { 
						var [whole, decimal] = String(preprocesedItem.notaprom).split('.')
						preprocesedItem.notaprom = parseFloat(`${whole}.${decimal.substr(0,2)}`)
					}
					if (preprocesedItem.publicado) {
						preprocesedItem.public = preprocesedItem.publicado
					}
					if (preprocesedItem.public) {
						preprocesedItem.publicFormated = this.dateFormatted(preprocesedItem.public)
					}
					if (preprocesedItem.ultimainter) {
						preprocesedItem.ultimainterFormated = this.dateFormatted(preprocesedItem.ultimainter)
					}
				}

				return preprocesedItems
			}
		},
		data () {
			return {
				initialized: false,
				selected: {
					e: [],
					m: [],
					a: []
				},
				entityHeaders: {
					'e': [
						{
							text: 'Nombre',
							align: 'left',
							sortable: false,
							value: 'nombre'
						},
						{ text: 'Nota promedio', align: 'center', value: 'notaprom' },
						{ text: '# Interacciones', value: 'cantinter' },
						{ text: 'Última interacción', value: 'ultimainter' }
					],
					'm': [
						{
							text: 'Nombre',
							align: 'left',
							sortable: false,
							value: 'nombre'
						},
						{ text: 'Tipo de contenido', value: 'cont' },
						{ text: 'Plataforma', value: 'plat' },
						{ text: '# Interacciones', value: 'cantinter' },
						{ text: 'Fecha de publicación', value: 'public' }

					],
					'a': [
						{
							text: 'Nombre',
							align: 'left',
							sortable: false,
							value: 'nombre'
						},
						{ text: 'Tipo de contenido', value: 'cont' },
						{ text: 'Nota promedio', value: 'notaprom' },
						{ text: 'Plataforma', value: 'plat' },
						{ text: '# Interacciones', value: 'cantinter' },
						{ text: 'Fecha de publicación', value: 'public' }
					]
				}
			}
		}
	}
</script>

<style>
.selectedInfo {
    text-transform: uppercase;
    font-size: small;
    background-color: #757575;
    display: inline-block;
    color: white;
    font-weight: bold;
    padding: 1pt 8pt;
    float: right;
    border-radius: 4pt;
    position: relative;
    bottom: 25pt;
}
</style>