<template>
	<div class="connector">
		<v-select
		 v-model="connector"
		 label="Conector"
		 :items="[{title:'Y', name: 'AND'}, {title: 'O', name: 'OR'}]"
		 item-text="title"
		 item-value="name"
		 style="width: 90pt; margin-left: 45pt"
		 solo
		 class="mb-1"
		 hide-details
		/>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				connector: 'AND'
			}
		},
		watch: {
			connector(newValue) {
				this.$emit('change', newValue)
			}
		}
	}
</script>

<style scoped>

</style>