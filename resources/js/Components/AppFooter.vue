<template>
    <footer class="border-t border-neutral-800 bg-neutral-950">
        <div class="container mx-auto px-3 py-3 text-sm text-neutral-300 flex items-center justify-between gap-3">
            <p>© {{ year }} BrightSteps</p>
            <div class="flex items-center gap-4">
                <button class="px-4 py-3 rounded-2xl border text-amber-400 border-amber-400 hover:bg-slate-900">Gyors segítség</button>
            </div>
        </div>
    </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>
