<template>
	<div class="graph" @click="emit()">
		<img :src="urlImgNodo"/>
		<h1>{{ data.nombre }}</h1>
		<h2>{{ data.titulo }}</h2>
	</div>
</template>

<script>
	export default {
		props: ['data'],
		computed: {
			urlImgNodo() {
				return {
					Docente: '/img/docente.png',
					Estudiante: '/img/estudiante.png',
					Actividad: '/img/recurso.svg',
					Material: '/img/actividad.png',
				}[this.data.tipoNodo]
			}
		},
		methods: {
			emit() {
				this.$emit('clicked',  {
					id: this.data.datos,
					isNode: true
				})
			}
		}
	}
</script>

<style scoped>
	.graph {
		cursor: pointer;
		border-bottom: 2pt solid lightgray;
    	border-radius: 7pt;
		margin: 5pt;
		margin-top: 15pt;
		padding: 15pt 0pt;
		width: 45%;
	    display: inline-block;
	    text-align: center;
	}

	img {
		width: 30pt;
	}

	h1 {
		font-size: 12pt;
		color: #8e44ad;
		text-align: center
	}

	h2 {
		margin-top: 0pt;
    	padding-top: 0pt;
		font-size: 15pt;
		text-align: center;
	}
</style>