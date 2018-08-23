<template>
	<div class="details">
		<div v-if="!focused" class="empty">
			<v-chip><v-avatar style="background-color: white;"><v-icon>help</v-icon></v-avatar>Seleccione un nodo o una interraccion para ver mas detalles</v-chip>
		</div>
		<div v-if="focused && focused.isNode" class="alumno">
			<v-chip><v-avatar style="background-color: white;"><img :src="avatar"></v-avatar><span class="type">{{ type }}</span> {{ name }}</v-chip>
		</div>
		<div v-if="focused && !focused.isNode" class="interaccion">
			<v-chip>
				<v-avatar style="background-color: white;"><img :src="`img/${value < 8? value : 'many'}.png`"></v-avatar>
				<span class="type">Interaccion</span><span> {{ origin_target }}</span>
			</v-chip>
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
			avatar() {
				var node_images = {
					Docente: 'img/docente.png', // docente
					Alumno: 'img/estudiante.png', // estudiante
					Material: 'img/recurso.svg', // recurso
					Actividad: 'img/actividad.png' // actvidad
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
						this.origin_target = `${this.dataGraphIndex.nodes[this.edgeDetails.source].nombre} ---- ${this.dataGraphIndex.nodes[this.edgeDetails.target].nombre}`						
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
/*		background-color: #8e44ad !important;*/
/*	    color: white;*/
	    /* width: 100%; */
	    position: fixed;
	    padding: 3pt 7pt;
	    margin: 5pt 7pt;
	    left: 0pt;
	    bottom: 0pt;
	    border-radius: 3pt;
	}

	.type {
		font-weight: bold;
		margin-right: 5pt
	}
</style>