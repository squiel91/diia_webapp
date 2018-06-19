<template>
	<li class="interaction_detail">
		<v-card>
        <v-card-title primary-title>
          <div>
            <h3 class="headline mb-0">{{ tipo_string }}</h3>
            <div>
				<div style="font-style: italic; color: gray;">{{ date_string }}</div>
				De <v-chip>{{ origen_string }}</v-chip> hacia <v-chip>{{ destino_string }}</v-chip>  en el curso {{ curso_string }}<span v-if="polaridad_string">, con <span style="font-weight: bold;" :style="{color: color_string}">polaridad {{ polaridad_string }}</span></span>.
            </div>
          </div>
        </v-card-title>
      </v-card>
	</li>
</template>

<script>
	export default {
		props: ['interaction', 'index'],
		data() {
			return {}
		},
		computed: {
			tipo_string() {
				let tipo_name = {
					vis: 'Visita',
					msj: 'Mensaje',
					pub: 'Publicacion',
					men: 'Mencion',
					rea: 'REA',
					com: 'Comunicacion'
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
			curso_string() {
				return `${this.index.courses[this.interaction.curso].titulo} (cod:${this.index.courses[this.interaction.curso].codigo})`
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
					'2': 'Miercoles',
					'3': 'Jueves',
					'4': 'Viernes',
					'5': 'Sabado',
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
				return `${days_name[d.getDay()]} ${d.getDate()} ${months_name[d.getMonth()]} del ${d.getFullYear()}, ${d.getHours()} horas y ${d.getMinutes()} minutos`
			}
		}
	}
</script>

<style>
	.interaction_detail {
    	padding: 5pt 20pt;
	}
</style>