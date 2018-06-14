import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
        email: undefined,
        pass: undefined,
        role: undefined,
        id_docente: undefined
    },
    mutations: {
        authenticate: (context, params) => {
            context.email = params.email
            context.pass = params.pass
            context.role = params.role
            context.id_docente = params.id_docente
        },
        signOut(state) {
            state.email = undefined
            state.pass = undefined
            state.role = undefined
            state.id_docente = undefined
        }
    },
    getters: {
        isAuthenticated(state) {
            return state.email != undefined
        },
        sign(state) {
            return {
                email: state.email,
                pass: state.pass
            }
        },
        docente(state) {
            return state.id_docente
        }
    }
})