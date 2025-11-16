<template>
    <section
        class="min-h-screen flex flex-col items-center justify-start pt-4 pb-10 px-2 sm:px-4 select-none"
        :style="pageStyle"
    >
        <div class="w-full flex flex-col items-center gap-6" :style="textBlockStyle">
            <div class="mx-auto" :style="gridWrapperStyle">
                <div
                    class="rounded-2xl border p-4 flex flex-col gap-4"
                    :style="panelStyle"
                    v-if="currentQuestion"
                >
                    <div class="w-full">
                        <div
                            class="relative rounded-xl sm:rounded-2xl border overflow-hidden"
                            :style="questionCardStyle"
                        >
                            <img
                                v-if="currentQuestion.image"
                                :src="imageUrl(currentQuestion.image.path || currentQuestion.image.url)"
                                :alt="currentQuestion.value"
                                class="w-full h-auto object-contain block"
                            />
                        </div>
                    </div>

                    <div class="grid gap-3 sm:gap-4 w-full" :style="optionsGridStyle">
                        <button
                            v-for="opt in currentQuestion.options"
                            :key="opt"
                            class="rounded-xl sm:rounded-2xl border px-4 py-3 break-words whitespace-normal text-center font-semibold focus:outline-none"
                            :style="optionStyle(opt)"
                            @click="selectOption(opt)"
                            :disabled="lock || win"
                        >
                            <span class="pointer-events-none tracking-widest text-center" :style="cardTextStyle">
                                {{ opt }}
                            </span>
                        </button>
                    </div>
                </div>

                <div
                    v-else
                    class="rounded-2xl border p-6 text-center"
                    :style="panelStyle"
                >
                    Nincs elérhető feladat.
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-3 sm:gap-4 mx-auto" :style="[hudWrapperStyle, textBlockStyle]">
                <div class="rounded-2xl border p-4" :style="panelStyle">
                    <p :style="mutedTextStyle" class="break-words whitespace-normal">Kérdés</p>
                    <p class="font-bold">
                        {{ totalQuestions ? currentQuestionIndex + 1 : 0 }}/{{ totalQuestions }}
                    </p>
                </div>
                <div class="rounded-2xl border p-4" :style="panelStyle">
                    <p :style="mutedTextStyle" class="break-words whitespace-normal">Idő</p>
                    <p class="font-bold">{{ timeText }}</p>
                </div>
                <div class="rounded-2xl border p-4" :style="panelStyle">
                    <p :style="mutedTextStyle" class="break-words whitespace-normal">Helyes válaszok</p>
                    <p class="font-bold">{{ correctCount }}/{{ totalQuestions }}</p>
                </div>
            </div>

            <div
                v-if="win"
                class="fixed inset-0 z-40 flex items-center justify-center px-4"
            >
                <div
                    class="absolute inset-0"
                    :style="backdropStyle"
                    @click="nextGame"
                ></div>

                <div
                    class="relative mx-auto rounded-2xl border p-6 max-w-xl w-full text-center z-50"
                    :style="[winPanelStyle, textBlockStyle]"
                >
                    <p class="font-bold mb-2">Kész!</p>
                    <p>
                        Kérdések:
                        <span class="font-semibold">{{ totalQuestions }}</span>
                        • Helyes válaszok:
                        <span class="font-semibold">{{ correctCount }}</span>
                        • Idő:
                        <span class="font-semibold">{{ timeText }}</span>
                    </p>
                    <button
                        class="mt-4 px-4 py-2 rounded-xl border text-sm font-semibold inline-flex items-center justify-center"
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
        </div>
    </section>
</template>

<script>
import api from '../api'

export default {
    name: 'RecognitionGame',
    data() {
        return {
            settings: null,
            palette: null,
            gameImages: [],
            falseValues: [],
            questions: [],
            currentQuestionIndex: 0,
            selectedOption: null,
            lock: false,
            moves: 0,
            correctCount: 0,
            timer: 0,
            timerHandle: null
        }
    },
    computed: {
        backdropStyle() {
            return {
                backgroundColor: 'rgba(0,0,0,0.5)'
            }
        },
        currentQuestion() {
            if (!this.questions.length) {
                return null
            }
            return this.questions[this.currentQuestionIndex] || null
        },
        totalQuestions() {
            return this.questions.length
        },
        win() {
            return this.totalQuestions > 0 && this.moves === this.totalQuestions
        },
        timeText() {
            const m = Math.floor(this.timer / 60)
            const s = this.timer % 60
            return m ? `${m} p ${String(s).padStart(2, '0')} mp` : `${s} mp`
        },
        pageStyle() {
            if (!this.palette) {
                return {}
            }
            return {
                backgroundColor: this.palette.background,
                color: this.palette.text
            }
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
                fontSize: (this.settings.font_size ?? 18) + 'px'
            }
        },
        mutedTextStyle() {
            if (!this.palette) {
                return {}
            }
            return { color: this.palette.text_muted }
        },
        panelStyle() {
            if (!this.palette) {
                return {}
            }
            return {
                backgroundColor: this.palette.surface_alt,
                borderColor: this.palette.border,
                color: this.palette.text
            }
        },
        winPanelStyle() {
            if (!this.palette) {
                return {}
            }
            return {
                backgroundColor: this.palette.surface_alt,
                borderColor: this.palette.accent,
                color: this.palette.text
            }
        },
        primaryButtonStyle() {
            if (!this.palette) {
                return {}
            }
            return {
                backgroundColor: this.palette.accent,
                color: this.palette.accent_text,
                borderColor: this.palette.accent
            }
        },
        cardTextStyle() {
            return {
                fontSize: `${this.settings?.font_size}px`,
                letterSpacing: `${this.settings?.letter_spacing / 100}em`,
                lineHeight: this.settings?.line_height / 100
            }
        },
        questionCardStyle() {
            if (!this.palette) {
                return {}
            }
            const style = {
                borderColor: this.palette.border,
                backgroundColor: this.palette.surface,
                color: this.palette.text,
                width: '100%'
            }
            if (this.settings && this.settings.width) {
                style.maxWidth = this.settings.width + 'px'
            }
            style.maxHeight = '480px'
            return style
        },
        optionsGridStyle() {
            return {
                gridTemplateColumns: 'repeat(auto-fit, minmax(160px, 1fr))'
            }
        }
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

            this.currentQuestionIndex = 0
            this.selectedOption = null
            this.lock = false
            this.moves = 0
            this.correctCount = 0
            this.timer = 0

            this.buildQuestionsFromImages()
            this.startTimer()
        },
        async fetchConfig() {
            const { data: user } = await api.get('/api/user')
            this.settings = user.user.settings

            if (this.settings) {
                if(!this.settings.show_recognition_game){
                    this.nextGame()
                }

                if(this.settings.color_palette_id){
                    const { data: pal } = await api.get(`/api/color-palettes/${this.settings.color_palette_id}`)
                    this.palette = pal
                }
            }

            const { data: imgs } = await api.get('/api/images', { params: { type: 'recognition' } })
            this.gameImages = imgs

            const { data: falseVals } = await api.get('/api/false-values')
            this.falseValues = falseVals
        },
        startTimer() {
            if (this.timerHandle) {
                clearInterval(this.timerHandle)
            }

            this.timerHandle = setInterval(() => {
                if (!this.win) {
                    this.timer++
                }
            }, 1000)
        },
        extractFalseValue(item) {
            if (typeof item === 'string') {
                return item
            }

            if (item && typeof item === 'object') {
                if (typeof item.value === 'string' && item.value.length > 0) {
                    return item.value
                }
                if (typeof item.label === 'string' && item.label.length > 0) {
                    return item.label
                }
                if (typeof item.name === 'string' && item.name.length > 0) {
                    return item.name
                }
            }

            return String(item)
        },
        buildQuestionsFromImages() {
            const list = this.gameImages

            const shuffled = list.slice()
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1))
                const temp = shuffled[i]
                shuffled[i] = shuffled[j]
                shuffled[j] = temp
            }

            let maxQuestions = 10
            if (shuffled.length < maxQuestions) {
                maxQuestions = shuffled.length
            }
            const selected = shuffled.slice(0, maxQuestions)

            const allImageValues = []
            for (let i = 0; i < list.length; i++) {
                const value = list[i].value
                if (allImageValues.indexOf(value) === -1) {
                    allImageValues.push(value)
                }
            }

            const allWrongValues = []
            for (let i = 0; i < allImageValues.length; i++) {
                const value = allImageValues[i]
                if (allWrongValues.indexOf(value) === -1) {
                    allWrongValues.push(value)
                }
            }

            for (let i = 0; i < this.falseValues.length; i++) {
                const raw = this.falseValues[i]
                const value = this.extractFalseValue(raw)
                if (allWrongValues.indexOf(value) === -1) {
                    allWrongValues.push(value)
                }
            }

            const questions = []

            for (let i = 0; i < selected.length; i++) {
                const img = selected[i]
                const correct = img.value

                const pool = []
                for (let k = 0; k < allWrongValues.length; k++) {
                    const v = allWrongValues[k]
                    if (v !== correct) {
                        pool.push(v)
                    }
                }

                const wrongOptions = this.getRandomSample(pool, 3)

                const options = [correct]
                for (let k = 0; k < wrongOptions.length; k++) {
                    const v = wrongOptions[k]
                    if (options.indexOf(v) === -1) {
                        options.push(v)
                    }
                }

                for (let k = options.length - 1; k > 0; k--) {
                    const j = Math.floor(Math.random() * (k + 1))
                    const temp = options[k]
                    options[k] = options[j]
                    options[j] = temp
                }

                questions.push({
                    id: img.id || i,
                    image: img,
                    value: correct,
                    options: options
                })
            }

            this.questions = questions
        },
        getRandomSample(arr, n) {
            const result = []
            const copy = arr.slice()

            if (n > copy.length) {
                n = copy.length
            }

            for (let i = 0; i < n; i++) {
                const idx = Math.floor(Math.random() * copy.length)
                result.push(copy[idx])
                copy.splice(idx, 1)
            }

            return result
        },
        async saveStatistics() {
            const percent = (this.correctCount / this.totalQuestions) * 100
            await api.post('/api/statistics', {
                type: 'recognition',
                points: percent,
                time: this.timer
            })
        },
        nextGame() {
            this.$router.push({ name: 'map-game' })
        },
        selectOption(option) {
            if (this.lock || this.win || !this.currentQuestion) {
                return
            }

            this.lock = true
            this.selectedOption = option

            if (option === this.currentQuestion.value) {
                this.correctCount++
            }

            this.moves++

            setTimeout(() => {
                if (this.moves >= this.totalQuestions) {
                    if (this.win) {
                        this.saveStatistics()
                    }
                    this.lock = false
                    return
                }

                this.currentQuestionIndex++
                this.selectedOption = null
                this.lock = false
            }, 500)
        },
        optionStyle(option) {
            if (!this.palette) {
                return {}
            }

            const base = {
                borderColor: this.palette.border,
                backgroundColor: this.palette.surface,
                color: this.palette.text
            }

            if (!this.currentQuestion) {
                return base
            }

            if (this.selectedOption && this.lock) {
                if (option === this.currentQuestion.value) {
                    base.backgroundColor = this.palette.accent
                    base.color = this.palette.accent_text
                    base.borderColor = this.palette.accent
                } else if (option === this.selectedOption && option !== this.currentQuestion.value) {
                    base.backgroundColor = this.palette.surface_alt
                }
            }

            return base
        },
        imageUrl(path) {
            return `${window.location.origin}/storage/${path}`
        }
    }
}
</script>
