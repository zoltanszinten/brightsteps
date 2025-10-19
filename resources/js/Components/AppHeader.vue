<template>
    <header class="border-b border-neutral-800 bg-neutral-950/90 backdrop-blur">
        <div class="container mx-auto px-3 py-2 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xl font-extrabold tracking-wider">BrightSteps</span>
            </div>

            <nav v-if="showNav" class="hidden sm:flex items-center gap-2 text-sm">
            </nav>

            <div class="flex items-center gap-1">
                <button @click="$emit('set-size','md')" class="min-h-[48px] px-3 rounded-lg border text-amber-400 border-amber-400">A−</button>
                <button @click="$emit('set-size','lg')" class="min-h-[48px] px-3 rounded-lg border font-semibold text-amber-400 border-amber-400">A</button>
                <button @click="$emit('set-size','xl')" class="min-h-[48px] px-3 rounded-lg border font-bold text-amber-400 border-amber-400">A+</button>
            </div>
        </div>
    </header>
</template>

<script setup>
import { RouterLink } from 'vue-router'
defineProps({ size: String, showNav: { type: Boolean, default: true } })
defineEmits(['set-size'])
</script>
