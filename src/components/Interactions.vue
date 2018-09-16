<template>
	<ul>
		<li class="interaction_detail">
			<v-card>
	        <v-card-title primary-title>
	          <div>
	            <h3 class="headline mb-0">{{ tipo_string }}</h3>
	            <div>
					<div style="font-style: italic; color: gray;">{{ date_string }}</div>
					De <v-chip>{{ origen_string }}</v-chip> hacia <v-chip>{{ destino_string }}</v-chip> <span v-if="interaction.polaridad">con <span style="font-weight: bold;" :style="{color: color_string}">polaridad {{ polaridad_string }}</span></span>.
	            </div>
	          </div>
	          	<v-spacer></v-spacer>
	        	<v-btn icon @click="extraInfo = !extraInfo">
            		<v-icon>{{ extraInfo ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
          		</v-btn>
          		<v-slide-y-transition>
		          <div class="extraInfo" v-if="extraInfo">
		          	<v-progress-linear v-if="!allInfo" :indeterminate="true"></v-progress-linear>
		            <div v-if="allInfo">
		            	<div>Tipo de contenido: <strong>{{ contentTypeName }}</strong><br>
		            	Contenido: {{ interaction.tipo == 'rea'? reactionName : content }}</div>
			        </div>
		          </div>
		        </v-slide-y-transition>
	        </v-card-title>
	      </v-card>
		</li>
	</ul>
</template>

<script>
	export default {
		props: ['interaction', 'index'],
		data() {
			return {
				extraInfo: false,
				allInfo: undefined,
				contentType: undefined,
				content: undefined
			}
		},
		methods: {
			fetchData() {
				if (!this.allInfo) {
					this.$http.get(`http://179.27.71.27/consulta/interaccion/${this.interaction.id_interaccion}`) 
					.then(data => {
						this.allInfo = data.body
						this.contentType = this.allInfo.tipoContenido
						this.content = this.allInfo.contenido 
					}, error => {
						console.log(error)
					})
				}
			}
		},
		watch: {
			extraInfo(newVal) {
				if(newVal) this.fetchData()
			}
		},

		computed: {
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
			reactionName() {
				return {
					NONE: 'Ninguna',
					LIKE: 'Gusta',
					LOVE: 'Amoroso',
					WOW: 'Asombro',
					HAHA: 'Gracioso',
					SAD: 'Tristeza',
					ANGRY: 'Enojo',
					THANKFUL: 'Agradecido'
				}[this.content]
			},
			tipo_string() {
				let tipo_name = {
					vis: 'Visto',
					msj: 'Mensaje',
					pub: 'Publicación',
					men: 'Mención',
					rea: 'Reacción',
					com: 'Comentario'
				}
				return tipo_name[this.interaction.tipo] || this.interaction.tipo 
				
			},
			color_string() {
				if (this.polaridad_string == 'positiva') {
					return 'green'
				}
				if (this.polaridad_string == 'negativa') {
					return 'red'
				}
				return 'gray'
			},
			polaridad_string() {
				let polaridad_name = {
					'p': 'positiva',
					'e': 'negativa',
					'n': 'neutra'
				}
				return polaridad_name[this.interaction.polaridad]
			},
			origen_string() {
				return this.index.nodes[this.interaction.origen].nombre
			},
			destino_string() {
				return this.index.nodes[this.interaction.destino].nombre
			},
			date_string() {
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
				var d = new Date(this.interaction.fecha)
				return `${days_name[d.getDay()]} ${d.getDate()} de ${months_name[d.getMonth()]} del ${d.getFullYear()}, ${d.getHours()} horas y ${d.getMinutes()} minutos`
			}
		}
	}
</script>

<style scoped>
	.extraInfo {
		clear: both;
		padding-top: 16pt;
		width: 100%;
	}

	ul {
		list-style-type: none;
		margin-top: 10pt;
	}

	.interaction_detail {
    	padding: 5pt 20pt;
	}
</style>