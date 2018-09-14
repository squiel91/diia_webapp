<template>
	<v-dialog v-model="modal" max-width="1000px">
		<v-card>
			<v-card-title>
				<h1>Filtro personalizado de {{ entityName }}</h1>
			</v-card-title>
			<v-card-text>
				<p>Elija {{ entityNameComplete }} que quiera ver en el grafo. Puede
seleccionarlos directamente, o crear condiciones para filtrarlos. Al finalizar, presione “Aplicar” para visualizar los cambios en el grafo.</p>
				<conditionChain class="conditionChain" v-show="entity == 'e'" entity="e" @change="allConditions.e = $event"></conditionChain>
				<conditionChain class="conditionChain" v-show="entity == 'm'" entity="m" @change="allConditions.m = $event"></conditionChain>
				<conditionChain class="conditionChain" v-show="entity == 'a'" entity="a" @change="allConditions.a = $event"></conditionChain>
				<tableFilter :entity="entity" :index="index" :allConditions="allConditions" @change="selectedNodes = $event"></tableFilter>
			</v-card-text>
			<v-card-actions>
				<v-btn color="primary" @click="emit()">Aplicar</v-btn>
				<v-btn @click="$emit('close')">Cancelar</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
	import ConditionChain from './ConditionChain.vue'
	import TableFilter from './tableFilter.vue'

	export default {
		props: ['elements', 'entity'],
		components: {
			conditionChain: ConditionChain,
			tableFilter: TableFilter
		},
		data() {
			return {
				selectedNodes: [],
				allConditions: {
					e: [],
					m: [],
					a: []
				},
				index: [],
				entities: [
					{
						title: 'Estudiantes',
						name: 'e'
					},
					{
						title: 'Recursos',
						name: 'm'
					},
					{
						title: 'Actividades',
						name: 'a'
					}
				]
			}
		},
		methods: {
			emit() {
				for (var record of Object.values(this.index[this.entity])) record.selected = false
				for (var node of this.selectedNodes[this.entity]) this.index[this.entity][node.id].selected = true
				this.$emit('change')
				this.$emit(this.entity)
			}
		},
		computed: {
			entityNameComplete() {
				return {
					e: 'los estudiantes',
					m: 'los recursos',
					a: 'las actividades'
				}[this.entity] 
			},
			entityName() {
				return {
					e: 'estudiantes',
					m: 'recursos',
					a: 'actividades'
				}[this.entity] 
			},
			modal: {
				get() {
					if(this.entity != "" && 'a|e|m'.indexOf(this.entity) >= 0) {
						this.index = this.elements
						return true
					} else {
						return false
					}
				},
				set(value) {
					if (!value) {
						this.$emit('close')
					}
				}
			}
		}
	}

</script>

<style scoped>
	h1 {

	}

	p {
		margin-bottom: 15pt
	}

</style>