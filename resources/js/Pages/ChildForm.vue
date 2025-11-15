<template>
    <section class="container mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">
                {{ isEdit ? 'Gyerek szerkesztése' : 'Új gyerek' }}
            </h1>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    @click="cancel"
                    class="px-3 py-2 rounded-xl border border-neutral-700 text-neutral-300 hover:bg-neutral-900"
                >
                    Mégse
                </button>
                <button
                    type="button"
                    @click="save"
                    :disabled="saving"
                    class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400 hover:bg-neutral-900"
                >
                    Mentés
                </button>
            </div>
        </header>

        <div class="grid md:grid-cols-2 gap-6">
            <form class="space-y-6 rounded-3xl p-6 shadow-sm border bg-neutral-900 border-neutral-800" @submit.prevent>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-amber-400">Név</label>
                    <input
                        v-model.trim="form.name"
                        type="text"
                        required
                        class="w-full rounded-xl px-3 py-3 border border-neutral-700 bg-neutral-950 text-neutral-100"
                    />
                </div>

                <section>
                    <h2 class="text-sm font-semibold text-neutral-300 mb-3">Tipográfia</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="font">Betűméret (px)</label>
                            <input
                                id="font"
                                type="range"
                                min="18"
                                max="64"
                                step="2"
                                v-model.number="state.font_size"
                                class="w-full"
                            />
                            <p class="text-sm text-neutral-300 mt-1">{{ state.font_size }} px</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="ls">Betűköz (em)</label>
                            <input
                                id="ls"
                                type="range"
                                min="0"
                                max="40"
                                step="1"
                                v-model.number="state.letter_spacing"
                                class="w-full"
                            />
                            <p class="text-sm text-neutral-300 mt-1">
                                {{ (state.letter_spacing / 100).toFixed(2) }} em
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="lh">Sorköz</label>
                            <input
                                id="lh"
                                type="range"
                                min="100"
                                max="300"
                                step="10"
                                v-model.number="state.line_height"
                                class="w-full"
                            />
                            <p class="text-sm text-neutral-300 mt-1">
                                {{ (state.line_height / 100).toFixed(2) }}
                            </p>
                        </div>
                    </div>
                </section>

                <div class="border-t border-neutral-800"></div>

                <section>
                    <h2 class="text-sm font-semibold text-neutral-300 mb-3">Színek és elrendezés</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="palette">Színpaletta</label>
                            <select
                                id="palette"
                                v-model.number="state.color_palette_id"
                                class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950 text-neutral-100"
                            >
                                <option v-for="p in palettes" :key="p.id" :value="p.id">
                                    {{ p.name }}
                                </option>
                            </select>
                            <p v-if="selectedPalette" class="mt-2 text-xs text-neutral-400">
                                Kulcs: {{ selectedPalette.value }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="w">
                                Kijelző szélesség (px)
                            </label>
                            <input
                                id="w"
                                type="number"
                                min="640"
                                max="4096"
                                step="10"
                                v-model.number="state.width"
                                class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950 text-neutral-100"
                            />
                        </div>
                    </div>
                </section>

                <div class="border-t border-neutral-800"></div>

                <section>
                    <h2 class="text-sm font-semibold text-neutral-300 mb-3">Modulok</h2>
                    <div class="grid sm:grid-cols-3 gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="state.show_card_game" class="h-5 w-5" />
                            <span>Kártya játék</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="state.show_recognition_game" class="h-5 w-5" />
                            <span>Képfelismerés</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="state.show_map_game" class="h-5 w-5" />
                            <span>Térkép</span>
                        </label>
                    </div>
                </section>
            </form>

            <div class="rounded-3xl p-6 border bg-neutral-950 border-neutral-800">
                <h2 class="text-xl font-bold mb-4 text-amber-400">Előnézet</h2>
                <div
                    :style="previewContainerStyle"
                    class="rounded-2xl p-4 border border-neutral-800 transition-colors duration-150"
                >
                    <div
                        :style="previewStyle"
                        class="h-full w-full flex flex-col gap-4 rounded-2xl transition-colors duration-150"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex flex-col">
                                <span class="text-xs uppercase tracking-wide opacity-80" :style="mutedTextStyle">
                                    Paletta
                                </span>
                                <span class="text-sm font-semibold">
                                    {{ selectedPalette ? selectedPalette.name : 'Nincs kiválasztva' }}
                                </span>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold" :style="badgeStyle">
                                Minta jelvény
                            </span>
                        </div>

                        <div
                            class="rounded-2xl p-4 border text-sm flex flex-col gap-2 border-neutral-700 bg-neutral-900"
                            :style="[cardStyle, textBlockStyle]"
                        >
                            <h3 class="text-lg font-semibold mb-1">Minta szöveg</h3>
                            <p class="mb-2">
                                Lorem Ipsum "Neque porro quisquam est..." What is Lorem Ipsum? Lorem Ipsum is simply
                                dummy text of the printing and typesetting industry.
                            </p>
                            <p class="mb-2" :style="mutedTextStyle">
                                Ez egy halványabb szöveg, hogy lásd a másodlagos színt.
                            </p>
                            <div class="flex flex-wrap gap-3 mt-3">
                                <button
                                    class="px-4 py-3 rounded-2xl border text-sm font-semibold border-amber-400 text-amber-400 hover:bg-slate-900"
                                    :style="primaryButtonStyle"
                                >
                                    Elsődleges gomb
                                </button>
                                <button
                                    class="px-4 py-3 rounded-2xl border text-sm border-neutral-700 text-neutral-200 hover:bg-slate-900"
                                    :style="secondaryButtonStyle"
                                >
                                    Másodlagos gomb
                                </button>
                                <button
                                    class="px-3 py-2 rounded-xl border text-xs border-neutral-700 text-neutral-200"
                                    :style="chipStyle"
                                >
                                    Kis chip
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3 text-xs">
                            <div
                                class="rounded-xl p-3 border flex flex-col gap-1 border-neutral-700 bg-neutral-900"
                                :style="smallCardStyle"
                            >
                                <span class="font-semibold">Kártya játék</span>
                                <span :style="mutedTextStyle">
                                    Címkék, ikonok, rövid szöveg.
                                </span>
                            </div>
                            <div
                                class="rounded-xl p-3 border flex flex-col gap-1 border-neutral-700 bg-neutral-900"
                                :style="smallCardStyle"
                            >
                                <span class="font-semibold">Képfelismerés</span>
                                <span :style="mutedTextStyle">
                                    Minta elem a modulhoz.
                                </span>
                            </div>
                            <div
                                class="rounded-xl p-3 border flex flex-col gap-1 border-neutral-700 bg-neutral-900"
                                :style="smallCardStyle"
                            >
                                <span class="font-semibold">Térkép</span>
                                <span :style="mutedTextStyle">
                                    Térképes nézet előnézete.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import api from '../api'

export default {
    name: 'ChildForm',
    data() {
        return {
            id: this.$route.params.id || null,
            isEdit: !!this.$route.params.id,
            saving: false,
            form: { name: '' },
            palettes: [],
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
        this.fetchPalettes()
        if (this.isEdit) {
            this.fetchChild()
        }
    },
    computed: {
        selectedPalette() {
            const currentId = Number(this.state.color_palette_id)
            return this.palettes.find(p => Number(p.id) === currentId) || null
        },
        previewContainerStyle() {
            const style = {
                width: `min(100%, ${this.state.width}px)`,
            }
            if (this.selectedPalette) {
                style.backgroundColor = this.selectedPalette.background
                style.borderColor = this.selectedPalette.border
            }
            return style
        },
        previewStyle() {
            const style = {
                minHeight: '360px',
                padding: '1.25rem',
                borderRadius: '1rem',
            }
            if (this.selectedPalette) {
                style.backgroundColor = this.selectedPalette.surface
                style.borderColor = this.selectedPalette.border
                style.color = this.selectedPalette.text
            }
            return style
        },
        textBlockStyle() {
            return {
                letterSpacing: `${this.state.letter_spacing / 100}em`,
                lineHeight: this.state.line_height / 100,
                fontSize: this.state.font_size + 'px',
            }
        },
        mutedTextStyle() {
            if (!this.selectedPalette) {
                return {}
            }
            return { color: this.selectedPalette.text_muted }
        },
        badgeStyle() {
            if (!this.selectedPalette) {
                return {}
            }
            return {
                backgroundColor: this.selectedPalette.accent,
                color: this.selectedPalette.accent_text,
                borderColor: this.selectedPalette.accent,
                borderWidth: '1px',
                borderStyle: 'solid',
            }
        },
        cardStyle() {
            if (!this.selectedPalette) {
                return {}
            }
            return {
                backgroundColor: this.selectedPalette.surface_alt,
                borderColor: this.selectedPalette.border,
            }
        },
        primaryButtonStyle() {
            if (!this.selectedPalette) {
                return {}
            }
            return {
                backgroundColor: this.selectedPalette.accent,
                color: this.selectedPalette.accent_text,
                borderColor: this.selectedPalette.accent,
            }
        },
        secondaryButtonStyle() {
            if (!this.selectedPalette) {
                return {}
            }
            return {
                borderColor: this.selectedPalette.border,
                color: this.selectedPalette.text,
            }
        },
        chipStyle() {
            if (!this.selectedPalette) {
                return {}
            }
            return {
                borderColor: this.selectedPalette.accent,
                color: this.selectedPalette.accent,
            }
        },
        smallCardStyle() {
            if (!this.selectedPalette) {
                return {}
            }
            return {
                backgroundColor: this.selectedPalette.surface,
                borderColor: this.selectedPalette.border,
            }
        },
    },
    methods: {
        async fetchPalettes() {
            const { data } = await api.get('/api/color-palettes')
            this.palettes = data
            if (!this.state.color_palette_id && this.palettes.length) {
                this.state.color_palette_id = Number(this.palettes[0].id)
            }
        },
        async fetchChild() {
            const { data } = await api.get(`/api/child/${this.id}`)
            this.form.name = data.name
            if (data.setting) {
                this.state = Object.assign({}, this.state, data.setting)
            }
        },
        async save() {
            this.saving = true
            const payload = { name: this.form.name, settings: { ...this.state } }
            if (this.isEdit) {
                await api.put(`/api/child/${this.id}`, payload)
            } else {
                const { data } = await api.post(`/api/child`, payload)
                this.id = data.id
            }
            this.saving = false
            this.$router.push({ name: 'children' })
        },
        cancel() {
            this.$router.push({ name: 'children' })
        },
    },
}
</script>

<style scoped>
</style>
