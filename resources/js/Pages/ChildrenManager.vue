<template>
    <section class="container mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">Gyerekek kezelése</h1>
            <button @click="createChild()" class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400">
                Új gyerek
            </button>
        </header>

        <div class="rounded-3xl border border-neutral-800 bg-neutral-950 overflow-hidden">
            <div class="grid grid-cols-12 px-4 py-3 text-sm text-neutral-400 border-b border-neutral-800">
                <div class="col-span-4">Név</div>
                <div class="col-span-3">Létrehozva</div>
                <div class="col-span-5 text-right">Műveletek</div>
            </div>

            <div v-if="children.length === 0" class="px-4 py-6 text-neutral-300">Nincs gyerek felvéve.</div>

            <div v-for="c in children" :key="c.id"
                 class="grid grid-cols-12 px-4 py-3 border-b border-neutral-900 items-center">
                <div class="col-span-4 truncate">
                    <span class="font-semibold text-neutral-100">{{ c.name }}</span>
                </div>
                <div class="col-span-3 text-neutral-400">
                    {{ formatDate(c.created_at) }}
                </div>
                <div class="col-span-5">
                    <div class="flex justify-end gap-2 flex-wrap">
                        <button
                            @click="gotoStatistics(c.id)"
                            class="p-2 rounded-xl border text-amber-400 border-amber-400 hover:bg-neutral-900"
                            title="Statisztikák"
                        >
                            <span class="sr-only">Statisztikák</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path d="M5 21V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <path d="M12 21V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <path d="M19 21V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </button>
                        <button
                            @click="gotoEdit(c.id)"
                            class="p-2 rounded-xl border text-amber-400 border-amber-400 hover:bg-neutral-900"
                            title="Beállítások"
                        >
                            <span class="sr-only">Beállítások</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path
                                    d="M4 20L5.5 15.5L15 6L18.5 9.5L9 19L4 20Z"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M14 6L17 3L21 7L18 10"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                        <button
                            @click="copyLink(c.id)"
                            class="p-2 rounded-xl border border-amber-400 text-amber-400 hover:bg-neutral-900"
                            title="Link másolása"
                        >
                            <span class="sr-only">Link másolása</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path
                                    d="M12 3V15"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M8 7L12 3L16 7"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M6 11V17C6 18.1046 6.89543 19 8 19H16C17.1046 19 18 18.1046 18 17V11"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                        <button
                            @click="removeChild(c.id)"
                            class="p-2 rounded-xl border border-red-600 text-red-400 hover:bg-neutral-900"
                            title="Törlés"
                        >
                            <span class="sr-only">Törlés</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path
                                    d="M6 7H18"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M9 7V5C9 4.44772 9.44772 4 10 4H14C14.5523 4 15 4.44772 15 5V7"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M8 7L9 20H15L16 7"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import api from '../api'
import { formatDate } from '@/utils/dateFormatter'

export default {
    name: 'ChildrenManager',
    data() {
        return {
            children: [],
        }
    },
    mounted() {
        this.fetchChildren()
    },
    methods: {
        formatDate,
        async fetchChildren() {
            const {data} = await api.get('/api/children')
            this.children = data.children
        },
        gotoStatistics(id) {
            this.$router.push({name: 'statistics', params: {id}})
        },
        gotoEdit(id) {
            this.$router.push({name: 'child-edit', params: {id}})
        },
        createChild() {
            this.$router.push({name: 'child-new'})
        },
        async copyLink(id) {
            const {data} = await api.get(`/api/token/${id}`)
            const token = data.token
            if (token) {
                const url = `${window.location.origin}/?token=${encodeURIComponent(token)}`
                await navigator.clipboard.writeText(url)
            }
        },
        async removeChild(id) {
            await api.delete(`/api/child/${id}`)
            await this.fetchChildren()
        },
    },
}
</script>

<style scoped>
</style>
