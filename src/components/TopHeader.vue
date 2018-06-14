<template>
    <header>
        <span>{{ email }} ({{ role }})</span>
        <button @click="signOut()"><v-icon>power_settings_new</v-icon></button>
    </header>
</template>

<script>
    export default {
        data() {
            return {
                email: this.$store.state.email,
                role: this.$store.state.role
            }
        },
        methods: {
            signOut() {
                this.$store.commit('signOut')
                this.$router.push('/')

            }
        }
    }
</script>

<style>

</style>
