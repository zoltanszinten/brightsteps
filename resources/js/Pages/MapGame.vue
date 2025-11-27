<template>
    <section
        class="min-h-screen flex flex-col items-center justify-start pt-4 pb-10 px-2 sm:px-4 select-none"
        :style="pageStyle"
    >
        <div class="w-full flex flex-col items-center gap-6" :style="textBlockStyle">
            <div class="mx-auto" :style="gridWrapperStyle">
                <div
                    v-if="mapImage"
                    class="rounded-2xl border p-4 flex flex-col gap-4"
                    :style="panelStyle"
                >
                    <div class="w-full flex items-center justify-center">
                        <div
                            class="relative rounded-xl sm:rounded-2xl border w-full"
                            :style="mapCardStyle"
                        >
                            <img
                                :src="imageUrl(mapImage.path || mapImage.url)"
                                :alt="mapImage.value"
                                class="w-full h-auto object-contain block"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-3 w-full items-center mt-2">
                        <button
                            class="rounded-xl sm:rounded-2xl border px-4 py-3 text-center font-semibold focus:outline-none inline-flex items-center justify-center gap-2"
                            :style="buttonStyle()"
                            @click="press('B')"
                            :disabled="finished"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                class="w-7 h-7"
                                :style="arrowDirStyle('B')"
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
                        <button
                            class="rounded-xl sm:rounded-2xl border px-4 py-3 text-center font-semibold focus:outline-none inline-flex items-center justify-center gap-2"
                            :style="buttonStyle()"
                            @click="press('E')"
                            :disabled="finished"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                class="w-7 h-7"
                                :style="arrowDirStyle('E')"
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
                        <button
                            class="rounded-xl sm:rounded-2xl border px-4 py-3 text-center font-semibold focus:outline-none inline-flex items-center justify-center gap-2"
                            :style="buttonStyle()"
                            @click="press('J')"
                            :disabled="finished"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                class="w-7 h-7"
                                :style="arrowDirStyle('J')"
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
                        <button
                            class="rounded-xl sm:rounded-2xl border px-4 py-3 text-center text-sm font-semibold focus:outline-none inline-flex items-center justify-center gap-2"
                            :style="buttonStyle()"
                            @click="clearInput"
                            :disabled="finished"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                class="w-7 h-7"
                            >
                                <path
                                    d="M4 4l16 16"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M20 4L4 20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </button>
                    </div>

                    <div class="flex justify-center items-center gap-2 min-h-[2.5rem] mt-2">
                        <template v-if="inputSequenceArray.length">
                            <span
                                v-for="(dir, idx) in inputSequenceArray"
                                :key="idx"
                                class="inline-flex items-center justify-center"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    class="w-7 h-7"
                                    :style="arrowDirStyle(dir)"
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
                            </span>
                        </template>
                    </div>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3 sm:gap-4 mx-auto" :style="[hudWrapperStyle, textBlockStyle]">
                <div class="rounded-2xl border p-4" :style="panelStyle">
                    <p :style="mutedTextStyle" class="break-words whitespace-normal">Idő</p>
                    <p class="font-bold">{{ timeText }}</p>
                </div>
                <div class="rounded-2xl border p-4" :style="panelStyle">
                    <p :style="mutedTextStyle" class="break-words whitespace-normal">Haladás</p>
                    <p class="font-bold">{{ inputSequence.length }}/{{ totalSteps }}</p>
                </div>
            </div>

            <div
                v-if="finished"
                class="fixed inset-0 z-40 flex items-center justify-center px-4"
            >
                <div
                    class="absolute inset-0"
                    :style="backdropStyle"
                ></div>

                <div
                    class="relative mx-auto rounded-2xl border p-6 max-w-xl w-full text-center z-50"
                    :style="[winPanelStyle, textBlockStyle]"
                >
                    <p class="font-bold mb-2">Kész!</p>
                    <p>
                        {{ success ? 'Útvonal helyes!' : 'Útvonal helytelen!' }}
                        • Idő:
                        <span class="font-semibold">{{ timeText }}</span>
                    </p>

                    <p class="font-bold mb-2">Játék vége! Szép munka!</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import api from '../api'

export default {
    name: 'MapGame',
    data() {
        return {
            settings: null,
            palette: null,
            mapImages: [],
            mapImage: null,
            expectedSequence: '',
            inputSequence: '',
            moves: 0,
            timer: 0,
            timerHandle: null,
            success: false
        }
    },
    computed: {
        totalSteps() {
            return this.expectedSequence.length
        },
        finished() {
            return this.totalSteps > 0 && this.inputSequence.length >= this.totalSteps
        },
        win() {
            return this.finished && this.success
        },
        timeText() {
            const m = Math.floor(this.timer / 60)
            const s = this.timer % 60
            if (m) {
                return m + ' p ' + String(s).padStart(2, '0') + ' mp'
            }
            return s + ' mp'
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
            const style = {maxWidth: '72rem', width: '100%'}
            if (this.settings && this.settings.width) {
                style.maxWidth = this.settings.width + 'px'
            }
            return style
        },
        hudWrapperStyle() {
            const style = {maxWidth: '32rem', width: '100%'}
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
                letterSpacing: (this.settings.letter_spacing || 0) / 100 + 'em',
                lineHeight: (this.settings.line_height || 160) / 100,
                fontSize: (this.settings.font_size || 18) + 'px'
            }
        },
        mutedTextStyle() {
            if (!this.palette) {
                return {}
            }
            return {color: this.palette.text_muted}
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
        mapCardStyle() {
            if (!this.palette) {
                return {}
            }
            const style = {
                borderColor: this.palette.border,
                backgroundColor: this.palette.surface,
                color: this.palette.text
            }
            if (this.settings && this.settings.width) {
                style.maxWidth = this.settings.width + 'px'
            }
            return style
        },
        backdropStyle() {
            return {
                backgroundColor: 'rgba(0,0,0,0.5)'
            }
        },
        inputSequenceArray() {
            if (!this.inputSequence) {
                return []
            }
            return this.inputSequence.toUpperCase().split('')
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
            this.pickRandomMap()
            this.resetStateForMap()
            this.startTimer()
        },
        async fetchConfig() {
            const {data: user} = await api.get('/api/user')
            this.settings = user.user.settings

            if (this.settings && this.settings.color_palette_id) {
                const {data: pal} = await api.get('/api/color-palettes/' + this.settings.color_palette_id)
                this.palette = pal
            }

            const {data: imgs} = await api.get('/api/images', {params: {type: 'map'}})
            this.mapImages = imgs
        },
        pickRandomMap() {
            if (!this.mapImages || !this.mapImages.length) {
                this.mapImage = null
                this.expectedSequence = ''
                return
            }
            const idx = Math.floor(Math.random() * this.mapImages.length)
            const img = this.mapImages[idx]
            this.mapImage = img
            this.expectedSequence = img.value.toLowerCase()
        },
        resetStateForMap() {
            this.inputSequence = ''
            this.moves = 0
            this.success = false
            this.timer = 0
        },
        clearInput() {
            if (this.finished) {
                return
            }
            this.inputSequence = ''
            this.success = false
        },
        startTimer() {
            if (this.timerHandle) {
                clearInterval(this.timerHandle)
            }
            this.timerHandle = setInterval(() => {
                if (!this.finished) {
                    this.timer++
                }
            }, 1000)
        },
        press(dir) {
            if (!this.mapImage || !this.expectedSequence || this.finished) {
                return
            }

            const upper = dir.toUpperCase()
            this.inputSequence += upper
            this.moves++

            if (this.inputSequence.length >= this.totalSteps) {
                const expected = this.expectedSequence.toUpperCase()
                const actual = this.inputSequence.toUpperCase().slice(0, this.totalSteps)
                this.success = actual === expected
                this.saveStatistics(this.success)
            }
        },
        async saveStatistics(success) {
            await api.post('/api/statistics', {
                type: 'map',
                points: success ? 100 : 0,
                time: this.timer
            })
        },
        buttonStyle() {
            if (!this.palette) {
                return {}
            }
            return {
                borderColor: this.palette.border,
                backgroundColor: this.palette.surface,
                color: this.palette.text
            }
        },
        arrowDirStyle(dir) {
            const base = {
                transform: '',
                transformOrigin: '50% 50%'
            }
            if (!dir) {
                return base
            }
            if (dir === 'B') {
                base.transform = 'rotate(180deg)'
            } else if (dir === 'E') {
                base.transform = 'rotate(-90deg)'
            } else if (dir === 'J') {
                base.transform = 'rotate(0deg)'
            }
            return base
        },
        imageUrl(path) {
            return window.location.origin + '/storage/' + path
        }
    }
}
</script>
