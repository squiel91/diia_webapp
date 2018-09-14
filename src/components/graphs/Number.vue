<template>
	<div class="number graph">
		<h1>{{ data.datos | trunc }}</h1>
		<h2>{{ data.titulo }}</h2>
	</div>
</template>

<script>
	export default {
		props: ['data'],
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
		font-size: 50pt;
		text-align: center;
		margin-bottom: 0pt;
    	padding-bottom: 0pt;
	}

	h2 {
		margin-top: 0pt;
    	padding-top: 0pt;
		font-size: 20pt;
		text-align: center;
		position: relative;
		top: -10pt;
	}
</style>