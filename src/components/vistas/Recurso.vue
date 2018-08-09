<template>
	<div class="vistaRecurso">
		<h1>Material: {{ name }}</h1>
		<div class="grid">
			<div class="cell"><img :src="platformImg"><label>Plataforma: {{ platformName }}</label></div>
			<div class="cell"><img :src="contentTypeImg"><label>Contenido: {{ contentTypeName }}</label></div>
		</div>
		<v-chip><v-avatar><v-icon>link</v-icon></v-avatar><a :href="uri" target="_blank">{{ uri }}</a></v-chip>
		<div><v-chip><v-avatar><v-icon>visibility</v-icon></v-avatar>Cantidad de accesos: {{ accessQty }}</v-chip></div>
		<div><v-chip><v-avatar><v-icon>event</v-icon></v-avatar>Publicado el {{ publishedDate | formatDate }}</v-chip></div>
		<!-- <div>Tipo: {{ type }}</div> -->
	</div>
</template>

<script>
export default {
		data() {
			return {
				fetchedId: undefined,
				platform: undefined,
				name: undefined,
				type: undefined,
				contentType: undefined,
				publishedDate: undefined,
				accessQty: undefined,
				uri: undefined
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
		computed: {
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
					gif: 'GIF animado'
				}[this.contentType]
			},
			contentTypeImg() {
				return {
					vid: 'img/vid.png',
					img: 'img/img.png',
					tex: 'img/tex.png',
					gif: 'img/gif.png'
				}[this.contentType]
			}
		},
		filters: {
			formatDate(date) {
				return date
			}
		},
		methods: {
			fetchData() {
				if (this.id && this.id != this.fetchedId) {
					this.$http.get(`http://179.27.71.27/material/${this.id}`)
						.then(data => {
							// {
							// 	"idCurso":1,
							// 	"plataforma":"m",
							// 	"nombre":"Intro Lenguajes y AutÃ³matas 1",
							// 	"tipo":"ri",
							// 	"tipoContenido":"gif",
							// 	"fechaPublicacion":1522638000000,
							// 	"numeroAccesos":176,
							// 	"urlUbicacion":"www.lola-lemus.com"
							// }
							let material = data.body
							this.fetchedId = this.id
							this.$emit('loaded')

							this.fetchedId =  material.idCurso
							this.platform =  material.plataforma
							this.name = material.nombre
							this.type = material.tipo
							this.contentType =  material.tipoContenido
							this.publishedDate =  material.fechaPublicacion
							this.accessQty =  material.numeroAccesos
							this.uri =  material.urlUbicacion
							
						}, error => {
							console.log('Error at retrieving material:')
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
		margin-right: 10pt
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
	
	.vistaRecurso {
		padding-top: 25pt;
	    padding: 25pt 15pt 0pt 15pt;
	    max-width: 1000px;
	    margin: 0pt auto;
	}
</style>