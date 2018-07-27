<template>
	<v-dialog v-model="mostrarInteracciones" fullscreen hide-overlay transiton="dialog-bottom-transition">
		<v-card tile>
			<v-toolbar fixed card dark color="primary">
			<v-btn icon dark @click.native="mostrarInteracciones=false">
				<v-icon>close</v-icon>
			</v-btn>
				<v-toolbar-title>
					<div class="titleContainer">
						<div>Detalles</div>
						<v-progress-circular v-if="loading" class="loading" indeterminate :size="30" color="white"></v-progress-circular>
					</div>
			</v-toolbar-title>
			</v-toolbar>
			<div class="dialogContent">
				<div v-if="info && info.isNode">
					<div v-if="type == 'e'">
						<iframe ref="iframe" v-if="mostrarInteracciones" :src="iframeUrl"></iframe>
					</div>
					<div v-if="type == 'a'">
						<h2>Actividad</h2>
					</div>
					<div v-if="type == 'm'">
						<h2>Recurso</h2>
					</div>
					<div v-if="type == 'd'">
						<h2>Docente</h2>
					</div>
				</div>
				<div v-if="info && !info.isNode">
					<interaction v-for="detailedEdge in interactions" :interaction="detailedEdge" :index="index"></interaction>
				</div>	
			</div class="dialogContent">
		</v-card>
	</v-dialog>

</template>

<script>
	
	import Interaction from './Interactions.vue'

	export default {
	    props: ['info', 'index', 'curso'],
	    components: {
			interaction: Interaction
		},
	    data() {
	    	return {
	    		mostrarInteracciones: false,
	    		loading: undefined,
	    		timer: undefined,
	    		firstChange: false
	    	}
	    },
	    computed: {
	    	interactions() {
	    		return this.index.combined_edges_index[this.info.id].interactions
	    	},
	    	type() {
	    		return this.index.nodes[this.info.id].tipo
	    	},
	    	iframeUrl() {
	    		return `http://diia.edu.uy/alumno/${this.info.id}/cursos/${this.curso}`
	    	}
	    },
	    watch: {
				'$props':{
					handler: function (newVal, oldVal) { 
						if (this.firstChange) {
							this.mostrarInteracciones = true
						} else {
							this.firstChange = true
						}
					},
					deep: true
				},
				mostrarInteracciones(newValue) {
					if (this.info && this.info.isNode && this.type=='e') {
						if (newValue) {
					    	this.loading = true
					    	var thisVue = this
					    	this.timer = setTimeout(
					    		function hola() {
					    			thisVue.loading = false
					    			// thisVue.$refs.iframe.style.height
					    		}, 
					    		6000
					    	)
						} else {
							if (this.timer) {
								clearTimeout(this.timer)
							}
						}
					}
				}
			},
	}
</script>

<style scoped>
	
	img.prototype {
    	width: 97%;
	}

	.dialogContent {
		margin-top: 35pt
	}

	.loading {
	    height: 30px;
	    width: 30px;
	    position: absolute;
	    right: 20pt;
        top: 6pt;
	}

	iframe {
		border-width: 0pt;
		width: 100%;
		height: 100%;
		height: 1200pt;
	}

</style>


