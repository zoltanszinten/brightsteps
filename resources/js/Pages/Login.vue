<template>
    <section class="container mx-auto px-4 py-12">
        <div class="mx-auto max-w-xl">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold tracking-widest text-amber-400">Bejelentkezés</h1>
                <p class="mt-2 tracking-wide text-slate-300">Lépj be a gyakorló feladatokhoz</p>
            </div>

            <form class="space-y-5 rounded-3xl p-8 shadow-sm border bg-neutral-900 border-amber-400">
                <div>
                    <label for="email" class="block text-base font-semibold mb-2 tracking-wide text-amber-400">E-mail</label>
                    <input id="email" type="email" inputmode="email"
                           class="w-full rounded-2xl px-4 py-4 border focus:outline-none focus:ring border-amber-400 text-slate-100 focus:ring-slate-600"
                           style="min-height:56px" />
                </div>

                <div>
                    <label for="password" class="block text-base font-semibold mb-2 tracking-wide text-amber-400">Jelszó</label>
                    <input id="password" type="password"
                           class="w-full rounded-2xl px-4 py-4 border focus:outline-none focus:ring border-amber-400 text-slate-100 focus:ring-slate-600"
                           style="min-height:56px" />
                </div>

                <button type="button"
                        class="w-full rounded-2xl px-4 py-4 font-bold tracking-wider border hover:bg-slate-900 border-amber-400 text-amber-400"
                        style="min-height:56px">
                    Belépés
                </button>
            </form>
        </div>
    </section>
</template>
<script setup lang="ts">
</script>
