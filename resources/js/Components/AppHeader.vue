<template>
    <header class="border-b border-neutral-800 bg-neutral-950/90 backdrop-blur">
        <div class="container mx-auto px-3 py-2 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xl font-extrabold tracking-wider">BrightSteps</span>
            </div>
            <div class="flex items-center gap-4">
                <button v-if="isLoggedIn"
                        class="px-4 py-3 rounded-2xl border text-amber-400 border-amber-400 hover:bg-neutral-900"
                        @click="logout">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <path d="M16 17l5-5-5-5"/>
                        <path d="M21 12H9"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>
</template>

<script>
import api, {tokenKey, userKey} from '@/api'
import {authState} from "@/authState.js";

export default {
    name: 'AppHeader',
    data() {
        return {}
    },
    mounted() {

    },
    computed: {
        isLoggedIn() {
            return authState.loggedIn
        },
        displayName() {
            if (!this.isLoggedIn) return ''
            try {
                const user = JSON.parse(localStorage.getItem(userKey))
                return user?.user?.name
            } catch {
                return ''
            }
        },
    },
    methods: {
        clearAuthState() {
            localStorage.removeItem(tokenKey)
            localStorage.removeItem(userKey)
        },
        async logout() {
            await api.post('/api/logout', {}, {headers: {Accept: 'application/json'}})
            this.clearAuthState()
            window.location.href = '/'
        },
    },
}
</script>
