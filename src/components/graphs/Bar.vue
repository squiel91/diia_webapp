<template>
	<div class="number graph">
		<div class="axis" style="margin-left: 25pt;">{{ data.nombre_eje_y }}</div>
		<canvas ref="graphContainer"/>
		<div class="axis" style="text-align: right; margin-right: 15pt;">{{ data.nombre_eje_x }}</div>
		<h1>{{ data.titulo }}</h1>
	</div>
</template>

<script>
	function trimer(length, text) {
		return typeof(text) == 'string' && text.length > length? text.substring(0, length) + '...' : text
	}

	export default {
		props: ['data'],
		mounted() {
			let data = {
			    datasets: [{
			    	label: '',
			        data: this.values,
			        backgroundColor: this.colors

			    }],
			    labels: this.labels
			};
			let container = this.$refs.graphContainer
			var myPieChart = new Chart(container,{

			    type: 'bar',
			    data: data,
			    options: {
			    	legend: {
			    		display: false
			    	}
			    }
			});
		},
		computed: {
			values() {
				return this.data.datos.map(d => d.valor)
			},
			labels() {
				return this.data.datos.map(d => trimer(10, d.nombre))
			},
			colors() {
				return this.data.datos.map(d => d.color)
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
		border-bottom: 2pt solid lightgray;
    	border-radius: 7pt;
		margin: 5pt;
		padding: 20pt 0pt 5pt 0pt;
		margin-top: 15pt;

	}

	h1 {
		text-align: center;
		font-size: 15pt;
	}

	.axis {
		color: gray;
		font-style: italic;
	}
</style>