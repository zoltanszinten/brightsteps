<template>
    <section class="container mx-auto px-4 py-12">
        <div class="mx-auto max-w-xl">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold tracking-widest text-amber-400">Bejelentkezés</h1>
            </div>

            <form class="space-y-5 rounded-3xl p-8 shadow-sm border bg-neutral-900 border-amber-400"
                  @submit.prevent="login">
                <div>
                    <label for="email"
                           class="block text-base font-semibold mb-2 tracking-wide text-amber-400">E-mail</label>
                    <input id="email" type="email" inputmode="email" v-model.trim="email"
                           class="w-full rounded-2xl px-4 py-4 border focus:outline-none focus:ring border-amber-400 text-slate-100 focus:ring-slate-600"
                           style="min-height:56px" autocomplete="email"/>
                </div>

                <div>
                    <label for="password"
                           class="block text-base font-semibold mb-2 tracking-wide text-amber-400">Jelszó</label>
                    <div class="relative">
                        <input type="password" id="password" v-model="password"
                               class="w-full rounded-2xl px-4 py-4 border focus:outline-none focus:ring border-amber-400 text-slate-100 focus:ring-slate-600 pr-12"
                               style="min-height:56px" autocomplete="current-password"/>
                    </div>
                </div>

                <button type="submit"
                        class="w-full rounded-2xl px-4 py-4 font-bold tracking-wider border hover:bg-slate-900 border-amber-400 text-amber-400 disabled:opacity-50"
                        style="min-height:56px">
                    Belépés
                </button>

                <p v-if="msg" class="text-center text-sm mt-4 text-amber-400">
                    {{ msg }}
                </p>
            </form>
        </div>
    </section>
</template>

<script>
import api, {tokenKey, userKey} from '../api'

export default {
    name: 'LoginForm',
    data() {
        return {
            email: '',
            password: '',
            msg: '',
        }
    },
    mounted() {

    },
    methods: {
        async login() {
            this.msg = ''

            try {
                const {data: login} = await api.post(
                    '/api/login',
                    {email: this.email, password: this.password},
                    {headers: {Accept: 'application/json'}},
                )

                if (login?.token) {
                    localStorage.setItem(tokenKey, login?.token)
                }

                const {data: user} = await api.get('/api/user', {headers: {Accept: 'application/json'}})

                if (user?.permissions) {
                    localStorage.setItem(userKey, JSON.stringify(user))
                }

                window.location.reload()
            } catch (err) {
                this.msg = 'Hiba a belépésnél'
            }
        },
    },
}
</script>
