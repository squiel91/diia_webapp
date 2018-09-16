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
		border-bottom: 2pt solid lightgray;
    	border-radius: 7pt;
		margin: 5pt;
		margin-top: 15pt;
		width: 45%;
	    display: inline-block;
	}

	h1 {
		font-size: 35pt;
		text-align: center;
		margin-bottom: 0pt;
    	padding-bottom: 0pt;
    	color: #8e44ad
	}

	h2 {
		margin-top: 0pt;
    	padding-top: 0pt;
		font-size: 12pt;
		text-align: center;
		position: relative;
		top: -10pt;
	}
</style>