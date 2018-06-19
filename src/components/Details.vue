<template>
	<div class="details">
		<div v-if="focused==undefined" class="empty">
			Seleccione un nodo o una interraccion para ver mas detalles.
		</div>
		<div v-if="focused && focused.isNode" class="alumno">
			<v-chip><v-avatar style="background-color: white;"><img :src="avatar"></v-avatar><strong>{{ type }}</strong> {{ name }}</v-chip>
			 <span> Cursos: {{ courses | listar }}</span>
			<span> (Click para mas detalle)</span>
		</div>
		<div v-if="focused && !focused.isNode" class="interaccion">
			<v-chip><strong>Interaccion</strong></v-chip> <span> Cantidad: {{ value }} | {{ origin_target }}</span><v-btn @click="mostrarInteracciones = true">Mostrar</v-btn>
		</div>
		<v-dialog v-if="edgeDetails" v-model="mostrarInteracciones" fullscreen hide-overlay transiton="dialog-bottom-transition" scrollable>
			<v-card tile>
				<v-toolbar card dark color="primary">
				<v-btn icon dark @click.native="mostrarInteracciones=false">
					<v-icon>close</v-icon>
				</v-btn>
					<v-toolbar-title>Interacciones</v-toolbar-title>
				</v-toolbar>
				<ul>
					<interaction v-for="detailedEdge in edgeDetails.interactions" :interaction="detailedEdge" :index="dataGraphIndex"></interaction>
				</ul>
			</v-card>
		</v-dialog>
	</div>
</template>

<script>
	import Interaction from './Interactions.vue'

	export default {
		props: ['focused', 'dataGraphIndex'],
		components: {
			interaction: Interaction
		},
		watch: {
			'$props':{
				handler: function (val, oldVal) { 
					this.refresh()
				},
				deep: true
			}
		},
		filters: {
			listar(list) {
				if (!list) return ''
				if (list.length == 1) return list[0]
				return list.slice(0, list.length - 1).join(', ') + ' y ' +list[list.length - 1]
			},
			capitalize(value) {
				if (!value) return ''
				value = value.toString()
				return value.charAt(0).toUpperCase() + value.slice(1)
			}
		},
		computed: {
			avatar() {
				var node_images = {
					Docente: 'img/img0.png', // docente
					Alumno: 'img/img1.png', //alumno
					Material: 'img/img2.png', // material
					Actividad: 'img/img2.png' // actvidad
				}
				return node_images[this.type]
			}
		},
		methods: {
			refresh() {
				var short = {
					e: 'Alumno',
					d: 'Docente',
					m: 'Material',
					a: 'Actividad'
				}
				// console.log(this.focused)
				if (this.focused) {
					// console.log(this.focused)
					if (this.focused.isNode) {
						this.edgeDetails = undefined
						var nodeDetails = this.dataGraphIndex.nodes[this.focused.id]
						this.name = nodeDetails.nombre
						this.courses = nodeDetails.cursos
						this.type = short[nodeDetails.tipo]
					} else {
						this.edgeDetails = this.dataGraphIndex.combined_edges_index[this.focused.id]
						this.origin_target = `${this.dataGraphIndex.nodes[this.edgeDetails.source].nombre} a ${this.dataGraphIndex.nodes[this.edgeDetails.target].nombre}`						
						this.value = this.edgeDetails.value
					}
				}
				return undefined
			}
		},
		data() {
			return {
				name: undefined,
				origin_target: undefined,
				courses: undefined,
				course: undefined,
				date: undefined,
				value: undefined,
				type: undefined,
				mostrarInteracciones: false,
				edgeDetails: undefined
			}
		}
	}
</script>

<style>
	
</style>