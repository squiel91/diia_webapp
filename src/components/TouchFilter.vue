<template>
	<div class="container">
		<individualSelection
		:entity="individualSelectionModal"
		:elements="nodeIndex"
		@close="individualSelectionModal = ''"
		@change="individualSelectionModal = '' ||  emit()"
		@a="sendSelection('a')"
		@e="sendSelection('e')"
		@m="sendSelection('m')">
		</individualSelection>
		<table>
			<tr>
				<td>
					<div class="help">
						<img src="img/touch.png">
					</div>
				</td>
				<td></td>					
				<td @click="initialize() || (menuActividades != menuActividades)" :class="{selected: actividad}" class="entity actividad">
					<v-menu
				      v-model="menuActividades"
				      absolute
				      offset-y
				      style="max-width: 600px"
				    >
				    <div slot="activator">
						<img src="img/actividad.png">
						<div class="label">Actividad</div>
						<img class="funnel" v-if="personalizedSelectionActividad" src="img/funnel.png">
					</div>
					<v-list>
				        <v-list-tile
				          v-for="(item, index) in listItems"
				          :key="index"
				          @click="valueActividades = index"
				        >
				          <v-list-tile-title>{{ item.title }}</v-list-tile-title>
				        </v-list-tile>
				      </v-list>
				    </v-menu>
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
				<td @click="initialize() || (menuEstudiantes != menuEstudiantes)" :class="{selected: estudiante}" class="entity estudiante">
					<v-menu
				      v-model="menuEstudiantes"
				      absolute
				      offset-y
				      style="max-width: 600px"
				    >
				    <div slot="activator">
						<img src="img/estudiante.png">
						<div class="label">Estudiante</div>
						<img class="funnel" v-if="personalizedSelectionEstudiante" src="img/funnel.png">
					</div>
					<v-list>
				        <v-list-tile
				          v-for="(item, index) in listItems"
				          :key="index"
				          @click="valueEstudiantes = index"
				        >
				          <v-list-tile-title>{{ item.title }}</v-list-tile-title>
				        </v-list-tile>
				      </v-list>
				    </v-menu>
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
				<td @click="initialize() || (menuRecursos != menuRecursos)" :class="{selected: recurso}" class="entity recurso">
					<v-menu
				      v-model="menuRecursos"
				      absolute
				      offset-y
				      style="max-width: 600px"
				    >
				    <div slot="activator">
						<img src="img/recurso.svg">
						<div class="label">Recurso</div>
						<img class="funnel" v-if="personalizedSelectionRecurso" src="img/funnel.png">
					</div>
					<v-list>
				        <v-list-tile
				          v-for="(item, index) in listItems"
				          :key="index"
				          @click="valueRecursos = index"
				        >
				          <v-list-tile-title>{{ item.title }}</v-list-tile-title>
				        </v-list-tile>
				      </v-list>
				    </v-menu>
				</td>
				<td></td>
			</tr>
		</table>
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

				valueEstudiantes: undefined,
				valueRecursos: undefined,
				valueActividades: undefined,

				personalizedSelectionActividad: false,
				personalizedSelectionEstudiante: false,
				personalizedSelectionRecurso: false,

				menuEstudiantes: false,
				menuRecursos: false,
				menuActividades: false,

				listItems: [
			        { title: 'Seleccionar todos' },
			        { title: 'No selecionar ninguno' },
			        { title: 'Seleccion personalizada' }
			      ],
				
				nodeIndex: undefined,
				individualSelectionModal: ''
			}
		},
		watch: {
			valueEstudiantes(newValue) {
				if (newValue <= 1) this.personalizedSelectionEstudiante = false
				if(newValue == 0) {
					this.estudianteSelection(true)
				}
				if(newValue == 1) {
					this.estudianteSelection(false)
				}
				if(newValue == 2) {
					this.individualSelectionModal = 'e'
				}
				this.valueEstudiantes = undefined
			},
			valueRecursos(newValue) {
				if (newValue <= 1) this.personalizedSelectionRecurso = false
				if(newValue == 0) {
					this.recursoSelection(true)
				}
				if(newValue == 1) {
					this.recursoSelection(false)
				}
				if(newValue == 2) {
					this.individualSelectionModal = 'm'
				}
				this.valueRecursos = undefined
			},
			valueActividades(newValue) {
				if (newValue <= 1) this.personalizedSelectionActividad = false
				if(newValue == 0) {
					this.actividadSelection(true)
				}
				if(newValue == 1) {
					this.actividadSelection(false)
				}
				if(newValue == 2) {
					this.individualSelectionModal = 'a'
				}
				this.valueActividades = undefined
			}
		},
		components: {
			individualSelection: IndividualSelection
		},
		computed: {
			filterApliedEstudiantes() {
				return this.nodeIndex && this.nodeIndex.e && !Object.values(this.nodeIndex.e).every(a => a.selected)
			},
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
		methods: {
			softReset() {
				this.nodeIndex = undefined
				this.personalizedSelectionActividad = false
				this.personalizedSelectionEstudiante = false
				this.personalizedSelectionRecurso = false
			},
			sendSelection(entity) {
				switch (entity) {
					case 'a': {
						this.personalizedSelectionActividad = true
						this.actividadSelection(true, true)
						break
					}
					case 'e': {
						this.personalizedSelectionEstudiante = true
						this.estudianteSelection(true, true)
						break
					}
					case 'm': {
						this.personalizedSelectionRecurso = true
						this.recursoSelection(true, true)
					}
				}
				this.$emit('individualSelection', {
					entity: entity,
					nodes: this.nodeIndex[entity]
				})
			},
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
					individualNodes: {
						'd': null,
						'e': this.personalizedSelectionEstudiante? Object.assign({}, this.nodeIndex.e) : null,
						'm': this.personalizedSelectionRecurso? Object.assign({}, this.nodeIndex.m) : null,
						'a': this.personalizedSelectionActividad? Object.assign({}, this.nodeIndex.a) : null
					}
				})
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
			estudianteSelection(selected, dontRemovePersonalized) {
				if (selected) {
					if (this.actividad) this.estudianteActividad = true
					if (this.recurso) this.estudianteRecurso =true
					this.estudianteEstudiante = true
					if (this.docente) this.docenteEstudiante = true
					this.estudiante=true
				} else {
					this.estudianteActividad = false
					this.estudianteRecurso =false
					this.estudianteEstudiante = false
					this.docenteEstudiante = false
					this.estudiante=false
				}
				if (!dontRemovePersonalized) {
					this.personalizedSelectionEstudiante = false
				}
				this.emit()
			},
			recursoSelection(selected, dontRemovePersonalized) {
				if (selected) {
					if (this.docente) this.docenteRecurso = true
					if (this.estudiante) this.estudianteRecurso = true
					this.recurso = true
				} else {
					this.docenteRecurso = false
					this.estudianteRecurso = false
					this.recurso = false
				}
				if (!dontRemovePersonalized) {
					this.personalizedSelectionRecurso = false
				}
				this.emit()
			},
			actividadSelection(selected, dontRemovePersonalized) {
				if (selected) {
					if (this.docente) this.docenteActividad = true
					if (this.estudiante) this.estudianteActividad = true
					this.actividad = true
				} else {
					this.docenteActividad = false
					this.estudianteActividad = false
					this.actividad=false
				}
				if (!dontRemovePersonalized) {
					this.personalizedSelectionActividad = false
				}
				this.emit()
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
		width: 100pt;
		height: 50pt;
	}

	.funnel {
		width: 18pt;
	    position: absolute;
	    top: -3pt;
	    left: 22pt;
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
		cursor: pointer;
	}

	.interaction {
		cursor: pointer;
	}

	.label {
		text-align: center;
		font-size: small;
		color: gray;
		width: 52pt;
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
		width: 50pt;
		margin-right: 5pt;
	}

	.help p {
		margin-bottom: 16px;
		display: inline-block;
		width: 70px;
		color: gray;
		font-size: small;
	}

</style>