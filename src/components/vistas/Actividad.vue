<template>
	<div class="vistaActividad">
		<h1>{{ typeName }}: {{ name }}</h1>
		
		<div class="grid">
			<div class="cell"><img :src="platformImg"><label>Plataforma: {{ platformName }}</label></div>

			<div class="cell"><img :src="contentTypeImg"><label>Contenido: {{ contentTypeName }}</label></div>
		</div>
		<v-chip><v-avatar><v-icon>link</v-icon></v-avatar><a :href="uri" target="_blank">{{ uri }}</a></v-chip>
		<div><v-chip><v-avatar><v-icon>visibility</v-icon></v-avatar>Cantidad de accesos: {{ accessQty }}</v-chip></div>
		<div><v-chip><v-avatar><v-icon>event</v-icon></v-avatar>Publicado el {{ published | formatDate }}</v-chip></div>
		<div><v-chip><v-avatar><v-icon>event</v-icon></v-avatar>Completar hasta {{ deadline | formatDate }}</v-chip></div>
		<!-- <div><v-chip><v-avatar><v-icon>maximize</v-icon></v-avatar>Calificacion maxima: {{ maxScore }}</v-chip></div> -->
		<!-- <div><v-chip><v-avatar><v-icon>minimize</v-icon></v-avatar>Calificacion minima: {{ minScore }}</v-chip></div> -->
		<h2>Calificaciones de estudiantes</h2>
		<v-data-table
		 rows-per-page-text="Recursos por página"
		 :rows-per-page-items="[10,20,30, {text:'Todos', value: -1}]"
		 :headers="headers"
		 :items="results"
		 class="elevation-1"
		>
			<template slot="items" slot-scope="props">
				<td>{{ props.item.nombre }}</td>
				<td class="text-xs">{{ props.item.fecha_entrega }}</td>
				<td class="text-xs">{{ props.item.nota }}</td>
			</template>
			<template slot="no-data">
		    	<v-alert :value="true" color="error" icon="warning">
		        Aún no hay ninguna calificación por mostrar.
		      </v-alert>
		    </template>
		</v-data-table>
	</div>
</template>

<script>
export default {
		data() {
			return {
				fetchedId: undefined,
				name: undefined,
				uri: undefined,
				platform: undefined,
				contentType: undefined,
				type: undefined,
				accessQty: undefined,
				deadline: undefined,
				published: undefined,
				minScore: undefined,
				maxScore: undefined,
				results: [],
				package: undefined,
				headers: [
					{
						text: 'Nombre',
						align: 'left',
						value: 'nombre'
					},
					{ text: 'Fecha de entrega', value: 'fecha_entrega', align: 'left'},
					{ text: 'calificación', value: 'nota', align: 'left'}
				]
			}
		},
		props: ['id'],
		watch: {
			'$props': {
				handler() {
					this.fetchData()
				},
				deep: true
			}
		},
		mounted() {
			this.fetchData()
		},
		filters: {
			formatDate(date) {
				var days_name = {
					'0': 'Lunes',
					'1': 'Martes',
					'2': 'Miércoles',
					'3': 'Jueves',
					'4': 'Viernes',
					'5': 'Sábado',
					'6': 'Domingo'
				}
				var months_name = {
					'0': 'Enero',
					'1': 'Febrero',
					'2': 'Marzo',
					'3': 'Abril',
					'4': 'Mayo',
					'5': 'Junio',
					'6': 'Julio',
					'7': 'Agosto',
					'8': 'Septiembre',
					'9': 'Octubre',
					'10': 'Noviembre',
					'11': 'Diciembre'
				}
				var d = new Date(date)
				return `${days_name[d.getDay()]} ${d.getDate()} de ${months_name[d.getMonth()]} del ${d.getFullYear()}, ${d.getHours()} horas y ${d.getMinutes()} minutos`
				return date
			}
		},
		computed: {
			typeName() {
				return {
					qu: 'Quiz',
					ec: 'Encuesta'
				}[this.type]
			},
			platformName() {
				return {
					p: 'PAM',
					c: 'CREA 2',
					f: 'Facebook',
					m: 'Uruguay Educa'
				}[this.platform]
			},
			platformImg() {
				return {
					p: 'img/pam.png',
					c: 'img/crea.png',
					f: 'img/facebook.png',
					m: 'img/moodle.png'
				}[this.platform]
			},
			contentTypeName() {
				return {
					vid: 'video',
					img: 'imágen',
					tex: 'texto',
					gif: 'GIF animado',
					des: 'Desconocido',
					lik: 'Enlace'
				}[this.contentType]
			},
			contentTypeImg() {
				return {
					vid: 'img/vid.png',
					img: 'img/img.png',
					tex: 'img/tex.png',
					gif: 'img/gif.png',
					des: 'img/unknown.png',
					lik: 'img/link.png'
				}[this.contentType]
			}
		},
		methods: {
			dateFormatted(timestamp) {
				if (!timestamp) return null

				var d = new Date(timestamp)
				var hour = String(d.getHours())
				if (hour.length == 1) hour = '0' + hour
				var minutes = String(d.getMinutes())
				if (minutes.length == 1) minutes = '0' + minutes
				return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()} ${hour}:${minutes}`
      		},
			fetchData() {
				if (this.id && this.id != this.fetchedId) {
					this.$http.get(`http://179.27.71.27/actividad/${this.id}`)
						.then(data => {
							let activity = data.body.length == undefined? data.body : data.body[0]
							this.package = activity
							this.fetchedId = this.id
							this.$emit('loaded')

							this.name = activity.nombre
							this.uri = activity.url_ubicacion
							this.platform = activity.plataforma
							this.contentType = activity.tipo_contenido
							this.type = activity.tipo
							this.accessQty = activity.numero_accesos
							this.deadline = activity.fecha_limite
							this.published = activity.fecha_publicacion
							this.minScore = activity.calificacion_minima
							this.maxScore = activity.calificacion_maxima
							this.egoGraph = activity.egoGrafo
							this.results = (activity.resultados || activity.egoGrafo.resultados)
							this.results = this.results.map(a => {
								return {
									nombre: a.nombre,
									fecha_entrega: this.dateFormatted(a.fecha_entrega),
									nota: parseFloat(a.nota)
								}
							})
							
						}, error => {
							console.log('Error at retrieving activity:')
							console.log(error)
						}	
					)
				}
			}
		}
	}
</script>

<style scoped>
	h2 {
		margin-top: 20pt;
	}

	img {
		width: 100%
	}

	.cell {
		border-bottom: 2pt solid lightgray;
		border-radius: 7pt;
		display: inline-block;
		padding: 7pt;
		width: 110pt;
	    height: 140pt;
	    overflow: hidden;
		margin-right: 10pt;
	}

	.grid {

		margin-top: 20pt;
		margin-bottom: 20pt
	}

	.cell label {
		display: block;
		text-align: center;
		color: gray;
		font-size: small;
	}

	.vistaActividad {
		padding-top: 25pt;
	    padding: 25pt 15pt 0pt 15pt;
	    max-width: 1000px;
	    margin: 0pt auto;
	}
</style>