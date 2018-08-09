<template>
	<div class="container">
		<individualSelection 
		:selection="individualSelectionModal"
		:elements="nodeIndex"
		@close="individualSelectionModal = ''"
		@change="individualSelectionModal = '' ||  emit()">
		</individualSelection>
		<table>
			<tr>
				<td></td>
				<td></td>					
				<td @click="clickActividad()" :class="{selected: actividad}" class="entity actividad">
					<img src="img/actividad.png">
					<div class="label">Actividad</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td @click="clickDocenteActividad" :class="{selected: docenteActividad}" class="interaction"><div class="downUp"></div><div class="downUpCover"></div></td>					
				<td @click="clickEstudianteActividad()" :class="{selected: estudianteActividad}" class="interaction"><div class="vertical"></div></td>
				<td></td>
			</tr>
			<tr>
				<td @click="clickDocente()" :class="{selected: docente}" class="entity docente">
					<img src="img/docente.png">
					<div class="label">Docente</div>
				</td>
				<td @click="clickDocenteEstudiante()" :class="{selected: docenteEstudiante}" class="interaction"><div class="horizontal"></div></td>					
				<td @click="clickEstudiante()" :class="{selected: estudiante}" class="entity estudiante">
					<img src="img/estudiante.png">
					<div class="label">Estudiante</div>
				</td>
				<td @click="clickEstudianteEstudiante()" :class="{selected: estudianteEstudiante}" class="interaction"><div class="loop"></div></td>
			</tr>
			<tr>
				<td></td>
				<td @click="clickDocenteRecurso" :class="{selected: docenteRecurso}" class="interaction"><div class="upDown"></div><div class="upDownCover"></div></td>					
				<td @click="clickEstudianteRecurso()" :class="{selected: estudianteRecurso}" class="interaction"><div class="vertical"></div></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>					
				<td @click="clickRecurso()" :class="{selected: recurso}" class="entity recurso">
					<img src="img/recurso.svg">
					<div class="label">Recurso</div>
				</td>
				<td></td>
			</tr>
		</table>
		<div class="help" ref="help">
			<transition name="fade">
				<div v-if="first" class="help-message">
					<img src="img/touch.png"><p>Selecciona nodos e interacciones que quieras ver</p>
				</div>
			</transition>
			<transition name="fade">
				<div v-if="second" class="help-message">
					<img src="img/doubletouch.png"><p>Doble click en el nodo para seleccionar un subconjunto</p>
				</div>
			</transition>
		</div>
	</div>
</template>

<script>
	import IndividualSelection from './IndividualSelection.vue'

	export default {
		props: ['nodes'],
		data() {
			return {
				docente: true,
				estudiante: true,
				actividad: true,
				recurso: true,
				docenteActividad: true,
				docenteRecurso:true,
				docenteEstudiante: true,
				estudianteRecurso: true,
				estudianteActividad: true,
				estudianteEstudiante: true,
				clicksDocente: 0,
				clicksEstudiante: 0,
				clicksRecurso: 0,
				clicksActividad: 0,
				timer: undefined,
				delay: 500, // ms
				first: true,
				second: false,

				nodeIndex: undefined,
				individualSelectionModal: ''
			}
		},
		components: {
			individualSelection: IndividualSelection
		},
		computed: {
			interactionNodeTypes() {
				return {
					'de': this.docenteEstudiante,
					'dm': this.docenteRecurso,
					'da': this.docenteActividad,
					'ee': this.estudianteEstudiante,
					'ed': this.estudianteDocente,
					'em': this.estudianteRecurso,
					'ea': this.estudianteActividad
				}
			},
			nodeTypes() {
				return {
					'e': this.estudiante,
					'd': this.docente,
					'm': this.recurso,
					'a': this.actividad
				}
			}
		},
		created() {
			this.emit()
		},
		mounted() {
			var self = this
			setInterval(function() {
				if (self.first) {
					self.first = false
					setTimeout(function() {
						self.second = true
					},500)
				} else {
					self.second = false
					setTimeout(function() {
						self.first = true
					},500)
				}
			}, 5000)
		},
		methods: {
			initialize() {
				if (!this.nodeIndex && this.nodes) {
					var nodeIndex = {}
					for (var node of Object.values(this.nodes)) {
						if (!(node.tipo in nodeIndex)) {
							nodeIndex[node.tipo] = {}
						}
						nodeIndex[node.tipo][node.id] = {
							selected: true,
							info: node 
						}
					}
					this.nodeIndex = nodeIndex
				}
			},
			clearIndividualNodes() {
				this.individualNodes = undefined
			},
			emit() {
				this.$emit('change', {
					interactionTypes: this.interactionNodeTypes,
					nodeTypes: this.nodeTypes,
					individualNodes: Object.assign({}, this.nodeIndex)
				})
			},
			clickEstudiante(){
				this.initialize()
				this.clicksEstudiante++
				if(this.clicksEstudiante === 1) {
					var self = this
					this.timer = setTimeout(function() {
						self.clicksEstudiante = 0
						self.singleClickEstudiante()
					}, this.delay);
				} else{
					clearTimeout(this.timer)
					this.doubleClickEstudiante()
					this.clicksEstudiante = 0
				}         
			},
			clickRecurso(){
				this.initialize()
				this.clicksRecurso++
				if(this.clicksRecurso === 1) {
					var self = this
					this.timer = setTimeout(function() {
						self.clicksRecurso = 0
						self.singleClickRecurso()
					}, this.delay);
				} else{
					clearTimeout(this.timer)
					this.doubleClickRecurso()
					this.clicksRecurso = 0
				}         
			},
			clickActividad(){
				this.initialize()
				this.clicksActividad++
				if(this.clicksActividad === 1) {
					var self = this
					this.timer = setTimeout(function() {
						self.clicksActividad = 0
						self.singleClickActividad()
					}, this.delay);
				} else{
					clearTimeout(this.timer)
					this.doubleClickActividad()
					this.clicksActividad = 0
				}         
			},
			clickDocente() {
				if (this.docente) {
					this.docenteActividad = false
					this.docenteRecurso =false
					this.docenteEstudiante = false
					this.docente=false
				} else {
					if (this.actividad) this.docenteActividad = true
					if (this.recurso) this.docenteRecurso =true
					if (this.estudiante) this.docenteEstudiante = true
					this.docente=true
				}
				this.emit()
			},
			singleClickEstudiante() {
				if (this.estudiante) {
					this.estudianteActividad = false
					this.estudianteRecurso =false
					this.estudianteEstudiante = false
					this.docenteEstudiante = false
					this.estudiante=false
				} else {
					if (this.actividad) this.estudianteActividad = true
					if (this.recurso) this.estudianteRecurso =true
					this.estudianteEstudiante = true
					if (this.docente) this.docenteEstudiante = true
					this.estudiante=true
				}
				this.emit()
			},
			singleClickRecurso() {
				if (this.recurso) {
					this.docenteRecurso = false
					this.estudianteRecurso = false
					this.recurso = false
				} else {
					if (this.docente) this.docenteRecurso = true
					if (this.estudiante) this.estudianteRecurso = true
					this.recurso = true
				}
				this.emit()
			},
			singleClickActividad() {
				if (this.actividad) {
					this.docenteActividad = false
					this.estudianteActividad = false
					this.actividad=false
				} else {
					if (this.docente) this.docenteActividad = true
					if (this.estudiante) this.estudianteActividad = true
					this.actividad = true
				}
				this.emit()
			},
			doubleClickEstudiante() {
				if (!this.estudiante) this.singleClickEstudiante()
				this.emit()
				this.individualSelectionModal = 'e' 
				this.$emit('selectEstudiante')
			},
			doubleClickRecurso() {
				if (!this.recurso) this.singleClickRecurso()
				this.emit()
				this.individualSelectionModal = 'm'
				this.$emit('selectRecurso')
			},
			doubleClickActividad() {
				if (!this.actividad) this.singleClickActividad()
				this.emit()
				this.individualSelectionModal = 'a'
				this.$emit('selectActividad')
			},
			clickDocenteActividad() {
				if (this.docenteActividad) {
					this.docenteActividad=false
				} else {
					this.docente = true
					this.actividad = true
					this.docenteActividad=true
				}
				this.emit()
			},
			clickDocenteEstudiante() {
				if (this.docenteEstudiante) {
					this.docenteEstudiante=false
				} else {
					this.docente = true
					this.estudiante = true
					this.docenteEstudiante=true
				}
				this.emit()
			},
			clickDocenteRecurso() {
				if (this.docenteRecurso) {
					this.docenteRecurso=false
				} else {
					this.docente = true
					this.Recurso = true
					this.docenteRecurso=true
				}
				this.emit()
			},
			clickEstudianteEstudiante() {
				if (this.estudianteEstudiante) {
					this.estudianteEstudiante=false
				} else {
					this.estudiante = true
					this.estudianteEstudiante=true
				}
				this.emit()
			},
			clickEstudianteActividad() {
				if (this.estudianteActividad) {
					this.estudianteActividad=false
				} else {
					this.estudiante = true
					this.actividad = true
					this.estudianteActividad=true
				}
				this.emit()
			},
			clickEstudianteRecurso() {
				if (this.estudianteRecurso) {
					this.estudianteRecurso=false
				} else {
					this.estudiante = true
					this.recurso = true
					this.estudianteRecurso=true
				}
				this.emit()
			}
		}
	}
</script>

<style scoped>

	.container {
		width: 400pt
	}

	table img {
		width: 30pt;
		border-style: none;
		margin-top: 5pt;
		margin-bottom: -3pt;
		margin-left: 11pt;
	}

	.help {
		width: 100%;
		height: 50pt;
		margin-top: 10pt
	}

	.fade-enter-active, .fade-leave-active {
		transition: opacity .5s;
	}
	.fade-enter, .fade-leave-to {
		opacity: 0;
	}

	td {
		display: inline-block;
		width: 56pt;
		height: 56pt;
		margin: 0pt;
		margin-left: 2pt;
	}

	.entity {
		border: 2pt solid transparent;
		border-radius: 5pt;
	}

	.interaction {

	}

	.label {
		text-align: center;
		font-size: small;
		color: gray;
	}

	.selected .label {
		color: #8e44ad
	}

	.vertical {
		margin-top: 8%;
		width: 50%;
		height: 84%;
		border-right: 2pt solid gray;
	}

	.horizontal {
		margin-left: 8%;
		width: 84%;
		height: 50%;
		border-bottom: 2pt solid gray;	
	}

	.loop {
		width: 60%;
		height: 60%;
		margin-top: 20%;
		margin-left: 8%;
		border: 2pt solid gray;	
		border-left: none;
		border-top-right-radius: 20pt;
		border-bottom-right-radius: 20pt;
	}

	.upDown {
		width: 0;
		height: 0;
		border-style: solid;
		border-width: 48pt 0 0px 48pt;
		border-color: gray transparent transparent transparent;

	}

	.upDownCover {
		border-width: 45pt 0 0px 45pt;
		position: relative;
		bottom: 48pt;
		left: 3pt;
		width: 0;
		height: 0;
		border-style: solid;
		position: relative;
		border-color: white transparent transparent transparent;
	} 

	.downUp {
		width: 0;
		height: 0;
		border-style: solid;
		border-width: 0 0px 48pt 48pt;
		border-color: transparent transparent gray transparent;

	} 

	.downUpCover {
		border-width: 0 0px 45pt 45pt;
		position: relative;
		bottom: 45pt;
		left: 3pt;
		width: 0;
		height: 0;
		border-style: solid;
		position: relative;
		border-color: transparent transparent white transparent;
	} 

	.entity.selected {
		border: 2pt solid #8e44ad 
	}

	.interaction.selected .loop, 
	.interaction.selected .horizontal, 
	.interaction.selected .vertical  {
		border-color: #8e44ad;
		border-width: 4pt
	}

	.interaction.selected .downUp {
		border-color: transparent transparent #8e44ad transparent;
	}

	.interaction.selected .downUpCover {
		bottom: 42pt;
	}

	.interaction.selected .upDown {
		border-color: #8e44ad transparent transparent transparent;
	}

	.interaction.selected .upDownCover {
		bottom: 51pt;
	}

	.help img {
		display: inline-block;
		width: 30pt;
		margin-right: 5pt;
	}

	.help p {
		margin-bottom: 16px;
		display: inline-block;
		width: 200px;
		color: gray;
		font-size: small;
	}

</style>