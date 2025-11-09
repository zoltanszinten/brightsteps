<template>
    <section class="container mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">
                {{ isEdit ? 'Gyerek szerkesztése' : 'Új gyerek' }}
            </h1>
            <div class="flex items-center gap-2">
                <button type="button" @click="cancel"
                        class="px-3 py-2 rounded-xl border border-neutral-700 text-neutral-300">Mégse
                </button>
                <button type="button" @click="save" :disabled="saving"
                        class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400">
                    {{ saving ? 'Mentés...' : 'Mentés' }}
                </button>
            </div>
        </header>

        <div class="grid md:grid-cols-2 gap-6">
            <form class="space-y-6 rounded-3xl p-6 shadow-sm border bg-neutral-900 border-neutral-800" @submit.prevent>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-amber-400">Név</label>
                    <input v-model.trim="form.name" type="text" required
                           class="w-full rounded-xl px-3 py-3 border border-neutral-700 bg-neutral-950 text-neutral-100"/>
                </div>

                <section>
                    <h2 class="text-sm font-semibold text-neutral-300 mb-3">Tipográfia</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="font">Betűméret
                                (px)</label>
                            <input id="font" type="range" min="14" max="32" step="1" v-model.number="state.font_size"
                                   class="w-full"/>
                            <p class="text-sm text-neutral-300 mt-1">{{ state.font_size }} px</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="ls">Betűköz (em)</label>
                            <input id="ls" type="range" min="0" max="0.2" step="0.01"
                                   v-model.number="state.letter_spacing" class="w-full"/>
                            <p class="text-sm text-neutral-300 mt-1">{{ state.letter_spacing.toFixed(2) }} em</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="lh">Sorköz</label>
                            <input id="lh" type="range" min="1.2" max="2.0" step="0.05"
                                   v-model.number="state.line_height" class="w-full"/>
                            <p class="text-sm text-neutral-300 mt-1">{{ state.line_height.toFixed(2) }}</p>
                        </div>
                    </div>
                </section>

                <div class="border-t border-neutral-800"></div>

                <section>
                    <h2 class="text-sm font-semibold text-neutral-300 mb-3">Színek és elrendezés</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400"
                                   for="palette">Színpaletta</label>
                            <select id="palette" v-model.number="state.color_palette_id"
                                    class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950">
                                <option v-for="p in palettes" :key="p.id" :value="p.id">{{ p.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="w">Kijelző szélesség
                                (px)</label>
                            <input id="w" type="number" min="640" max="4096" step="10" v-model.number="state.width"
                                   class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950"/>
                        </div>
                    </div>
                </section>

                <div class="border-t border-neutral-800"></div>

                <section>
                    <h2 class="text-sm font-semibold text-neutral-300 mb-3">Modulok</h2>
                    <div class="grid sm:grid-cols-3 gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="state.show_card_game" class="h-5 w-5"/>
                            <span>Kártya játék</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="state.show_recognition_game" class="h-5 w-5"/>
                            <span>Képfelismerés</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="state.show_map_game" class="h-5 w-5"/>
                            <span>Térkép</span>
                        </label>
                    </div>
                </section>
            </form>

            <div class="rounded-3xl p-6 border bg-neutral-950 border-neutral-800">
                <h2 class="text-xl font-bold mb-4 text-amber-400">Előnézet</h2>
                <div :style="previewContainerStyle" class="rounded-2xl p-4 border border-neutral-800">
                    <div :style="previewStyle">
                        <h3 class="text-lg font-semibold mb-2">Minta szöveg</h3>
                        <p class="mb-3">
                            Lorem Ipsum "Neque porro quisquam est..." What is Lorem Ipsum? Lorem Ipsum is simply dummy
                            text of the printing and typesetting industry.
                        </p>
                        <div class="flex gap-3">
                            <button
                                class="px-4 py-3 rounded-2xl border border-amber-400 text-amber-400 hover:bg-slate-900">
                                Példa gomb
                            </button>
                            <button
                                class="px-4 py-3 rounded-2xl border border-neutral-700 text-neutral-200 hover:bg-slate-900">
                                Másik gomb
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from 'axios'

export default {
    name: 'ChildForm',
    data() {
        return {
            id: this.$route.params.id || null,
            isEdit: !!this.$route.params.id,
            saving: false,
            form: {name: ''},
            palettes: [
                {id: 1, name: 'Alap'},
                {id: 2, name: 'Extra kontraszt'},
                {id: 3, name: 'Lágy'},
            ],
            state: {
                width: 1280,
                font_size: 18,
                letter_spacing: 4,
                line_height: 160,
                color_palette_id: 1,
                show_card_game: true,
                show_recognition_game: true,
                show_map_game: true,
            },
        }
    },
    created() {
        if (this.isEdit) {
            this.fetchChild()
        }
    },
    computed: {
        previewContainerStyle() {
            return {width: `min(100%, ${this.state.width}px)`}
        },
        previewStyle() {
            return {
                letterSpacing: `${this.state.letter_spacing / 100}em`,
                lineHeight: String(this.state.line_height / 100),
                fontSize: this.state.font_size + 'px',
                minHeight: '360px',
                padding: '1rem',
                borderRadius: '1rem',
            }
        },
    },
    methods: {
        async fetchChild() {
            const {data} = await axios.get(`/api/child/${this.id}`)
            this.form.name = data.name
            if (data.settings) {
                this.state = Object.assign({}, this.state, data.settings)
            }
        },
        async save() {
            this.saving = true
            const payload = {name: this.form.name, settings: {...this.state}}
            if (this.isEdit) {
                await axios.put(`/api/child/${this.id}`, payload)
            } else {
                const {data} = await axios.post(`/api/child`, payload)
                this.id = data.id
            }
            this.saving = false
            this.$router.push({name: 'children'})
        },
        cancel() {
            this.$router.push({name: 'children'})
        },
    },
}
</script>

<style scoped>
</style>
