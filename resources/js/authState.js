import {reactive} from 'vue'

export const authState = reactive({
    loggedIn: !!localStorage.getItem('user') && !!localStorage.getItem('auth_token')
})

export function setLoggedIn(v) {
    authState.loggedIn = v
}
