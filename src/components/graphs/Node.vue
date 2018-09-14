<template>
	<div class="graph">

		
		<h1><img :src="urlImgNodo"/> {{ data.nombre }}</h1>
		<h2>{{ data.titulo }}</h2>
	</div>
</template>

<script>
	export default {
		props: ['data'],
		computed: {
			urlImgNodo() {
				return {
					d: '/img/docente.png',
					e: '/img/estudiante.png',
					a: '/img/recurso.svg',
					m: '/img/actividad.png',
				}[this.data.tipoNodo]
			}
		}
	}
</script>

<style scoped>
	.graph {
		border: 1pt solid lightgray;
		margin: 5pt;
		padding: 15pt 0pt;
		border-radius: 3pt;
	}

	h1 {
		font-size: 30pt;
		text-align: center
	}

	h2 {
		margin-top: 0pt;
    	padding-top: 0pt;
		font-size: 20pt;
		text-align: center;
	}
</style>