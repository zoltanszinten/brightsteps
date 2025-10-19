<template>
    <section class="container mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">Gyerekek kezelése</h1>
            <button @click="openCreate" class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400">Új gyerek</button>
        </header>

        <!-- Create / Edit drawer -->
        <div v-if="drawer.open" class="fixed inset-0 bg-black/50 z-40" @click.self="closeDrawer">
            <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-neutral-950 border-l border-neutral-800 p-6 overflow-y-auto">
                <h2 class="text-xl font-bold text-amber-400 mb-4">{{ drawer.mode === 'create' ? 'Új gyerek' : 'Gyerek szerkesztése' }}</h2>

                <form class="space-y-4" @submit.prevent="saveChild">
                    <div>
                        <label for="childName" class="block text-sm font-semibold mb-1 text-amber-400">Név</label>
                        <input id="childName" v-model.trim="form.name" class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950" placeholder="pl. Kiss Anna" />
                    </div>

                    <div>
                        <label for="childNote" class="block text-sm font-semibold mb-1 text-amber-400">Megjegyzés</label>
                        <input id="childNote" v-model.trim="form.note" class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950" placeholder="opcionális" />
                    </div>

                    <fieldset class="grid sm:grid-cols-2 gap-3">
                        <legend class="sr-only">Alap beállítások</legend>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="font">Betűméret</label>
                            <input id="font" type="number" min="14" max="32" step="1" v-model.number="form.settings.font_size" class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-amber-400" for="palette">Színpaletta</label>
                            <select id="palette" v-model="form.settings.color_palette" class="w-full rounded-xl px-3 py-3 border border-amber-400 bg-neutral-950">
                                <option v-for="p in palettes" :key="p.id" :value="p.id">{{ p.name }}</option>
                            </select>
                        </div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.settings.show_card_game" class="h-5 w-5" />
                            <span>Kártya játék</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.settings.show_recognition_game" class="h-5 w-5" />
                            <span>Képfelismerés</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" v-model="form.settings.show_map_game" class="h-5 w-5" />
                            <span>Térkép</span>
                        </label>
                    </fieldset>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-4 py-3 rounded-xl border border-amber-400 text-amber-400">
                            {{ drawer.mode === 'create' ? 'Létrehozás' : 'Mentés' }}
                        </button>
                        <button type="button" class="px-4 py-3 rounded-xl border border-neutral-700 text-neutral-300" @click="closeDrawer">Mégse</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Children table -->
        <div class="rounded-3xl border border-neutral-800 bg-neutral-950 overflow-hidden">
            <div class="grid grid-cols-12 px-4 py-3 text-sm text-neutral-400 border-b border-neutral-800">
                <div class="col-span-4">Név</div>
                <div class="col-span-3">Megjegyzés</div>
                <div class="col-span-3">Létrehozva</div>
                <div class="col-span-2 text-right">Műveletek</div>
            </div>

            <div v-if="children.length === 0" class="px-4 py-6 text-neutral-300">Nincs gyerek felvéve.</div>

            <div v-for="c in children" :key="c.id" class="grid grid-cols-12 px-4 py-3 border-b border-neutral-900 items-center">
                <div class="col-span-4 truncate">
                    <span class="font-semibold text-neutral-100">{{ c.name }}</span>
                </div>
                <div class="col-span-3 truncate text-neutral-300">{{ c.note || '—' }}</div>
                <div class="col-span-3 text-neutral-400">{{ formatDate(c.created_at) }}</div>
                <div class="col-span-2">
                    <div class="flex justify-end gap-2">
                        <button @click="gotoSettings(c)" class="px-3 py-2 rounded-xl border text-amber-400 border-amber-400">Statisztikák</button>
                        <button @click="gotoSettings(c)" class="px-3 py-2 rounded-xl border text-amber-400 border-amber-400">Beállítások</button>
                        <div class="relative" @keydown.escape="openTokenFor=null">
                            <button @click="toggleTokenMenu(c)" class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400">Link</button>
                            <div v-if="openTokenFor===c.id" class="absolute right-0 mt-2 w-72 rounded-2xl border border-neutral-800 bg-neutral-950 p-3 z-10">
                                <div class="flex items-end gap-2">
                                    <div class="flex-1">
                                        <label class="block text-xs text-neutral-400 mb-1" for="exp-{{c.id}}">Lejárat (óra)</label>
                                        <input :id="`exp-${c.id}`" type="number" min="0" step="1" v-model.number="tokenForm.expires_in_hours" class="w-full rounded-xl px-2 py-2 border border-neutral-700 bg-neutral-900 text-neutral-200" />
                                    </div>
                                    <button @click="createTokenFor(c)" class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400">Létrehoz</button>
                                </div>

                                <div class="mt-3 space-y-2 max-h-64 overflow-y-auto">
                                    <div v-for="t in childTokens(c.id)" :key="t.id" class="rounded-xl border border-neutral-800 p-2">
                                        <p class="text-xs text-neutral-400">Link</p>
                                        <p class="text-xs text-neutral-300 break-all">{{ t.link }}</p>
                                        <p class="text-xs text-neutral-500 mt-1">Lejár: {{ t.expires_at ? formatDate(t.expires_at) : 'nincs' }}</p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <button @click="copyLink(t)" class="px-2 py-1 rounded-lg border border-neutral-700 text-neutral-200 text-xs">Másolás</button>
                                            <button @click="downloadQr(t)" class="px-2 py-1 rounded-lg border border-neutral-700 text-neutral-200 text-xs">QR</button>
                                            <button @click="removeToken(t.id)" class="px-2 py-1 rounded-lg border border-red-600 text-red-400 text-xs">Törlés</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <canvas :id="`qr-preview-${c.id}`" width="160" height="160" class="rounded bg-white"></canvas>
                                </div>
                            </div>
                        </div>
                        <button @click="removeChild(c.id)" class="px-3 py-2 rounded-xl border border-red-600 text-red-400">Törlés</button>
                    </div>
                </div>
            </div>
        </div>

        <p id="live" class="sr-only" role="status" aria-live="polite"></p>
    </section>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import QRCode from 'qrcode' // npm i qrcode

const CHILDREN_KEY = 'children'
const TOKENS_KEY = 'child-tokens'

const router = useRouter()

const palettes = [
    { id: 'base', name: 'Alap (sárga-fekete-fehér)' },
    { id: 'high', name: 'Extra kontraszt' },
    { id: 'soft', name: 'Lágyabb háttér' },
]

const children = ref(loadChildren())
const openTokenFor = ref(null)
const tokenForm = reactive({ expires_in_hours: 0 })

const drawer = reactive({ open: false, mode: 'create', editId: null })
const form = reactive({
    name: '',
    note: '',
    settings: {
        font_size: 18,
        color_palette: 'base',
        show_card_game: true,
        show_recognition_game: true,
        show_map_game: true,
    }
})

function openCreate(){
    drawer.open = true; drawer.mode = 'create'; drawer.editId = null
    Object.assign(form, { name:'', note:'', settings: { font_size:18, color_palette:'base', show_card_game:true, show_recognition_game:true, show_map_game:true } })
}
function openEdit(c){
    drawer.open = true; drawer.mode = 'edit'; drawer.editId = c.id
    Object.assign(form, { name: c.name, note: c.note || '', settings: { ...form.settings, ...c.settings } })
}
function closeDrawer(){ drawer.open = false }

function saveChild(){
    if(!form.name.trim()){ announce('Név szükséges.'); return }
    if(drawer.mode === 'create'){
        const entry = {
            id: crypto.randomUUID(),
            name: form.name.trim(),
            note: form.note.trim(),
            settings: { ...form.settings },
            created_at: Date.now(),
        }
        children.value.unshift(entry)
    } else {
        const idx = children.value.findIndex(x => x.id === drawer.editId)
        if(idx !== -1){
            children.value[idx] = { ...children.value[idx], name: form.name.trim(), note: form.note.trim(), settings: { ...form.settings } }
        }
    }
    persistChildren()
    drawer.open = false
    announce('Mentve.')
}

function removeChild(id){
    children.value = children.value.filter(x => x.id !== id)
    persistChildren()
    // töröljük a hozzá tartozó tokeneket
    const list = loadTokens().filter(x => x.child_id !== id)
    localStorage.setItem(TOKENS_KEY, JSON.stringify(list))
    announce('Gyerek törölve.')
}

function gotoSettings(c){
    // Navigálás a meglévő ChildSettings oldalra, query param-mal
    router.push({ name: 'child-settings', query: { child: c.id } })
}

function toggleTokenMenu(c){
    openTokenFor.value = openTokenFor.value === c.id ? null : c.id
    if(openTokenFor.value){
        // azonnali QR preview a legutóbbi linkhez
        const t = childTokens(c.id)[0]
        if(t) drawQrTo(`qr-preview-${c.id}`, t.link)
    }
}

function createTokenFor(c){
    const id = crypto.randomUUID()
    const token = generateToken()
    const link = `${location.origin}/play?token=${encodeURIComponent(token)}`
    const now = Date.now()
    const expires_at = tokenForm.expires_in_hours > 0 ? now + tokenForm.expires_in_hours * 3600_000 : null
    const entry = { id, child_id: c.id, token, link, created_at: now, expires_at }
    const list = loadTokens()
    list.unshift(entry)
    localStorage.setItem(TOKENS_KEY, JSON.stringify(list))
    announce('Link létrehozva.')
    drawQrTo(`qr-preview-${c.id}`, link)
}

function childTokens(childId){
    return loadTokens().filter(x => x.child_id === childId)
}

function copyLink(t){ navigator.clipboard.writeText(t.link).then(()=> announce('Link másolva.')) }
function removeToken(id){
    const list = loadTokens().filter(x => x.id !== id)
    localStorage.setItem(TOKENS_KEY, JSON.stringify(list))
    announce('Link törölve.')
}
function downloadQr(t){
    // ideiglenes vászon
    const canvasId = `dl-${t.id}`
    const canvas = document.createElement('canvas')
    canvas.width = 256; canvas.height = 256
    document.body.appendChild(canvas)
    QRCode.toCanvas(canvas, t.link, { width: 256, margin: 1 }, ()=>{
        const a = document.createElement('a')
        a.href = canvas.toDataURL('image/png')
        a.download = `brightsteps-token-${t.id}.png`
        a.click()
        canvas.remove()
    })
}

function drawQrTo(canvasId, text){
    const canvas = document.getElementById(canvasId)
    if(!canvas) return
    QRCode.toCanvas(canvas, text, { width: 160, margin: 1, color: { dark:'#000000', light:'#ffffff' } })
}

function loadChildren(){
    try{ return JSON.parse(localStorage.getItem(CHILDREN_KEY) || '[]') }catch{ return [] }
}
function persistChildren(){ localStorage.setItem(CHILDREN_KEY, JSON.stringify(children.value)) }

function loadTokens(){
    try{ return JSON.parse(localStorage.getItem(TOKENS_KEY) || '[]') }catch{ return [] }
}

function formatDate(ts){ if(!ts) return '—'; return new Date(ts).toLocaleString('hu-HU') }
function announce(msg){ const el = document.getElementById('live'); if(el){ el.textContent = msg; setTimeout(()=> el.textContent='', 1200) } }

onMounted(()=> { /* no-op */ })
</script>
