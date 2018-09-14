<template>
	<div class="number graph">
		<canvas ref="graphContainer"/>
		<h1>{{ data.titulo }}</h1>
	</div>
</template>

<script>
	export default {
		props: ['data'],
		mounted() {
			let data = {
			    datasets: [{
			        data: this.values,
			        backgroundColor: this.colors

			    }],
			    labels: this.labels
			};
			let container = this.$refs.graphContainer
			var myPieChart = new Chart(container,{
			    type: 'doughnut',
			    data: data,
			});
		},
		computed: {
			values() {
				return this.data.datos.map(d => d.valor)
			},
			labels() {
				return this.data.datos.map(d => d.nombre)
			},
			colors() {
				return this.data.datos.map(d => '#'+Math.floor(Math.random()*16777215).toString(16)) 
			}
		},
		filters: {
			trunc(value) {
				var [whole, decimal] = String(value).split('.')
				if (!decimal) 
					return value
					else return parseFloat(`${whole}.${decimal.substr(0,2)}`)
			}
		}
	}
</script>

<style scoped>
	.graph {
		border: 1pt solid lightgray;
		margin: 5pt;
		border-radius: 3pt;
		
	}

	h1 {
		text-align: center
	}
</style>