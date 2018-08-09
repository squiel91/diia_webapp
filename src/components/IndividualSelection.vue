<template>
	<v-dialog v-model="modal" max-width="500px">
		<v-card>
			<v-card-title>
				<span>Filtro Individual</span>
				<v-spacer></v-spacer>
				<v-menu bottom left>
					<v-btn icon slot="activator">
						<v-icon>more_vert</v-icon>
					</v-btn>
					<v-list>
						<v-list-tile @click="batchChange(true)">
							<v-list-tile-title>Seleccionar todos</v-list-tile-title>
						</v-list-tile>
						<v-list-tile @click="batchChange(false)">
							<v-list-tile-title>No seleccionar ninguno</v-list-tile-title>
						</v-list-tile>
					</v-list>
				</v-menu>
			</v-card-title>
			<v-card-text>
				<v-checkbox color="primary" class="ma-0" v-for="element in elementSelection" :key="element.info.id" 
				:label="element.info.nombre || element.info.titulo" v-model="element.selected" :value="element.selected"></v-checkbox>
			</v-card-text>
			<v-card-actions>
				<v-btn color="primary" flat @click="$emit('close')">Cancelar</v-btn>
				<v-btn color="primary" dark @click="$emit('change')">Aceptar</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
	export default {
		props: ['elements', 'selection'],
		data() {
			return {
				newSelection: {}
			}
		},
		computed: {
			modal: {
				get() {
					return this.selection != "" && 'a|e|m'.indexOf(this.selection) >= 0 
				},
				set(value) {
					if (!value) {
						this.$emit('close')
					}
				}
			},
			elementSelection() {
				return this.selection? this.elements[this.selection] : []
			}
		},
		methods: {
			batchChange(value) {
				var self = this
				Object.keys(this.elementSelection).forEach(element_key => {
					self.elements[self.selection][element_key].selected = value
				})
			}
		}
	}

</script>

<style scoped>

</style>