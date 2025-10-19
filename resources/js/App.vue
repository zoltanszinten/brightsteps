<template>
    <div class="min-h-screen flex flex-col body-readable bg-neutral-950 text-neutral-100">
        <AppHeader :showNav="!route.meta.hideNav" :size="size" @set-size="setSize" />
        <main class="flex-1"><router-view /></main>
        <AppFooter />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const size = ref('md') // md|lg|xl  (alap: md = 20px)

function applySize(s){
    const map = { md: '18px', lg: '22px', xl: '28px' }
    document.documentElement.style.setProperty('--font-base', map[s] || '20px')
}
function setSize(s){ size.value = s; applySize(s) }

onMounted(()=> applySize(size.value))
</script>
