<template>
    <div class="min-h-screen flex flex-col body-readable bg-neutral-950 text-neutral-100">
        <AppHeader/>
        <main class="flex-1">
            <router-view/>
        </main>
        <AppFooter/>
    </div>
</template>

<script setup>
import AppHeader from '@/Components/AppHeader.vue'
import AppFooter from '@/Components/AppFooter.vue'
import {useRoute} from 'vue-router'

const route = useRoute()
</script>
