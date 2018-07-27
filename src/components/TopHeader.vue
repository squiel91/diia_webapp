<template>
    <header>
        <img class="small-logo" src="img/white.png">
        <v-chip close class="inline floatLeft"
        @input="logOutConfirmation = true">
            Bienvenid@ {{ name }} {{ surname }}!
        </v-chip>
            <v-dialog
              v-model="logOutConfirmation"
              max-width="290"
            >
              <v-card>
                <v-card-title class="headline">Seguro que quieres salir?</v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>

                  <v-btn
                    color="primary"
                    @click="logOutConfirmation = false"
                  >
                    Cancelar
                  </v-btn>
                  <v-btn
                    color="primary"
                    flat="flat"
                    @click="signOut()"
                  >
                    Cerrar sesion
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
    </header>
</template>

<script>
    export default {
        data() {
            return {
                email: this.$store.state.email,
                role: this.$store.state.role,
                name: this.$store.state.name,
                surname: this.$store.state.surname,
                course: undefined,
                logOutConfirmation: false
            }
        },
        props: ['courses'],
        methods: {
            signOut() {
                this.$store.commit('signOut')
                this.$router.push('/')

            }
        }
    }
</script>

<style>
    img.small-logo {
        width: 100pt;
    }

    .inline {
        display: inline-block;
    }

    header {
        width: 100%
    }

    button {
        margin-left: 5pt;
    }

    .floatLeft {
        float:right
    }

    .selectCourse {
        width: 200pt;
    }

    .v-chip {
        position: relative;
        top: 2pt;
    }
</style>
