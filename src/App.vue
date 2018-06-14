<template>
  <v-app id="inspire">
    <router-view></router-view>
  </v-app>
</template>

<script>
    import Login from './components/Login.vue' 
    import Home from './components/Home.vue' 
    import Consentimiento from './components/Consentimiento.vue' 

    export default {
        computed: {
            isAuthenticated() {
                return this.$store.getters.isAuthenticated
            }
        },
        components: {
            login: Login,
            home: Home,
            consentimiento: Consentimiento
        }
    }
</script>