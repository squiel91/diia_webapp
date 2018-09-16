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
				var labels = []
				for (var dato of this.data.datos) {
					let percentage = this.getTotal == 0? 0 : this.trunc((dato.valor / this.getTotal) * 100)
					labels.push(`${dato.nombre} (${percentage}%)`)
				}
				return labels
			},
			colors() {
				return this.data.datos.map(d => d.color)
			},
			getTotal() {
				return this.data.datos.map(d => d.valor).reduce((a,b)=>a+b)
			}
		},
		methods: {
			trunc(value) {
				var [whole, decimal] = String(value).split('.')
				return whole
			}
		}
	}
</script>

<style scoped>
	.graph {
		border-bottom: 2pt solid lightgray;
    	border-radius: 7pt;
		margin: 5pt;
		margin-top: 15pt;
	}

	h1 {
		text-align: center;
		font-size: 15pt;
	}
</style>