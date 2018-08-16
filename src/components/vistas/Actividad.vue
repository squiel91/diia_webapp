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
		<div><v-chip><v-avatar><v-icon>maximize</v-icon></v-avatar>Calificacion maxima: {{ maxScore }}</v-chip></div>
		<div><v-chip><v-avatar><v-icon>minimize</v-icon></v-avatar>Calificacion minima: {{ minScore }}</v-chip></div>
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
				maxScore: undefined
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
					c: 'Crea 2',
					f: 'Facebook',
					m: 'Moodle'
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
					img: 'imagen',
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
			fetchData() {
				if (this.id && this.id != this.fetchedId) {
					this.$http.get(`http://179.27.71.27/actividad/${this.id}`)
						.then(data => {
							let activity = data.body.length == undefined? data.body : data.body[0]
							this.fetchedId = this.id
							this.$emit('loaded')

							this.name = activity.nombre
							this.uri = activity.url_ubicacion
							this.platform = activity.plataforma
							this.contentType = activity.tipo_contenido
							this.type = activity.tipo
							this.accessQty = activity.numero_accesos
							this.deadline = activity.fechaLimite
							this.published = activity.fecha_publicacion
							this.minScore = activity.calificacion_minima
							this.maxScore = activity.calificacion_maxima
							
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
	img {
		width: 100%
	}

	.cell {
		border: 2pt solid rgba(128, 128, 128, 0.30);
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