<template>
    <footer class="border-t border-neutral-800 bg-neutral-950">
        <div class="container mx-auto px-3 py-3 text-sm text-neutral-300 flex items-center justify-between gap-3">
            <p>© {{ year }} BrightSteps</p>
            <div class="flex items-center gap-4">
                <button v-if="isLoggedIn"
                        class="px-4 py-3 rounded-2xl border text-amber-400 border-amber-400 hover:bg-slate-900"
                        @click="logout">
                    Kijelentkezés
                </button>
            </div>
        </div>
    </footer>
</template>

<script>
import api, {tokenKey, userKey} from '../api'

export default {
    name: 'AppFooter',
    data() {
        return {
            year: new Date().getFullYear(),
            loading: false,
            isLoggedIn: false,
        }
    },
    mounted() {
        this.isLoggedIn = !!localStorage.getItem(userKey) || !!localStorage.getItem(tokenKey)
    },
    methods: {
        clearAuthState() {
            localStorage.removeItem(tokenKey)
            localStorage.removeItem(userKey)
        },
        async logout() {
            try {
                await api.post('/api/logout', {}, {headers: {Accept: 'application/json'}})
            } catch (_) {
                this.clearAuthState()
            } finally {
                this.clearAuthState()
                this.loading = false
                window.location.href = '/'
            }
        },
    },
}
</script>
