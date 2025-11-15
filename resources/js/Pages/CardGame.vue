<template>
    <section
        class="min-h-screen flex flex-col items-center justify-start pt-4 pb-10 px-2 sm:px-4 select-none"
        :style="pageStyle"
    >
        <div class="w-full flex flex-col items-center gap-6" :style="textBlockStyle">
            <div class="mx-auto" :style="gridWrapperStyle">
                <div
                    class="grid gap-3 sm:gap-4 justify-center"
                    :style="{
                        gridTemplateColumns: 'repeat(auto-fit, minmax(' + cardSize + 'px, ' + cardSize + 'px))'
                    }"
                    aria-label="Memória kártyák"
                >
                    <button
                        v-for="card in cards"
                        :key="card.uid"
                        class="relative rounded-xl sm:rounded-2xl border text-center font-extrabold focus:outline-none overflow-hidden"
                        :style="cardStyle(card)"
                        @click="onFlip(card.uid)"
                    >
                        <div
                            class="w-full h-full flex items-center justify-center"
                            :style="{
                                width: '100%',
                                paddingTop: '100%',
                                position: 'relative'
                            }"
                        >
                            <div class="absolute inset-0 flex items-center justify-center">
                                <template v-if="card.showCheck">
                                    <span class="check-fade check-fade--visible">
                                        <svg
                                            viewBox="0 0 24 24"
                                            class="w-12 h-12"
                                            :style="checkmarkStyle"
                                        >
                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                opacity="0.2"
                                            />
                                            <path
                                                d="M7 12.5L10.2 16 17 8"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2.4"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </span>
                                </template>
                                <template v-else-if="isRevealed(card)">
                                    <img
                                        v-if="card.type === 'image' && card.image"
                                        :src="imageUrl(card.image.path || card.image.url)"
                                        :alt="card.value"
                                        class="w-full h-full object-contain"
                                    />
                                    <span
                                        v-else
                                        class="pointer-events-none tracking-widest text-center"
                                        :style="cardTextStyle"
                                    >
                                        {{ card.value }}
                                    </span>
                                </template>
                                <template v-else>
                                    <span class="pointer-events-none">?</span>
                                </template>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-3 sm:gap-4 mx-auto" :style="hudWrapperStyle">
                <div class="rounded-2xl border p-4" :style="panelStyle">
                    <p :style="mutedTextStyle" class="text-sm">Lépések</p>
                    <p class="text-2xl font-bold">{{ moves }}</p>
                </div>
                <div class="rounded-2xl border p-4" :style="panelStyle">
                    <p :style="mutedTextStyle" class="text-sm">Idő</p>
                    <p class="text-2xl font-bold">{{ timeText }}</p>
                </div>
                <div class="rounded-2xl border p-4" :style="panelStyle">
                    <p :style="mutedTextStyle" class="text-sm">Párok</p>
                    <p class="text-2xl font-bold">{{ matchedCount }}/{{ totalPairs }}</p>
                </div>
            </div>

            <div
                v-if="win"
                class="mx-auto rounded-2xl border p-6 max-w-xl text-center"
                :style="winPanelStyle"
            >
                <p class="text-xl font-bold mb-2">Kész!</p>
                <p>
                    Lépések:
                    <span class="font-semibold">{{ moves }}</span>
                    • Idő:
                    <span class="font-semibold">{{ timeText }}</span>
                </p>
                <button
                    class="mt-4 px-4 py-2 rounded-xl border text-sm font-semibold"
                    :style="primaryButtonStyle"
                    @click="nextGame"
                >
                    <svg
                        viewBox="0 0 24 24"
                        class="w-7 h-7"
                    >
                        <path
                            d="M5 12h11"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                        />
                        <path
                            d="M13 6l6 6-6 6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </section>
</template>

<script>
import api from '../api'

export default {
    name: 'CardGame',
    data() {
        return {
            settings: null,
            palette: null,
            gameImages: [],
            cards: [],
            firstPick: null,
            secondPick: null,
            lock: false,
            moves: 0,
            matchedCount: 0,
            timer: 0,
            timerHandle: null,
        }
    },
    computed: {
        totalPairs() {
            return this.cards.length / 2
        },
        win() {
            return this.totalPairs > 0 && this.matchedCount === this.totalPairs
        },
        timeText() {
            const m = Math.floor(this.timer / 60)
            const s = this.timer % 60
            return m ? `${m} p ${String(s).padStart(2, '0')} mp` : `${s} mp`
        },
        pageStyle() {
            if (!this.palette){
                return {}
            }

            return {
                backgroundColor: this.palette.background,
                color: this.palette.text,
            }
        },
        cardSize() {
            return Math.max(140, Math.min(this.settings?.font_size * 7, 320))
        },
        gridWrapperStyle() {
            const style = { maxWidth: '72rem', width: '100%' }
            if (this.settings && this.settings.width) {
                style.maxWidth = this.settings.width + 'px'
            }
            return style
        },
        hudWrapperStyle() {
            const style = { maxWidth: '32rem', width: '100%' }
            if (this.settings && this.settings.width) {
                style.maxWidth = Math.min(this.settings.width, 800) + 'px'
            }
            return style
        },
        textBlockStyle() {
            if (!this.settings) {
                return {}
            }

            return {
                letterSpacing: `${(this.settings.letter_spacing ?? 0) / 100}em`,
                lineHeight: (this.settings.line_height ?? 160) / 100,
                fontSize: (this.settings.font_size ?? 18) + 'px',
            }
        },
        mutedTextStyle() {
            if (!this.palette){
                return {}
            }

            return { color: this.palette.text_muted }
        },
        panelStyle() {
            if (!this.palette){
                return {}
            }

            return {
                backgroundColor: this.palette.surface_alt,
                borderColor: this.palette.border,
                color: this.palette.text,
            }
        },
        winPanelStyle() {
            if (!this.palette){
                return {}
            }

            return {
                backgroundColor: this.palette.surface_alt,
                borderColor: this.palette.accent,
                color: this.palette.text,
            }
        },
        primaryButtonStyle() {
            if (!this.palette){
                return {}
            }

            return {
                backgroundColor: this.palette.accent,
                color: this.palette.accent_text,
                borderColor: this.palette.accent,
            }
        },
        cardTextStyle() {
            return {
                fontSize: `${this.settings?.font_size}px`,
                letterSpacing: `${this.settings?.letter_spacing / 100}em`,
                lineHeight: this.settings?.line_height / 100,
            }
        },
        checkmarkStyle() {
            if (!this.palette) {
                return {}
            }

            return {
                color: this.palette.accent,
            }
        },
    },
    created() {
        this.init()
    },
    beforeUnmount() {
        if (this.timerHandle) {
            clearInterval(this.timerHandle)
        }
    },
    methods: {
        async init() {
            await this.fetchConfig()

            this.firstPick = null
            this.secondPick = null
            this.lock = false
            this.moves = 0
            this.matchedCount = 0
            this.timer = 0

            this.buildDeckFromImages()
            this.startTimer()
        },
        async fetchConfig() {
            const { data: user } = await api.get('/api/user')
            this.settings = user.user.settings

            if (this.settings && this.settings.color_palette_id) {
                const { data: pal } = await api.get(`/api/color-palettes/${this.settings.color_palette_id}`)
                this.palette = pal
            }

            const { data: imgs } = await api.get('/api/images', { params: { type: 'card' } })
            this.gameImages = Array.isArray(imgs) ? imgs : []
        },
        startTimer() {
            if (this.timerHandle){
                clearInterval(this.timerHandle)
            }

            this.timerHandle = setInterval(() => {
                if (!this.win){
                    this.timer++
                }
            }, 1000)
        },
        buildDeckFromImages() {
            const list = this.gameImages.filter(img => img.type === 'card')
            if (!list.length) {
                this.cards = []
                return
            }

            const byValue = new Map()
            for (const img of list) {
                const key = img.value
                if (!byValue.has(key)) byValue.set(key, [])
                byValue.get(key).push(img)
            }

            const valueKeys = Array.from(byValue.keys())
            if (!valueKeys.length) {
                this.cards = []
                return
            }

            const desiredPairs = Math.min(9, valueKeys.length)
            const chosenValues = valueKeys.slice(0, desiredPairs)

            const deck = []
            chosenValues.forEach((val, idx) => {
                const imgs = byValue.get(val)
                const img = imgs[0]

                deck.push({
                    uid: `i-${idx}`,
                    type: 'image',
                    value: val,
                    image: img,
                    matched: false,
                    flipped: false,
                    showCheck: false,
                })

                deck.push({
                    uid: `t-${idx}`,
                    type: 'text',
                    value: val,
                    image: null,
                    matched: false,
                    flipped: false,
                    showCheck: false,
                })
            })

            for (let i = deck.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1))
                const tmp = deck[i]
                deck[i] = deck[j]
                deck[j] = tmp
            }

            this.cards = deck
        },
        async saveStatistics() {
            await api.post('/api/statistics', {
                type: 'card',
                points: this.moves,
                time: this.timer,
            })
        },
        nextGame() {

        },
        onFlip(uid) {
            if (this.lock || this.win){
                return
            }

            const c = this.cards.find(x => x.uid === uid)
            if (!c || c.matched || c.flipped) {
                return
            }

            c.flipped = true

            if (this.firstPick === null) {
                this.firstPick = uid
                return
            }
            if (this.secondPick === null) {
                this.secondPick = uid
                this.moves++
                this.checkMatch()
            }
        },
        checkMatch() {
            const a = this.cards.find(x => x.uid === this.firstPick)
            const b = this.cards.find(x => x.uid === this.secondPick)
            if (a.value === b.value && a.uid !== b.uid) {
                a.matched = true
                b.matched = true
                this.matchedCount++

                const first = a
                const second = b
                this.firstPick = null
                this.secondPick = null

                setTimeout(() => {
                    first.showCheck = true
                    second.showCheck = true
                }, 500)

                if (this.win) {
                    this.saveStatistics()
                }
            } else {
                this.lock = true

                setTimeout(() => {
                    a.flipped = false
                    b.flipped = false
                    this.firstPick = null
                    this.secondPick = null
                    this.lock = false
                }, 500)
            }
        },
        isRevealed(card) {
            return card.flipped || card.matched
        },
        cardStyle(card) {
            const base = {
                borderColor: this.palette.border,
                backgroundColor: this.palette.surface,
                color: this.palette.text,
            }

            if (card.matched) {
                base.borderColor = this.palette.accent
                if (card.showCheck) {
                    base.backgroundColor = this.palette.surface_alt
                }
            } else if (card.flipped) {
                base.backgroundColor = this.palette.surface_alt
            }

            return base
        },
        imageUrl(path) {
            return `${window.location.origin}/storage/${path}`
        },
    },
}
</script>

<style scoped>
.check-fade {
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.check-fade--visible {
    opacity: 1;
    transform: scale(1);
}
</style>
