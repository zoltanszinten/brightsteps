<template>
    <section class="container mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">Gyerekek kezelése</h1>
            <button @click="createChild()"
                    class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400 hover:bg-neutral-900">
                Új gyerek
            </button>
        </header>

        <div class="rounded-3xl border border-neutral-800 bg-neutral-950 overflow-hidden">
            <div class="grid grid-cols-12 px-4 py-3 text-sm text-neutral-400 border-b border-neutral-800">
                <div class="col-span-4">Név</div>
                <div class="col-span-3">Létrehozva</div>
                <div class="col-span-5 text-right">Műveletek</div>
            </div>

            <div v-if="children.length === 0" class="px-4 py-6 text-neutral-300">
                Nincs gyerek felvéve.
            </div>

            <div
                v-for="c in children"
                :key="c.id"
                class="grid grid-cols-12 px-4 py-3 border-b border-neutral-900 items-center"
            >
                <div class="col-span-4 truncate">
                    <span class="font-semibold text-neutral-100">{{ c.name }}</span>
                </div>
                <div class="col-span-3 text-neutral-400">
                    {{ formatDate(c.created_at) }}
                </div>
                <div class="col-span-5">
                    <div class="flex justify-end gap-2 flex-wrap">
                        <button
                            @click="gotoStatistics(c.id)"
                            class="p-2 rounded-xl border text-amber-400 border-amber-400 hover:bg-neutral-900"
                            title="Statisztikák"
                        >
                            <span class="sr-only">Statisztikák</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path d="M5 21V10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M12 21V5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M19 21V13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                        <button
                            @click="gotoEdit(c.id)"
                            class="p-2 rounded-xl border text-amber-400 border-amber-400 hover:bg-neutral-900"
                            title="Beállítások"
                        >
                            <span class="sr-only">Beállítások</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path
                                    d="M4 20L5.5 15.5L15 6L18.5 9.5L9 19L4 20Z"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M14 6L17 3L21 7L18 10"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                        <button
                            @click="copyLink(c.id)"
                            class="p-2 rounded-xl border border-amber-400 text-amber-400 hover:bg-neutral-900"
                            title="Link megosztása"
                        >
                            <span class="sr-only">Link megosztása</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path
                                    d="M12 3V15"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M8 7L12 3L16 7"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M6 11V17C6 18.1046 6.89543 19 8 19H16C17.1046 19 18 18.1046 18 17V11"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                        <button
                            @click="removeChild(c.id)"
                            class="p-2 rounded-xl border border-red-600 text-red-400 hover:bg-neutral-900"
                            title="Törlés"
                        >
                            <span class="sr-only">Törlés</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                                <path
                                    d="M6 7H18"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M9 7V5C9 4.44772 9.44772 4 10 4H14C14.5523 4 15 4.44772 15 5V7"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M8 7L9 20H15L16 7"
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
        </div>

        <section class="mt-10">
            <h2 class="text-xl font-extrabold tracking-widest text-amber-400 mb-4">Képek kezelése</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="rounded-3xl border border-neutral-800 bg-neutral-950 p-4 flex flex-col gap-4">
                    <h3 class="text-lg font-semibold text-amber-400">Kártyajáték</h3>
                    <form @submit.prevent="uploadImage('card')" class="space-y-3">
                        <div>
                            <label class="block text-sm text-neutral-300 mb-1">Érték</label>
                            <input
                                v-model="newCardValue"
                                type="text"
                                class="w-full rounded-xl px-3 py-2 border border-neutral-700 bg-neutral-900 text-neutral-100"
                            />
                        </div>
                        <div>
                            <label class="block text-sm text-neutral-300 mb-1">Kép</label>

                            <label
                                class="cursor-pointer inline-flex items-center px-4 py-2 border-amber-400 text-amber-400 w-full rounded">
                                Válassz egy képet
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleFileChange('card', $event)"
                                    class="hidden"
                                />
                            </label>
                        </div>
                        <button
                            type="submit"
                            class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400 w-full text-sm hover:bg-neutral-900"
                        >
                            Feltöltés
                        </button>
                    </form>
                    <div class="border-t border-neutral-800 pt-3">
                        <h4 class="text-sm text-neutral-400 mb-2">Létező képek</h4>
                        <div v-if="imagesCard.length === 0" class="text-xs text-neutral-500">
                            Nincs kép feltöltve.
                        </div>
                        <div v-else class="space-y-2 max-h-64 overflow-y-auto">
                            <div
                                v-for="img in imagesCard"
                                :key="img.id"
                                class="flex items-center gap-3"
                            >
                                <div
                                    class="w-12 h-12 rounded-lg overflow-hidden border border-neutral-700 bg-neutral-900 flex-shrink-0">
                                    <img
                                        v-if="imageUrl(img.path)"
                                        :src="imageUrl(img.path)"
                                        alt=""
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-neutral-200 truncate">{{ img.value }}</div>
                                    <div class="text-[10px] text-neutral-500 truncate">{{ img.original_name }}</div>
                                </div>
                                <button
                                    @click="deleteImage('card', img)"
                                    class="p-1 rounded-lg border border-red-600 text-red-400 hover:bg-neutral-900"
                                    title="Törlés"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                                        <path d="M6 7H18" stroke="currentColor" stroke-width="2"
                                              stroke-linecap="round"/>
                                        <path
                                            d="M9 7V5C9 4.44772 9.44772 4 10 4H14C14.5523 4 15 4.44772 15 5V7"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                        />
                                        <path
                                            d="M8 7L9 20H15L16 7"
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
                </div>

                <div class="rounded-3xl border border-neutral-800 bg-neutral-950 p-4 flex flex-col gap-4">
                    <h3 class="text-lg font-semibold text-amber-400">Képfelismerés</h3>
                    <form @submit.prevent="uploadImage('recognition')" class="space-y-3">
                        <div>
                            <label class="block text-sm text-neutral-300 mb-1">Érték</label>
                            <input
                                v-model="newRecognitionValue"
                                type="text"
                                class="w-full rounded-xl px-3 py-2 border border-neutral-700 bg-neutral-900 text-neutral-100"
                            />
                        </div>
                        <div>
                            <label class="block text-sm text-neutral-300 mb-1">Kép</label>
                            <label
                                class="cursor-pointer inline-flex items-center px-4 py-2 border-amber-400 text-amber-400 w-full rounded">
                                Válassz egy képet
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleFileChange('recognition', $event)"
                                    class="hidden"
                                />
                            </label>
                        </div>
                        <button
                            type="submit"
                            class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400 w-full text-sm hover:bg-neutral-900"
                        >
                            Feltöltés
                        </button>
                    </form>
                    <div class="border-t border-neutral-800 pt-3 mb-4">
                        <h4 class="text-sm text-neutral-400 mb-2">Létező képek</h4>
                        <div v-if="imagesRecognition.length === 0" class="text-xs text-neutral-500">
                            Nincs kép feltöltve.
                        </div>
                        <div v-else class="space-y-2 max-h-64 overflow-y-auto">
                            <div
                                v-for="img in imagesRecognition"
                                :key="img.id"
                                class="flex items-center gap-3"
                            >
                                <div
                                    class="w-12 h-12 rounded-lg overflow-hidden border border-neutral-700 bg-neutral-900 flex-shrink-0">
                                    <img
                                        v-if="imageUrl(img.path)"
                                        :src="imageUrl(img.path)"
                                        alt=""
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-neutral-200 truncate">{{ img.value }}</div>
                                    <div class="text-[10px] text-neutral-500 truncate">{{ img.original_name }}</div>
                                </div>
                                <button
                                    @click="deleteImage('recognition', img)"
                                    class="p-1 rounded-lg border border-red-600 text-red-400 hover:bg-neutral-900"
                                    title="Törlés"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                                        <path d="M6 7H18" stroke="currentColor" stroke-width="2"
                                              stroke-linecap="round"/>
                                        <path
                                            d="M9 7V5C9 4.44772 9.44772 4 10 4H14C14.5523 4 15 4.44772 15 5V7"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                        />
                                        <path
                                            d="M8 7L9 20H15L16 7"
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

                    <div class="border-t border-neutral-800 pt-3">
                        <h4 class="text-sm text-neutral-400 mb-2">Hamis értékek</h4>
                        <form @submit.prevent="createFalseValue" class="flex gap-2 mb-3">
                            <input
                                v-model="newFalseValue"
                                type="text"
                                placeholder="Új hamis érték"
                                class="flex-1 rounded-xl px-3 py-2 border border-neutral-700 bg-neutral-900 text-neutral-100 text-sm"
                            />
                            <button
                                type="submit"
                                class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400 text-sm hover:bg-neutral-900"
                            >
                                +
                            </button>
                        </form>
                        <div v-if="falseValues.length === 0" class="text-xs text-neutral-500">
                            Nincs hamis érték felvéve.
                        </div>
                        <div v-else class="space-y-1 max-h-40 overflow-y-auto">
                            <div
                                v-for="fv in falseValues"
                                :key="fv.id"
                                class="flex items-center justify-between gap-2 text-xs text-neutral-200 bg-neutral-900 border border-neutral-800 rounded-lg px-2 py-1"
                            >
                                <span class="truncate">{{ fv.value }}</span>
                                <button
                                    @click="deleteFalseValue(fv)"
                                    class="p-1 rounded-lg border border-red-600 text-red-400 hover:bg-neutral-800"
                                    title="Törlés"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24">
                                        <path d="M6 7H18" stroke="currentColor" stroke-width="2"
                                              stroke-linecap="round"/>
                                        <path
                                            d="M9 7V5C9 4.44772 9.44772 4 10 4H14C14.5523 4 15 4.44772 15 5V7"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                        />
                                        <path
                                            d="M8 7L9 20H15L16 7"
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
                </div>

                <div class="rounded-3xl border border-neutral-800 bg-neutral-950 p-4 flex flex-col gap-4">
                    <h3 class="text-lg font-semibold text-amber-400">Térkép</h3>
                    <form @submit.prevent="uploadImage('map')" class="space-y-3">
                        <div>
                            <label class="block text-sm text-neutral-300 mb-1">Érték</label>
                            <input
                                v-model="newMapValue"
                                type="text"
                                class="w-full rounded-xl px-3 py-2 border border-neutral-700 bg-neutral-900 text-neutral-100"
                            />
                        </div>
                        <div>
                            <label class="block text-sm text-neutral-300 mb-1">Kép</label>
                            <label
                                class="cursor-pointer inline-flex items-center px-4 py-2 border-amber-400 text-amber-400 w-full rounded">
                                Válassz egy képet
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleFileChange('map', $event)"
                                    class="hidden"
                                />
                            </label>
                        </div>
                        <button
                            type="submit"
                            class="px-3 py-2 rounded-xl border border-amber-400 text-amber-400 w-full text-sm hover:bg-neutral-900"
                        >
                            Feltöltés
                        </button>
                    </form>
                    <div class="border-t border-neutral-800 pt-3">
                        <h4 class="text-sm text-neutral-400 mb-2">Létező képek</h4>
                        <div v-if="imagesMap.length === 0" class="text-xs text-neutral-500">
                            Nincs kép feltöltve.
                        </div>
                        <div v-else class="space-y-2 max-h-64 overflow-y-auto">
                            <div
                                v-for="img in imagesMap"
                                :key="img.id"
                                class="flex items-center gap-3"
                            >
                                <div
                                    class="w-12 h-12 rounded-lg overflow-hidden border border-neutral-700 bg-neutral-900 flex-shrink-0">
                                    <img
                                        v-if="imageUrl(img.path)"
                                        :src="imageUrl(img.path)"
                                        alt=""
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-neutral-200 truncate">{{ img.value }}</div>
                                    <div class="text-[10px] text-neutral-500 truncate">{{ img.original_name }}</div>
                                </div>
                                <button
                                    @click="deleteImage('map', img)"
                                    class="p-1 rounded-lg border border-red-600 text-red-400 hover:bg-neutral-900"
                                    title="Törlés"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                                        <path d="M6 7H18" stroke="currentColor" stroke-width="2"
                                              stroke-linecap="round"/>
                                        <path
                                            d="M9 7V5C9 4.44772 9.44772 4 10 4H14C14.5523 4 15 4.44772 15 5V7"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                        />
                                        <path
                                            d="M8 7L9 20H15L16 7"
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
                </div>
            </div>
        </section>
    </section>
</template>

<script>
import api from '../api'

const dateFormatter = new Intl.DateTimeFormat('hu-HU', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
})

export default {
    name: 'ChildrenManager',
    data() {
        return {
            children: [],
            imagesCard: [],
            imagesRecognition: [],
            imagesMap: [],
            falseValues: [],
            newCardValue: '',
            newRecognitionValue: '',
            newMapValue: '',
            newFalseValue: '',
            newCardFile: null,
            newRecognitionFile: null,
            newMapFile: null,
        }
    },
    mounted() {
        this.fetchChildren()
        this.fetchImages('card')
        this.fetchImages('recognition')
        this.fetchImages('map')
        this.fetchFalseValues()
    },
    methods: {
        async fetchChildren() {
            const {data} = await api.get('/api/children')
            this.children = data
        },
        formatDate(value) {
            if (!value) {
                return ''
            }
            const date = new Date(value)
            if (Number.isNaN(date.getTime())) {
                return String(value)
            }
            return dateFormatter.format(date)
        },
        gotoStatistics(id) {
            this.$router.push({name: 'statistics', params: {id}})
        },
        gotoEdit(id) {
            this.$router.push({name: 'child-edit', params: {id}})
        },
        createChild() {
            this.$router.push({name: 'child-new'})
        },
        async copyLink(id) {
            const {data} = await api.get(`/api/token/${id}`)
            const token = data.token
            if (!token) {
                return
            }
            const url = `${window.location.origin}/?token=${encodeURIComponent(token)}`
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: 'BrightSteps',
                        text: 'Megosztott játék link.',
                        url,
                    })
                } catch (e) {
                }
            } else if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(url)
            }
        },
        async removeChild(id) {
            await api.delete(`/api/child/${id}`)
            this.children = this.children.filter(c => c.id !== id)
        },
        handleFileChange(type, event) {
            const file = event.target.files && event.target.files[0] ? event.target.files[0] : null
            if (type === 'card') {
                this.newCardFile = file
            } else if (type === 'recognition') {
                this.newRecognitionFile = file
            } else if (type === 'map') {
                this.newMapFile = file
            }
        },
        async fetchImages(type) {
            const {data} = await api.get('/api/images', {params: {type}})
            if (type === 'card') {
                this.imagesCard = data
            } else if (type === 'recognition') {
                this.imagesRecognition = data
            } else if (type === 'map') {
                this.imagesMap = data
            }
        },
        async uploadImage(type) {
            let value
            let file
            if (type === 'card') {
                value = this.newCardValue
                file = this.newCardFile
                if (!value || !file) {
                    return
                }
            } else if (type === 'recognition') {
                value = this.newRecognitionValue
                file = this.newRecognitionFile
                if (!value || !file) {
                    return
                }
            } else if (type === 'map') {
                value = this.newMapValue
                file = this.newMapFile
                if (!value || !file) {
                    return
                }
            }

            const formData = new FormData()
            formData.append('type', type)
            formData.append('value', value)
            formData.append('image', file)

            try {
                const {data} = await api.post('/api/images', formData, {
                    headers: {'Content-Type': 'multipart/form-data'},
                })
                if (type === 'card') {
                    this.imagesCard.unshift(data)
                    this.newCardValue = ''
                    this.newCardFile = null
                } else if (type === 'recognition') {
                    this.imagesRecognition.unshift(data)
                    this.newRecognitionValue = ''
                    this.newRecognitionFile = null
                } else if (type === 'map') {
                    this.imagesMap.unshift(data)
                    this.newMapValue = ''
                    this.newMapFile = null
                }
            } catch (e) {
            }
        },
        async deleteImage(type, image) {
            await api.delete(`/api/images/${image.id}`)
            if (type === 'card') {
                this.imagesCard = this.imagesCard.filter(i => i.id !== image.id)
            } else if (type === 'recognition') {
                this.imagesRecognition = this.imagesRecognition.filter(i => i.id !== image.id)
            } else if (type === 'map') {
                this.imagesMap = this.imagesMap.filter(i => i.id !== image.id)
            }
        },
        imageUrl(path) {
            return `${window.location.origin}/storage/${path}`
        },
        async fetchFalseValues() {
            const {data} = await api.get('/api/false-values')
            this.falseValues = data
        },
        async createFalseValue() {
            if (!this.newFalseValue) {
                return
            }
            try {
                const {data} = await api.post('/api/false-values', {
                    value: this.newFalseValue,
                })
                this.falseValues.push(data)
                this.newFalseValue = ''
            } catch (e) {
            }
        },
        async deleteFalseValue(item) {
            await api.delete(`/api/false-values/${item.id}`)
            this.falseValues = this.falseValues.filter(v => v.id !== item.id)
        },
    },
}
</script>

<style scoped>
</style>
