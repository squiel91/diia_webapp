<template>
	<div class="conditionChain">
		<div class="conditionsContainer" ref="conditionsContainer">
		</div>
		<img @click="addCondition()" class="funnel" src="img/funnel.png" v-if="recordsQty == 0"></img>
		<v-btn @click="addCondition()" style="margin-left: 35pt;">+ NUEVA CONDICIÓN</v-btn>
	</div>
</template>

<script>
	import Condition from './Condition.vue'
	import Vue from 'vue'

	let ConditionClass = Vue.extend(Condition)

	export default {
		props: ['entity'],
		data() {
			return {
				records: [],
				recordsQty: 0
			}
		},
		methods: {
			reset() {
				this.records = []
				this.recordsQty = 0
				this.$refs.conditionsContainer.innerHTML = ""

			},
			emit() {
				var conditionJSON = []
				for (var record of this.records)
					if (record) {
						if (record.conditionChange == null) return
						conditionJSON.push(record.conditionChange)
					}
				this.$emit('change', conditionJSON)
			},
			addCondition() {
				this.recordsQty += 1
				let conditionInstance = new ConditionClass({
			    	propsData: { entity: this.entity }
				})

				conditionInstance.$mount()
				this.$refs.conditionsContainer.appendChild(conditionInstance.$el) 

				var record = {
					condition: conditionInstance,
					conditionChange: null,
					index: this.records.length
				}
				
				var parent = this

				conditionInstance.$on('change', event => {
					record.conditionChange = event
					this.emit()
				})

				conditionInstance.$on('destroy', event => {
 					parent.$refs.conditionsContainer.removeChild(record.condition.$el);
					record.condition.$destroy()
					parent.records[record.index] = null
					this.recordsQty -= 1
					this.emit()
				})

				this.records.push(record)
			}
		}
	}
</script>

<style scoped>
	img.funnel {
	    position: absolute;
		width: 18pt;
	    margin-left: 9pt;
	    margin-top: 9pt;
	}
</style>

