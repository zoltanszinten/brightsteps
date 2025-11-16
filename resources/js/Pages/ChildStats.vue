<template>
    <section class="container mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">Statisztikák</h1>
                <p class="text-neutral-300">
                    Gyermek:
                    <span class="font-semibold text-neutral-100">
                        {{child.name }}
                    </span>
                </p>
                <p v-if="lastSession">
                    Utolsó játék:
                    <span class="text-neutral-100 font-semibold">
                        {{ formatDateTime(lastSession.created_at) }}
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    @click="cancel"
                    class="px-3 py-2 rounded-xl border border-neutral-700 text-neutral-300 hover:bg-neutral-900"
                >
                    Vissza
                </button>
            </div>
        </header>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Összes játék</p>
                <p class="text-2xl font-bold text-neutral-100">{{ totalSessions }}</p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Átlag pont</p>
                <p class="text-2xl font-bold text-neutral-100">
                    {{ averagePointsDisplay }}
                </p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Átlag idő</p>
                <p class="text-2xl font-bold text-neutral-100">
                    {{ averageTimeDisplay }}
                </p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-4 mb-6">
            <div
                v-for="t in perType"
                :key="t.type"
                class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4"
            >
                <p class="text-neutral-400 text-sm mb-1">{{ t.label }}</p>
                <p class="text-xl font-bold text-neutral-100">
                    {{ t.count }} alkalom
                </p>
                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-neutral-400">Átlag pont</p>
                        <p class="font-semibold">
                            {{ formatPercent(t.avgPoints) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-neutral-400">Átlag idő</p>
                        <p class="font-semibold">
                            {{ formatDuration(t.avgTime) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-neutral-800 bg-neutral-950 overflow-hidden">
            <div class="px-4 py-3 border-b border-neutral-800 flex items-center justify-between">
                <p class="text-sm text-neutral-300">Játékok listája</p>
                <select
                    v-model="filterType"
                    class="rounded-xl px-2 py-2 border border-neutral-700 bg-neutral-900 text-neutral-200 text-sm"
                >
                    <option value="all">Összes</option>
                    <option value="card">Kártya játék</option>
                    <option value="recognition">Képfelismerés</option>
                    <option value="map">Térkép</option>
                </select>
            </div>

            <div class="grid grid-cols-12 px-4 py-2 text-sm text-neutral-400 border-b border-neutral-800">
                <div class="col-span-4">Dátum</div>
                <div class="col-span-3">Modul</div>
                <div class="col-span-2">Pontszám</div>
                <div class="col-span-2">Idő</div>
                <div class="col-span-1 text-right">#</div>
            </div>

            <div
                v-if="filteredStats.length === 0"
                class="px-4 py-4 text-neutral-300 text-sm"
            >
                Nincs statisztika a kiválasztott szűrővel.
            </div>

            <div
                v-for="s in filteredStats"
                :key="s.id"
                class="grid grid-cols-12 px-4 py-3 border-b border-neutral-900 items-center"
            >
                <div class="col-span-4 text-neutral-300">
                    {{ formatDateTime(s.created_at) }}
                </div>
                <div class="col-span-3 text-neutral-300">
                    {{ typeLabel(s.type) }}
                </div>
                <div class="col-span-2 text-neutral-100 font-semibold">
                    {{ formatPercent(s.points) }}
                </div>
                <div class="col-span-2 text-neutral-300">
                    {{ formatDuration(s.time) }}
                </div>
                <div class="col-span-1 text-right text-xs text-neutral-500">
                    #{{ s.id }}
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import api from '../api'

const TYPE_LABELS = {
    card: 'Kártya játék',
    recognition: 'Képfelismerés',
    map: 'Térkép',
}

export default {
    name: 'Statistics',
    data() {
        return {
            id: this.$route.params.id,
            child: null,
            stats: [],
            filterType: 'all',
        }
    },
    created() {
        this.fetchChild()
        this.fetchStats()
    },
    computed: {
        sortedStats() {
            return [...this.stats].sort(
                (a, b) => new Date(b.created_at) - new Date(a.created_at)
            )
        },
        filteredStats() {
            if (this.filterType === 'all') {
                return this.sortedStats
            }

            return this.sortedStats.filter(s => s.type === this.filterType)
        },
        totalSessions() {
            return this.stats.length
        },
        lastSession() {
            return this.sortedStats[0] || null
        },
        averagePoints() {
            if (!this.stats.length) return 0
            const sum = this.stats.reduce((acc, s) => acc + (s.points || 0), 0)
            return sum / this.stats.length
        },
        averagePointsDisplay() {
            return this.stats.length ? this.formatPercent(this.averagePoints) : '–'
        },
        bestPoints() {
            if (!this.stats.length) return 0
            return this.stats.reduce((max, s) => Math.max(max, s.points || 0), 0)
        },
        bestPointsDisplay() {
            return this.stats.length ? this.formatPercent(this.bestPoints) : '–'
        },
        averageTime() {
            if (!this.stats.length) return 0
            const sum = this.stats.reduce((acc, s) => acc + (s.time || 0), 0)
            return sum / this.stats.length
        },
        averageTimeDisplay() {
            return this.stats.length ? this.formatDuration(this.averageTime) : '–'
        },
        perType() {
            const types = ['card', 'recognition', 'map']
            return types.map(type => {
                const list = this.stats.filter(s => s.type === type)
                const count = list.length
                let avgPoints = 0
                let avgTime = 0
                if (count) {
                    const sumPoints = list.reduce((acc, s) => acc + (s.points || 0), 0)
                    const sumTime = list.reduce((acc, s) => acc + (s.time || 0), 0)
                    avgPoints = sumPoints / count
                    avgTime = sumTime / count
                }
                return {
                    type,
                    label: TYPE_LABELS[type],
                    count,
                    avgPoints,
                    avgTime,
                }
            })
        },
    },
    methods: {
        async fetchChild() {
            if (!this.id) return
            const { data } = await api.get(`/api/child/${this.id}`)
            this.child = data
        },
        async fetchStats() {
            if (!this.id) return
            const { data } = await api.get(`/api/statistics/${this.id}`)
            this.stats = Array.isArray(data) ? data : []
        },
        typeLabel(type) {
            return TYPE_LABELS[type] || type
        },
        formatPercent(value) {
            if (value === null || value === undefined || isNaN(value)) return '–'
            const n = Math.round(Number(value))
            return `${n}`
        },
        formatDuration(seconds) {
            const s = Math.round(Number(seconds) || 0)
            const m = Math.floor(s / 60)
            const rest = s % 60
            if (!m) {
                return `${rest} mp`
            }
            return `${m} p ${String(rest).padStart(2, '0')} mp`
        },
        formatDateTime(isoString) {
            if (!isoString) return ''
            const d = new Date(isoString)
            return d.toLocaleString('hu-HU', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
            })
        },
        cancel() {
            this.$router.push({ name: 'children' })
        },
    },
}
</script>

<style scoped>
</style>
