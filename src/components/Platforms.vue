<template>
	<ul class="interactionFilter">
		<li @click="click('m')" :class="{selected: platforms.m}"><img src="img/moodle.png"/><span class="label">Moodle</span></li>
		<li @click="click('c')" :class="{selected: platforms.c}"><img src="img/crea.jpg"/><span class="label">CREA 2</span></li>
		<li @click="click('p')" :class="{selected: platforms.p}"><img src="img/pam.png"/><span class="label">PAM</span></li>
		<li @click="click('f')" :class="{selected: platforms.f}"><img src="img/facebook.png"/><span class="label">Facebook</span></li>
	</ul>
</template>

<script>
	export default {
		data() {
			return {
				platforms: {
					m: true,
					c: true,
					p: true,
					f: true
				}
			}
		},
		created() {
			this.emit()
		},
		methods: {
			emit() {
				this.$emit('change', this.platforms)
			},
			click(platform) {
				this.platforms[platform] = !this.platforms[platform]
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
		height: 30pt;
		border-radius: 5pt;
		color: gray;
		cursor: pointer;

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
	    padding-top: 2pt;
	    padding-left: 25pt;
	    display: block;
	}

	img {
		position: absolute;
		width: 20pt;
	}

</style>