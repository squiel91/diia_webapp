<template>
	<div class="condition">
	    <v-tooltip bottom>
			<v-btn class="mb-2" style="display: inline-block; margin-right: 0pt" slot="activator" icon @click="destroy()"><v-icon>delete</v-icon></v-btn>
	    	<span>Eliminar condición</span>
	    </v-tooltip>
		<v-select
		  :items="entitySchema"
		  v-model="selectedSchema"
		  item-text="title"
		  label="Columna"
		  return-object
		  solo
		  style="width: 200pt; display: inline-block; margin-right: 0pt"
		  hide-details
		></v-select>

		<v-select v-if="selected.name"
		 v-model="selected.operator"
		 label="Operador"
		 :items="selectedSchema.validOperators"
		 item-text="name"
		 item-value="value"
		 style="width: 90pt; display: inline-block; margin-right: 0pt"
		 solo
		 hide-details
		>
		</v-select>

		<template v-if="selected.operator">
			<v-text-field v-if="selectedSchema.valueType == 'real' || selectedSchema.valueType == 'integer'"
             v-model="intermediateNumberValue"
             type="number"
             label="Valor"
		 	 style="width: 90pt; display: inline-block; margin-right: 0pt"
             solo
             hide-details
          	>
          	</v-text-field>
          	</v-text-field>
			</v-select>
			<v-menu v-if="selectedSchema.valueType == 'date'"
			 lazy
			 v-model="dateField"
			 transition="scale-transition"
			 :close-on-content-click="false"
			>
				<v-text-field
				 slot="activator"
				 label="Fecha"
				 v-model="dateFormatted"
				 style="width: 90pt; display: margin-right: 0pt;"
				 solo
				 readonly
	             hide-details
				>
				</v-text-field>
				<v-date-picker v-model="selected.value" 
				 @input="dateField = false"
				 locale="es-419"
				 no-title
				></v-date-picker>
			</v-menu>
			<v-select v-if="selectedSchema.valueType == 'enumerated'"
			 v-model="selected.value"
		 	 :items="selectedSchema.values"
		 	 style="width: 200pt; display: inline-block;"
		 	 label="Opción"
		 	 solo
             hide-details
		 	>
			</v-select>
		</template>
	</div>
</template>

<script>
	let ARITHMETIC_OPERATORS = [
		{
			name: '<',
			value: '<' 
		},
		{
			name: '≤',
			value: '<=' 
		},
		{
			name: '=',
			value: '==' 
		},
		{
			name: '≥',
			value: '>=' 
		},
		{
			name: '>',
			value: '>' 
		}
	]

	let EQUAL_DIFFERENT_OPERATORS = [
		{
			name: '=',
			value: '==' 
		},
		{
			name: '≠',
			value: '!=' 
		}
	]

	let PLATFORMS = ['Facebook', 'PAM', 'CREA 2', 'Uruguay Educa']

	let CONTENT_TYPES = ['Video', 'Imágen', 'Texto', 'GIF animado', 'Desconocido', 'Enlace']
	
	export default {
		props: ['entity'],
		watch: {
			intermediateNumberValue(newValue) {
				let parsed = this.selectedSchema.valueType == 'real'? parseFloat(newValue) : parseInt(newValue)
				if (!isNaN(parsed)) {
					this.selected.value = parsed
				}
			},
			selectedSchema(selection) {
				if (selection) {
					this.selected.name = selection.name
				} else {
					this.selected.name = undefined
				}
			},
			selected: {
				handler() {
					this.emit()
				},
				deep: true
			}
		},
		data() {
			// date
			return {
				intermediateNumberValue: undefined,
				date: undefined,
				dateField: false,

				selectedSchema: undefined,
				selected: {
					name: undefined,
					operator: undefined,
					value: undefined
				},
				schema: { 
					e: [
						{
							name: 'notaprom',
							title: 'Nota promedio',
							validOperators: ARITHMETIC_OPERATORS,
							valueType: 'real'
						},
						{
							name: 'cantinter',
							title: '# Interacciones',
							validOperators: ARITHMETIC_OPERATORS,
							valueType: 'integer'
						},
						{
							name: 'ultimainter',
							title: 'Última interacción',
							validOperators: ARITHMETIC_OPERATORS,
							valueType: 'date'
						}
					],
					m : [
						{
							name: 'cont',
							title: 'Tipo de contenido',
							validOperators: EQUAL_DIFFERENT_OPERATORS,
							valueType: 'enumerated',
							values: CONTENT_TYPES
						},
						{
							name: 'plat',
							title: 'Plataforma',
							validOperators: EQUAL_DIFFERENT_OPERATORS,
							valueType: 'enumerated',
							values: PLATFORMS
						},
						{
							name: 'cantinter',
							title: '# Interacciones',
							validOperators: ARITHMETIC_OPERATORS,
							valueType: 'integer',
						},
						{
							name: 'public',
							title: 'Fecha de publicación',
							validOperators: ARITHMETIC_OPERATORS,
							valueType: 'date',
						}
					],
					a : [
						{
							name: 'cont',
							title: 'Tipo de contenido',
							validOperators: EQUAL_DIFFERENT_OPERATORS,
							valueType: 'enumerated',
							values: CONTENT_TYPES
						},
						{
							name: 'notaprom',
							title: 'Nota promedio',
							validOperators: ARITHMETIC_OPERATORS,
							valueType: 'real',
						},
						{
							name: 'plat',
							title: 'Plataforma',
							validOperators: EQUAL_DIFFERENT_OPERATORS,
							valueType: 'enumerated',
							values: PLATFORMS
						},
						{
							name: 'cantinter',
							title: '# Interacciones',
							validOperators: ARITHMETIC_OPERATORS,
							valueType: 'integer',
						},
						{
							name: 'public',
							title: 'Fecha de publicación',
							validOperators: ARITHMETIC_OPERATORS,
							valueType: 'date',
						}
					]
				}
			}
		},
		computed: {
			entitySchema() {
				return this.schema[this.entity]
			},
			dateFormatted() {
				if (!this.selected.value) return null
        		const [year, month, day] = this.selected.value.split('-')
        		return `${day}/${month}/${year}`
      		},
		},
		methods: {
			emit() {
				if (this.selected.name && this.selected.operator && this.selected.value) {
					this.$emit('change', this.selected)
				} else this.$emit('change', null)
			},
			destroy() {
				this.$emit('destroy')
			}
		}
	}
</script>

<style scoped>
	.condition {
		margin-bottom: 7pt
	}
</style>