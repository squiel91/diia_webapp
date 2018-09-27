<template>
    <header>
        <img class="small-logo" src="img/white.png">
        <div class="right">
            <v-select
            z-index="500"
            style="width: 320pt; display: inline-block; position: relative; top: 0pt;"
            color="gray"
            :items="courses"
            item-text="titulo"
            item-value="id_curso"
            v-model="course"
            label="Curso"
            hide-details
            >
            </v-select>
            <v-chip close class="inline"
            style="position: relative; top: -2pt;"
            @input="logOutConfirmation = true">
                ¡Bienvenid@, {{ name }} {{ surname }}!
            </v-chip>
        </div>
        <v-dialog
         v-model="logOutConfirmation"
         max-width="290"
        >
            <v-card>
                <!-- <v-card-title class="headline">Seguro que quieres salir?</v-card-title> -->
                <v-card-actions>
                    <v-btn
                     color="primary"
                     @click="logOutConfirmation = false"
                    >
                        CANCELAR
                    </v-btn>
                    <v-btn
                     color="primary"
                     flat="flat"
                     @click="signOut()"
                    >
                        CERRAR SESIÓN
                  </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </header>
</template>

<script>
    export default {
        props: ['courses'],
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
        watch: {
            course() {
                this.emit()
            }
        },
        methods: {
            signOut() {
                this.$store.commit('signOut')
                this.$router.push('/')

            },
            emit() {
                this.$emit('change', this.course)
            }
        },
        mounted() {
            if (this.courses && this.courses[0]) {
                this.course = this.courses[0].id_curso 
            }
            this.emit()
        }
    }
</script>

<style>
    img.small-logo {
        width: 52pt;
        position: absolute;
        top: -3pt;
        left: 20pt;
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

    .right {

        position: absolute;
        top: 10pt;
        right: 10pt;
    }

    .selectCourse {
        width: 200pt;
    }

    .v-chip {
        position: relative;
        top: 2pt;
    }
</style>
