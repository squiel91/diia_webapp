<template>
	<div class="conditionChain">
		<div class="conditionsContainer" ref="conditionsContainer">
		</div>
		<v-btn @click="addCondition()" style="margin-left: 47pt;">+ Nueva condición</v-btn>
	</div>
</template>

<script>
	import Condition from './Condition.vue'
	import Connector from './Connector.vue'
	import Vue from 'vue'

	let ConditionClass = Vue.extend(Condition)
	let ConnectorClass = Vue.extend(Connector)

	export default {
		props: ['entity'],
		components: {
			connector: Connector
		},
		data() {
			return {
				conditionJSON: [],
				records: []
			}
		},
		methods: {
			reset() {
				this.conditionJSON = []
				this.records = []
				this.$refs.conditionsContainer.innerHTML = ""

			},
			emit() {
				var conditionJSON = []
				for (var record of this.records) {
					if (record) {
						if ((record.conditionChange == null) || record.connectorChange == null) {
							conditionJSON = null
							break
						}
						if (record.connector) conditionJSON.push(record.connectorChange)
						conditionJSON.push(record.conditionChange)
					}
				}
				if (conditionJSON == null) return
				this.conditionJSON = conditionJSON
				this.$emit('change', conditionJSON)
			},
			addCondition() {
				var isFirst = true
				for (var i = 0; i < this.records.length; i += 1) {
					if (this.records[i]) {
						isFirst = false
						break
					}
				}

				var connectorInstance = null

				if (!isFirst) {
					connectorInstance = new ConnectorClass()
					connectorInstance.$mount()
					this.$refs.conditionsContainer.appendChild(connectorInstance.$el)
				} 

				let conditionInstance = new ConditionClass({
			    	propsData: { entity: this.entity }
				})

				conditionInstance.$mount()
				this.$refs.conditionsContainer.appendChild(conditionInstance.$el) 

				var record = {
					condition: conditionInstance,
					connector: connectorInstance,
					conditionChange: null,
					connectorChange: 'AND',
					index: this.records.length,
					isFirst: isFirst
				}
				
				var parent = this

				if (connectorInstance) {
					connectorInstance.$on('change', event => {
						record.connectorChange = event
						this.emit()
					})
				}

				conditionInstance.$on('change', event => {
					console.log(event)
					record.conditionChange = event
					this.emit()
				})

				conditionInstance.$on('destroy', event => {
 					parent.$refs.conditionsContainer.removeChild(record.condition.$el);
					record.condition.$destroy()
					if (record.connector) {
						parent.$refs.conditionsContainer.removeChild(record.connector.$el);
						record.connector.$destroy()
					}
					if (record.isFirst) {
						for (var i = record.index + 1; i < parent.records.length; i += 1) {
							if (parent.records[i]) {
								parent.records[i].isFirst = true
								parent.$refs.conditionsContainer.removeChild(parent.records[i].connector.$el)
								parent.records[i].connector.$destroy()
								parent.records[i].connector = null
								break
							} 
						}
					}
					parent.records[record.index] = null
					this.emit()
				})

				this.records.push(record)
			}
		}
	}
</script>

<style scoped>
</style>

