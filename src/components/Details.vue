<template>
	<div class="details">
		<div v-if="focused==undefined" class="empty">
			Seleccione un nodo o una interraccion para ver mas detalles.
		</div>
		<div v-if="focused && focused.isNode" class="alumno">
			<v-chip><v-avatar style="background-color: white;"><img :src="avatar"></v-avatar><strong>{{ type }}</strong> {{ name }}</v-chip>
			 <span> Cursos: {{ listar_cursos }}</span>
		</div>
		<div v-if="focused && !focused.isNode" class="interaccion">
			<v-chip><strong>Interaccion</strong></v-chip> <span> Cantidad: {{ value }} | {{ origin_target }}</span>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['focused', 'dataGraphIndex'],
		watch: {
			'$props':{
				handler: function (val, oldVal) { 
					this.refresh()
				},
				deep: true
			}
		},
		filters: {
			capitalize(value) {
				if (!value) return ''
				value = value.toString()
				return value.charAt(0).toUpperCase() + value.slice(1)
			}
		},
		computed: {
			listar_cursos() {
				// debugger
				var list = this.courses
				if (!list || list.length == 0) return 'No asignado'
				var list_numbers = eval('[' + list + ']')
				var index = this.dataGraphIndex
				var list_names = list_numbers.map(function(c) {
					// debugger
					return index.courses[c].titulo
				})
				if (list_names.length == 1) return list_names[0]
				return list_names.slice(0, list_names.length - 1).join(', ') + ' y ' +list_names[list_names.length - 1]
			},
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
				edgeDetails: undefined
			}
		}
	}
</script>

<style>    
	.details {
		background-color: #8e44ad !important;
	    color: white;
	    /* width: 100%; */
	    position: fixed;
	    padding: 3pt 7pt;
	    margin: 5pt 7pt;
	    left: 0pt;
	    bottom: 0pt;
	    border-radius: 3pt;
	}

	
</style>