<template>
	<div class="filters">
		<div class="calendar">
			<p>Para filtrar por fecha selecciona fecha de inicio y de fin.</p>
			<v-menu
				ref="menu"
				lazy
				v-model="startDateField"
				transition="scale-transition"
				offset-y
				:nudge-right="40"
			>
				<v-text-field
				slot="activator"
				label="Fecha inicio"
				v-model="startDate"
				prepend-icon="event"
				readonly
				>
				</v-text-field>
				<v-date-picker
				ref="picker"
				v-model="startDate"
				min="2017-01-01"
				:max="new Date().toISOString().substr(0, 10)"
				>
				</v-date-picker>
			</v-menu>
			<v-menu
				ref="menu"
				lazy
				v-model="endDateField"
				transition="scale-transition"
				offset-y
				:nudge-right="40"
			>
				<v-text-field
				slot="activator"
				label="Fecha fin"
				v-model="endDate"
				prepend-icon="event"
				readonly
				>
				</v-text-field>
				<v-date-picker
				ref="picker"
				v-model="endDate"
				min="2017-01-01"
				:max="new Date().toISOString().substr(0, 10)"
				>
				</v-date-picker>
			</v-menu>
		</div>
		<div class="course">
			Selecciona los cursos a mostrar.
			<v-checkbox v-for="course in courses" :key="course.id_curso" 
						:label="course.titulo" v-model="selectedCourses" :value="course.id_curso"></v-checkbox>
		</div>
		<div class="node_type">
			<v-btn color="normal" flat @click.stop="modalStudents = true">Filtrar por alumnos</v-btn>

			<v-dialog v-model="modalStudents" max-width="500px">
				<v-card>
					<v-card-title>
						<span>Filtro por alumno</span>
						<v-spacer></v-spacer>
						<v-menu bottom left>
							<v-btn icon slot="activator">
								<v-icon>more_vert</v-icon>
							</v-btn>
							<v-list>
								<v-list-tile @click="selectedStudents = studentIds">
									<v-list-tile-title>Seleccionar todos</v-list-tile-title>
								</v-list-tile>
								<v-list-tile @click="selectedStudents = []">
									<v-list-tile-title>No seleccionar ninguno</v-list-tile-title>
								</v-list-tile>
							</v-list>
						</v-menu>
					</v-card-title>
					<v-card-text>
						<v-checkbox v-for="student in students" :key="student.id" 
						:label="student.nombre" v-model="selectedStudents" :value="student.id"></v-checkbox>
					</v-card-text>
					<v-card-actions>
						<v-btn color="primary" flat @click.stop="modalStudents=false">Cancelar</v-btn>
						<v-btn color="primary" dark @click.stop="modalStudents=false">Aceptar</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
		</div>
		<div class="interaction_type">
			<v-select
			:items="interactionTypes"
			v-model="interactionType"
			label="Tipo de interaccion"
			item-value="text"
			></v-select>
		</div>
		<v-btn color="primary" class="ma-0 wide" :disabled="!changed" @click="change()" full-width>Filtrar</v-btn>
	</div>
</template>

<script>
	export default {
		props: ['courses', 'nodes'],
		methods: {
			change() {
				this.$emit('changeFilter', {
					students: this.selectedStudents,
					courses: this.selectedCourses,
					startDate: this.startDate,
					endDate: this.endDate,
					interactionsType: this.interactionType
				})
				this.changed = false
			}
		},
		computed: {
			students() {
				var students = []
				for (var node of this.nodes) {
					if (node.tipo == "e") {
						students.push(node)
					}
				}
				return students
			},
			studentIds() {
				var studentsIds = []
				for (var node of this.nodes) {
					if (node.tipo == "e") {
						studentsIds.push(node.id)
					}
				}
				return studentsIds
			}
		},
		watch: {
			'$props': {
				handler() {
					if (this.courses) {
						this.selectedCourses = Object.values(this.courses).map(r => r.id_curso) 
					}
					if (this.nodes) {
						this.selectedStudents = this.nodes.map(r => r.id) 
					}
				},
				deep: true
			},
			selectedStudents(newValue) {	
				this.conditions.students = newValue					
				this.changed = true
			},
			selectedCourses(newValue) {
				this.conditions.courses = newValue
				this.changed = true
			},
			startDate(newValue) {
				this.conditions.startDate = newValue
				this.changed = true
			},
			endDate(newValue) {
				this.conditions.endDate = newValue
				this.changed = true
			},
			interactionType(newValue) {
				this.conditions.interactionsType = newValue				
				this.changed = true
			}
		},
		data() {
			return {
				changed: false,
				startDateField: false,
				endDateField: false,
				selectedStudents: [],
				selectedCourses: [],
				modalStudents: false,
				conditions: {
					startDate: undefined,
					endDate: undefined,
					interactionsType: undefined,
					students: [],
					courses: []
				},
				startDate: "",
				endDate: "",
				interactionType: undefined,
				interactionTypes: [
					'todas',
					'alumno-alumno',
					'alumno-docente',
					'alumno-material/actividad'
				]
			}
		}
	}
</script>

<style>
	button.wide {
		width: 100%;
	}
</style>
