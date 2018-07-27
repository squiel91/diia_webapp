import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
        email: undefined,
        pass: undefined,
        role: undefined,
        name: undefined,
        surname: undefined,
        id_docente: undefined,
        courses: []
    },
    mutations: {
        authenticate: (context, params) => {
            context.email = params.email
            context.pass = params.pass
            context.role = params.role
            context.name = params.name
            context.surname = params.surname
            context.id_docente = params.id_docente
            context.courses = params.courses
        },
        signOut(state) {
            state.email = undefined
            state.pass = undefined
            state.role = undefined
            state.name = undefined
            state.surname = undefined
            state.id_docente = undefined
            state.courses = []
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
        },
        courses(state) {
            return state.courses
        }
    }
})