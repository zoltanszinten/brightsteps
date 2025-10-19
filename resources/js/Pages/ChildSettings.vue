<template>
    <section class="container mx-auto px-4 py-8">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Beállítások -->
            <form class="space-y-5 rounded-3xl p-6 shadow-sm border bg-neutral-900 border-amber-400" @submit.prevent>
                <header class="flex items-center justify-between">
                    <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">Gyerek beállítások</h1>
                    <div class="flex items-center gap-2">
                        <button type="button" class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400" @click="resetToDefaults">Alaphelyzet</button>
                        <button type="button" class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400" @click="saveToLocal">Link generálása</button>
                    </div>
                </header>

                <fieldset class="grid sm:grid-cols-2 gap-4" aria-labelledby="appearance">
                    <legend id="appearance" class="sr-only">Megjelenés</legend>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-amber-400" for="font">Betűméret (px)</label>
                        <input id="font" type="range" min="14" max="32" step="1" v-model.number="state.font_size" class="w-full" aria-label="Betűméret" />
                        <p class="text-sm text-neutral-300 mt-1">{{ state.font_size }} px</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-amber-400" for="palette">Színpaletta</label>
                        <select id="palette" v-model="state.color_palette" class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950" aria-label="Színpaletta">
                            <option v-for="p in palettes" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-amber-400" for="ls">Betűköz (em)</label>
                        <input id="ls" type="range" min="0" max="0.2" step="0.01" v-model.number="state.letter_spacing" class="w-full" aria-label="Betűköz" />
                        <p class="text-sm text-neutral-300 mt-1">{{ state.letter_spacing.toFixed(2) }} em</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-amber-400" for="lh">Sorköz</label>
                        <input id="lh" type="range" min="1.2" max="2.0" step="0.05" v-model.number="state.line_height" class="w-full" aria-label="Sorköz" />
                        <p class="text-sm text-neutral-300 mt-1">{{ state.line_height.toFixed(2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-amber-400" for="w">Kijelző szélesség (px)</label>
                        <input id="w" type="number" min="640" max="4096" step="10" v-model.number="state.width" class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950" aria-label="Kijelző szélesség" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-amber-400" for="h">Kijelző magasság (px)</label>
                        <input id="h" type="number" min="480" max="4096" step="10" v-model.number="state.height" class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950" aria-label="Kijelző magasság" />
                    </div>
                </fieldset>

                <fieldset class="grid sm:grid-cols-3 gap-4" aria-labelledby="modules">
                    <legend id="modules" class="sr-only">Modulok</legend>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" v-model="state.show_card_game" class="h-5 w-5" aria-label="Kártya játék" />
                        <span>Kártya játék</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" v-model="state.show_recognition_game" class="h-5 w-5" aria-label="Képfelismerés" />
                        <span>Képfelismerés</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" v-model="state.show_map_game" class="h-5 w-5" aria-label="Térkép" />
                        <span>Térkép</span>
                    </label>
                </fieldset>

                <fieldset class="grid sm:grid-cols-2 gap-4" aria-labelledby="a11y">
                    <legend id="a11y" class="sr-only">Akadálymentesítés</legend>
                    <label class="flex items-center justify-between gap-3 p-3 rounded-xl border border-neutral-700">
                        <span>Csökkentett mozgás</span>
                        <input type="checkbox" v-model="state.reduced_motion" class="h-5 w-5" aria-label="Csökkentett mozgás" />
                    </label>
                    <label class="flex items-center justify-between gap-3 p-3 rounded-xl border border-neutral-700">
                        <span>Erős fókuszkeret</span>
                        <input type="checkbox" v-model="state.strong_focus_outline" class="h-5 w-5" aria-label="Erős fókuszkeret" />
                    </label>
                </fieldset>
            </form>

            <div class="rounded-3xl p-6 border bg-neutral-950 border-neutral-800">
                <h2 class="text-xl font-bold mb-4 text-amber-400">Előnézet</h2>
                <div :style="previewStyle" class="rounded-2xl p-4 border border-neutral-800" :class="previewClass">
                    <p class="mb-3">Lorem Ipsum
                        "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."
                        "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain..."
                        What is Lorem Ipsum?
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                    <button
                        class="px-4 py-3 rounded-2xl border border-amber-400 text-amber-400 hover:bg-slate-900 focus:outline-none"
                        :class="focusClass"
                    >
                        Példa gomb
                    </button>
                </div>
                </div>
        </div>
    </section>
</template>

<script setup>
import { reactive, watch, onMounted, computed } from 'vue'

const DEFAULTS = {
    font_size: 18,
    color_palette: 'base',
    letter_spacing: 0.04,
    line_height: 1.6,
    width: 1280,
    height: 800,
    show_card_game: true,
    show_recognition_game: true,
    show_map_game: true,
    reduced_motion: false,
    strong_focus_outline: true,
}

const palettes = [
    { id: 'base', name: 'Alap (sárga-fekete-fehér)', bg: '#0a0a0a', fg: '#e5e7eb', accent: '#f59e0b' },
    { id: 'high', name: 'Extra kontraszt', bg: '#000000', fg: '#ffffff', accent: '#ffd000' },
    { id: 'soft', name: 'Lágyabb háttér', bg: '#111827', fg: '#e5e7eb', accent: '#fbbf24' },
]

const state = reactive(loadFromLocal() ?? { ...DEFAULTS })

function applyToRoot() {
    const p = palettes.find(x => x.id === state.color_palette) ?? palettes[0]
    const r = document.documentElement
    r.style.setProperty('--font-base', `${state.font_size}px`)
    r.style.setProperty('--letter-space', `${state.letter_spacing}em`)
    r.style.setProperty('--line-height', `${state.line_height}`)
    r.style.setProperty('--app-bg', p.bg)
    r.style.setProperty('--app-fg', p.fg)
    r.style.setProperty('--app-accent', p.accent)
    r.style.setProperty('--screen-w', `${state.width}px`)
    r.style.setProperty('--screen-h', `${state.height}px`)
    document.body.classList.toggle('reduce-motion', !!state.reduced_motion)
}

function loadFromLocal(){ try{ return JSON.parse(localStorage.getItem('child-settings')||'null') }catch{ return null } }
function saveToLocal(){ localStorage.setItem('child-settings', JSON.stringify(state)); announce('Beállítások mentve.') }
function resetToDefaults(){ Object.assign(state, { ...DEFAULTS }); announce('Alaphelyzet visszaállítva.') }

watch(state, applyToRoot, { deep: true })
onMounted(applyToRoot)

// SR élő régió
function announce(msg){
    const el = document.getElementById('live')
    if(el){ el.textContent = msg; setTimeout(()=> el.textContent = '', 1500) }
}

const previewStyle = computed(()=>{
    const p = palettes.find(x => x.id === state.color_palette) ?? palettes[0]
    return {
        backgroundColor: p.bg,
        color: p.fg,
        letterSpacing: `${state.letter_spacing}em`,
        lineHeight: String(state.line_height),
        fontSize: state.font_size + 'px',
        width: 'min(100%, var(--screen-w, 100%))',
        height: 'auto',
    }
})

const previewClass = computed(()=> state.reduced_motion ? 'motion-reduce' : '')
const focusClass = computed(()=> state.strong_focus_outline ? 'focus:ring-4 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-neutral-900' : 'focus:outline-none')
</script>

<style scoped>
.motion-reduce * { transition: none !important; animation: none !important; }
</style>
