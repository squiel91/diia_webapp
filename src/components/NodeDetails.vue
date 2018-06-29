<template>
	<v-dialog v-model="mostrarInteracciones" fullscreen hide-overlay transiton="dialog-bottom-transition" scrollable>
		<v-card tile>
			<v-toolbar card dark color="primary">
			<v-btn icon dark @click.native="mostrarInteracciones=false">
				<v-icon>close</v-icon>
			</v-btn>
				<v-toolbar-title>Detalles</v-toolbar-title>
			</v-toolbar>
			<ul>
				<div v-if="info && info.isNode">
			        <h1 style="text-align: center;">Detalles del nodo {{ info.id }}.</h1>  
				</div>
				<div v-if="info && !info.isNode">
					<interaction v-for="detailedEdge in interactions" :interaction="detailedEdge" :index="index"></interaction>
				</div>	
				
			</ul>
		</v-card>
	</v-dialog>

</template>

<script>
	
	import Interaction from './Interactions.vue'

	export default {
	    props: ['info', 'index'],
	    components: {
			interaction: Interaction
		},
	    data() {
	    	return {
	    		mostrarInteracciones: false
	    	}
	    },
	    computed: {
	    	interactions() {
	    		return this.index.combined_edges_index[this.info.id].interactions
	    	}
	    },
	    watch: {
				'$props':{
					handler: function (newVal, oldVal) { 
						this.mostrarInteracciones = true
					},
					deep: true
				}
			},
	}
</script>



