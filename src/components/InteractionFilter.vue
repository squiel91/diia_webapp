<template>
	<ul class="interactionFilter">
		<li @click="click('publicacion')" :class="{selected: interactionTypes.publicacion}"><v-icon>import_contacts</v-icon><span class="label">Publicacion</span></li>
		<li @click="click('comentario')" :class="{selected: interactionTypes.comentario}"><v-icon>message</v-icon><span class="label">Comentario</span></li>
		<li @click="click('visto')" :class="{selected: interactionTypes.visto}"><v-icon>done</v-icon><span class="label">Visto</span></li>
		<li @click="click('mensaje')" :class="{selected: interactionTypes.mensaje}"><v-icon>forum</v-icon><span class="label">Mensaje</span></li>
		<li @click="click('reaccion')" :class="{selected: interactionTypes.reaccion}"><v-icon>thumbs_up_down</v-icon><span class="label">Reaccion</span></li>
		<li @click="click('mencion')" :class="{selected: interactionTypes.mencion}"><v-icon>person_add</v-icon><span class="label">Mencion</span></li>
	</ul>
</template>

<script>
	export default {
		data() {
			return {
				interactionTypes: {
					publicacion: true,
					comentario: true,
					mensaje: true,
					visto: true,
					reaccion: true,
					mensaje: true,
					mencion: true
				}
			}
		},
		created() {
			this.emit()
		},
		methods: {
			emit() {
				this.$emit('change', this.interactionTypes)
			},
			click(interactionType) {
				this.interactionTypes[interactionType] = !this.interactionTypes[interactionType]
				this.emit()
			}
		}
	}
</script>

<style scoped>

	ul.interactionFilter {
		list-style-type: none;
		margin-top: 15pt;
		margin-bottom: 20pt;
		padding: 0pt;
	}

	li {
		display: inline-block;
		border: 2pt solid transparent;
		margin: 3pt;
		padding: 3pt 5pt;
		width: 110pt;
		border-radius: 5pt;
		color: gray;
	}

	.selected {
		border: 2pt solid #8e44ad;
		color: #8e44ad;
	}

	ul li i.v-icon {
		margin-right: 7pt;
		color: inherit;
	}

	ul li.selected i.v-icon {
		margin-right: 7pt;
		color: #8e44ad;
	}

	span.label {
		position: relative;
		bottom: 2pt;
	}

</style>