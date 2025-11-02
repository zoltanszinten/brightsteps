<template>
    <footer class="border-t border-neutral-800 bg-neutral-950">
        <div class="container mx-auto px-3 py-3 text-sm text-neutral-300 flex items-center justify-between gap-3">
            <p>© {{ year }} BrightSteps</p>
            <div class="flex items-center gap-4"></div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'AppFooter',
    data() {
        return {year: new Date().getFullYear()}
    },
}
</script>
