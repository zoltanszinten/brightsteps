<template>
    <section class="container mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">Gyerekek kezelése</h1>
            <button @click="createChild()" class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400">Új gyerek
            </button>
        </header>

        <div class="rounded-3xl border border-neutral-800 bg-neutral-950 overflow-hidden">
            <div class="grid grid-cols-12 px-4 py-3 text-sm text-neutral-400 border-b border-neutral-800">
                <div class="col-span-4">Név</div>
                <div class="col-span-3">Létrehozva</div>
                <div class="col-span-2 text-right">Műveletek</div>
            </div>

            <div v-if="children.length === 0" class="px-4 py-6 text-neutral-300">Nincs gyerek felvéve.</div>

            <div v-for="c in children" :key="c.id"
                 class="grid grid-cols-12 px-4 py-3 border-b border-neutral-900 items-center">
                <div class="col-span-4 truncate">
                    <span class="font-semibold text-neutral-100">{{ c.name }}</span>
                </div>
                <div class="col-span-3 text-neutral-400">{{ c.created_at }}</div>
                <div class="col-span-2">
                    <div class="flex justify-end gap-2">
                        <button @click="gotoStatistics(c.id)"
                                class="px-3 py-2 rounded-xl border text-amber-400 border-amber-400">Statisztikák
                        </button>
                        <button @click="gotoSettings(c.id)"
                                class="px-3 py-2 rounded-xl border text-amber-400 border-amber-400">Beállítások
                        </button>
                        <button @click="copyLink(c.id)"
                                class="px-3 py-2 rounded-xl border border-red-600 text-red-400">Link
                        </button>
                        <button @click="removeChild(c.id)"
                                class="px-3 py-2 rounded-xl border border-red-600 text-red-400">Törlés
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <p id="live" class="sr-only" role="status" aria-live="polite"></p>
    </section>
</template>

<script>

export default {
    name: 'ChildrenManager',
    data() {
        return {
            children: []
        }
    },
    mounted() {

    },
    computed: {},
    methods: {
        gotoStatistics(id) {

        },
        gotoSettings(id) {

        },
        copyLink(id) {

        },
        removeChild(id) {

        },
        createChild(id) {

        },
    },
}
</script>
