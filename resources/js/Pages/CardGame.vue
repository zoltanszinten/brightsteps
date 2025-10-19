<template>
    <section class="container mx-auto px-2 sm:px-4 pt-4 pb-8 select-none">
        <!-- Grid: centered, slightly smaller cards, 9 pairs -->
        <div class="mx-auto max-w-5xl">
            <div
                class="grid gap-3 sm:gap-4 justify-center"
                :style="{
          gridTemplateColumns: 'repeat(auto-fit, minmax(' + cardMin + ', ' + cardMin + '))'
        }"
                aria-label="Memória kártyák"
            >
                <button
                    v-for="card in cards"
                    :key="card.uid"
                    class="relative aspect-square rounded-xl sm:rounded-2xl border text-center font-extrabold focus:outline-none"
                    :class="cardClass(card)"
                    :aria-label="ariaLabel(card)"
                    @click="onFlip(card.uid)"
                >
          <span v-if="isRevealed(card)" class="pointer-events-none text-2xl sm:text-3xl tracking-widest">
            {{ card.face }}
          </span>
                    <span v-else class="pointer-events-none text-neutral-400 text-2xl">?</span>
                    <span v-if="card.matched" class="absolute top-1.5 right-1.5 text-xs px-2 py-0.5 rounded-full bg-emerald-600 text-white">pár</span>
                </button>
            </div>
        </div>

        <!-- HUD under grid -->
        <div class="grid sm:grid-cols-3 gap-3 sm:gap-4 mt-6 mx-auto max-w-3xl">
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Lépések</p>
                <p class="text-2xl font-bold text-neutral-100">{{ moves }}</p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Idő</p>
                <p class="text-2xl font-bold text-neutral-100">{{ timeText }}</p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Párok</p>
                <p class="text-2xl font-bold text-neutral-100">{{ matchedCount }}/{{ totalPairs }}</p>
            </div>
        </div>

        <!-- Win panel -->
        <div v-if="win" class="mt-6 mx-auto max-w-3xl rounded-2xl border border-amber-400 bg-neutral-900 p-6">
            <p class="text-xl font-bold text-amber-400 mb-2">Kész!</p>
            <p class="text-neutral-200">Lépések: <span class="font-semibold">{{ moves }}</span> • Idő: <span class="font-semibold">{{ timeText }}</span></p>
        </div>

        <p id="live" class="sr-only" role="status" aria-live="polite"></p>
    </section>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'

const FACE_SET = [
    'STOP','BUSZ','METRÓ','ZEBRA','KERÉK','VONAT','LÁMPA','HÍD',
    'ISKOLA','PARK','ÚT','VÁGÁNY','BALRA','JOBBRA','ÁTJÁRÓ','VESZÉLY',
]

// 9 pairs -> 18 cards
const TOTAL_CARDS = 18
const totalPairs = computed(()=> TOTAL_CARDS / 2)

// slightly smaller cards, centered layout
const cardMin = 'clamp(104px, 12vw, 168px)'

const cards = ref([])
const firstPick = ref(null) // uid
const secondPick = ref(null) // uid
const lock = ref(false)
const moves = ref(0)
const matchedCount = ref(0)
const timer = ref(0)
let timerHandle = null

const win = computed(()=> matchedCount.value === totalPairs.value)

const timeText = computed(()=>{
    const m = Math.floor(timer.value / 60)
    const s = timer.value % 60
    return m ? `${m} p ${String(s).padStart(2,'0')} mp` : `${s} mp`
})

watch(win, v => { if(v) announce('Játék vége.') })

onMounted(()=> {
    resetGame()
    timerHandle = setInterval(()=> { if(!win.value) timer.value++ }, 1000)
})
onBeforeUnmount(()=> clearInterval(timerHandle))

function buildDeck(){
    const needPairs = totalPairs.value
    const faces = FACE_SET.slice(0, needPairs)
    const deck = faces.flatMap((f,i)=> ([
        { uid: `a-${i}`, face: f, matched: false, flipped: false },
        { uid: `b-${i}`, face: f, matched: false, flipped: false },
    ]))
    for(let i = deck.length - 1; i > 0; i--){
        const j = Math.floor(Math.random() * (i + 1))
        ;[deck[i], deck[j]] = [deck[j], deck[i]]
    }
    return deck
}

function resetGame(){
    firstPick.value = null
    secondPick.value = null
    lock.value = false
    moves.value = 0
    matchedCount.value = 0
    timer.value = 0
    cards.value = buildDeck()
    announce('Új játék.')
}

function onFlip(uid){
    if(lock.value) return
    const c = cards.value.find(x => x.uid === uid)
    if(!c || c.matched || c.flipped) return
    c.flipped = true
    if(firstPick.value === null){ firstPick.value = uid; return }
    if(secondPick.value === null){
        secondPick.value = uid
        moves.value++
        checkMatch()
    }
}

function checkMatch(){
    const a = cards.value.find(x => x.uid === firstPick.value)
    const b = cards.value.find(x => x.uid === secondPick.value)
    if(!a || !b) return
    if(a.face === b.face){
        a.matched = b.matched = true
        firstPick.value = secondPick.value = null
        matchedCount.value++
        announce('Pár megtalálva.')
    } else {
        lock.value = true
        setTimeout(()=>{
            a.flipped = false
            b.flipped = false
            firstPick.value = secondPick.value = null
            lock.value = false
        }, 600)
    }
}

function isRevealed(card){ return card.flipped || card.matched }

function cardClass(card){
    const base = 'border-neutral-800 bg-neutral-950 text-amber-400 hover:bg-neutral-900 focus:ring-4 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-neutral-900'
    const on = 'bg-neutral-900'
    const done = 'bg-emerald-900/30 border-emerald-600 text-emerald-300'
    return [base, card.matched ? done : (card.flipped ? on : '')]
}

function ariaLabel(card){
    if(card.matched) return `Párosítva: ${card.face}`
    if(card.flipped) return `Felfordítva: ${card.face}`
    return 'Lefordított kártya'
}

function announce(msg){
    const el = document.getElementById('live')
    if(el){ el.textContent = msg; setTimeout(()=> el.textContent = '', 1200) }
}
</script>
