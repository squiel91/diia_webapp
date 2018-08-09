import Vue from 'vue'
import App from './App.vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.css'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'

import { store } from './store/Store'

Vue.use(Vuetify, {
	theme: {
		primary: '#8e44ad',
		secondary: '#b0bec5',
		error: '#e74c3c'
	}
})
Vue.use(VueResource)

if (window.location.href.indexOf('localhost') >= 0) {
  	console.log('Developer Mode');
	Vue.http.options.root = 'http://localhost:80/'
}

import Login from './components/Login.vue'
import Home from './components/Home.vue'
import Consentimiento from './components/Consentimiento.vue' 

Vue.use(VueRouter)

const routes = [
	{path: '/', component: Login},
	{path: '/home', component: Home},
	{path: '/consentimiento', component: Consentimiento}
] 
const router = new VueRouter({
	routes
	// mode: 'history'
})

new Vue({
	el: '#app',
	store,
	router,
	render: h => h(App)
})
