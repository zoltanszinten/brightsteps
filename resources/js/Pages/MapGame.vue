<template>
    <section class="container mx-auto px-2 sm:px-4 pt-4 pb-8 select-none">
        <!-- Image panel -->
        <div class="mx-auto max-w-5xl rounded-2xl border border-neutral-800 bg-neutral-950 overflow-hidden">
            <div class="mx-auto w-full max-w-2xl aspect-[16/9] bg-neutral-900 rounded-2xl overflow-hidden flex items-center justify-center">
                <div class="w-full h-full grid place-items-center px-4">
                    <div class="text-center">
                        <p class="text-amber-400 font-extrabold tracking-widest mb-2 text-lg sm:text-xl">
                            Térkép
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- HUD -->
        <div class="grid sm:grid-cols-3 gap-3 sm:gap-4 mt-5 mx-auto max-w-5xl">
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Feladat</p>
                <p class="text-2xl font-bold text-neutral-100">{{ index+1 }}/{{ items.length }}</p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Pont</p>
                <p class="text-2xl font-bold text-neutral-100">{{ score }}</p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Idő</p>
                <p class="text-2xl font-bold text-neutral-100">{{ timeText }}</p>
            </div>
        </div>

        <!-- Controls -->
        <div class="mt-5 mx-auto max-w-5xl flex items-center justify-between">
            <button class="px-4 py-3 rounded-xl border border-neutral-700 text-neutral-200" @click="resetGame">
                Újrakezdés
            </button>
        </div>

        <!-- Result -->
        <div v-if="finished" class="mt-6 mx-auto max-w-3xl rounded-2xl border border-amber-400 bg-neutral-900 p-6">
            <p class="text-xl font-bold text-amber-400 mb-2">Kész!</p>
            <p class="text-neutral-200">Pont: <span class="font-semibold">{{ score }}</span> • Idő: <span class="font-semibold">{{ timeText }}</span></p>
        </div>

        <p id="live" class="sr-only" role="status" aria-live="polite"></p>
    </section>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const items = ref([
    {
        id: 'm1',
        titleHU: 'Hol van metróállomás?',
        image: null,
        choicesHU: ['Kálvin tér', 'Uzsoki kórház', 'ELTE Parkoló', 'Iskola bejárat'],
        answer: 1,
    },
])

const index = ref(0)
const score = ref(0)
const locked = ref(false)
const picked = ref(null)
const timer = ref(0)
let tick = null

const finished = computed(()=> index.value >= items.value.length)
const currentItem = computed(()=> items.value[Math.min(index.value, items.value.length-1)] || items.value[0])
const canNext = computed(()=> picked.value !== null || finished.value)

const timeText = computed(()=>{
    const m = Math.floor(timer.value / 60)
    const s = timer.value % 60
    return m ? `${m} p ${String(s).padStart(2,'0')} mp` : `${s} mp`
})

onMounted(()=> { resetGame(); tick = setInterval(()=> { if(!finished.value) timer.value++ }, 1000) })
onBeforeUnmount(()=> clearInterval(tick))

function resetGame(){
    index.value = 0
    score.value = 0
    locked.value = false
    picked.value = null
    timer.value = 0
    announce('Új játék.')
}

function onChoose(i){
    if(locked.value || finished.value) return
    picked.value = i
    locked.value = true
    const ok = i === currentItem.value.answer
    if(ok) { score.value += 1; announce('Helyes válasz.') } else { announce('Helytelen válasz.') }
}

function nextTask(){
    if(!canNext.value) return
    index.value += 1
    locked.value = false
    picked.value = null
    if(!finished.value) announce('Következő feladat.')
    else announce('Játék vége.')
}

function buttonClass(i){
    const base = 'border-neutral-800 bg-neutral-950 hover:bg-neutral-900 text-neutral-100 focus:ring-4 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-neutral-900'
    if(picked.value === null) return base
    const ok = i === currentItem.value.answer
    const mine = i === picked.value
    if(ok && mine) return `${base} border-emerald-600 text-emerald-300 bg-emerald-900/20`
    if(ok) return `${base} border-emerald-600 text-emerald-300`
    if(mine) return `${base} border-red-600 text-red-300`
    return base
}

function announce(msg){
    const el = document.getElementById('live')
    if(el){ el.textContent = msg; setTimeout(()=> el.textContent = '', 1200) }
}
</script>
