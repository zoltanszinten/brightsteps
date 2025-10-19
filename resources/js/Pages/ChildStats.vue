<template>
    <section class="container mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-extrabold tracking-widest text-amber-400">Statisztikák</h1>
                <p class="text-neutral-300">Gyermek: <span class="font-semibold text-neutral-100">Kis Feri</span></p>
            </div>
        </header>

        <!-- KPI cards -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Összes feladat</p>
                <p class="text-2xl font-bold text-neutral-100">15</p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Átlag pontszám</p>
                <p class="text-2xl font-bold text-neutral-100">82%</p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Pontosság</p>
                <p class="text-2xl font-bold text-neutral-100">88%</p>
            </div>
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm">Közepes reakcióidő</p>
                <p class="text-2xl font-bold text-neutral-100">1 p 20 mp</p>
            </div>
        </div>

        <!-- Game-type breakdown -->
        <div class="grid lg:grid-cols-3 gap-4 mb-6">
            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm mb-1">Kártya</p>
                <p class="text-xl font-bold text-neutral-100">7 alkalom</p>
                <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
                    <div><p class="text-neutral-400">Átlag</p><p class="font-semibold">85%</p></div>
                    <div><p class="text-neutral-400">Pontosság</p><p class="font-semibold">90%</p></div>
                    <div><p class="text-neutral-400">Idő</p><p class="font-semibold">1 p 10 mp</p></div>
                </div>
                <div class="mt-4">
                    <svg viewBox="0 0 100 30" class="w-full h-16">
                        <polyline fill="none" stroke="currentColor" stroke-width="2" points="0,20 20,15 40,18 60,10 80,12 100,8" class="text-amber-400"/>
                    </svg>
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm mb-1">Képfelismerés</p>
                <p class="text-xl font-bold text-neutral-100">5 alkalom</p>
                <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
                    <div><p class="text-neutral-400">Átlag</p><p class="font-semibold">78%</p></div>
                    <div><p class="text-neutral-400">Pontosság</p><p class="font-semibold">83%</p></div>
                    <div><p class="text-neutral-400">Idő</p><p class="font-semibold">1 p 35 mp</p></div>
                </div>
                <div class="mt-4">
                    <svg viewBox="0 0 100 30" class="w-full h-16">
                        <polyline fill="none" stroke="currentColor" stroke-width="2" points="0,22 20,24 40,20 60,18 80,16 100,14" class="text-amber-400"/>
                    </svg>
                </div>
            </div>

            <div class="rounded-2xl border border-neutral-800 bg-neutral-950 p-4">
                <p class="text-neutral-400 text-sm mb-1">Térkép</p>
                <p class="text-xl font-bold text-neutral-100">3 alkalom</p>
                <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
                    <div><p class="text-neutral-400">Átlag</p><p class="font-semibold">76%</p></div>
                    <div><p class="text-neutral-400">Pontosság</p><p class="font-semibold">81%</p></div>
                    <div><p class="text-neutral-400">Idő</p><p class="font-semibold">1 p 55 mp</p></div>
                </div>
                <div class="mt-4">
                    <svg viewBox="0 0 100 30" class="w-full h-16">
                        <polyline fill="none" stroke="currentColor" stroke-width="2" points="0,26 20,22 40,24 60,20 80,18 100,16" class="text-amber-400"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sessions table -->
        <div class="rounded-2xl border border-neutral-800 bg-neutral-950 overflow-hidden">
            <div class="px-4 py-3 border-b border-neutral-800 flex items-center gap-3">
                <label class="text-sm text-neutral-300">Szűrés:</label>
                <select class="rounded-xl px-2 py-2 border border-neutral-700 bg-neutral-900 text-neutral-200 text-sm">
                    <option>Összes</option>
                    <option>Kártya</option>
                    <option>Képfelismerés</option>
                    <option>Térkép</option>
                </select>
                <select class="rounded-xl px-2 py-2 border border-neutral-700 bg-neutral-900 text-neutral-200 text-sm">
                    <option>Utolsó 30 nap</option>
                    <option>Utolsó 7 nap</option>
                    <option>Mind</option>
                </select>
            </div>

            <div class="grid grid-cols-12 px-4 py-2 text-sm text-neutral-400 border-b border-neutral-800">
                <div class="col-span-3">Dátum</div>
                <div class="col-span-2">Modul</div>
                <div class="col-span-2">Pontszám</div>
                <div class="col-span-2">Pontosság</div>
                <div class="col-span-2">Idő</div>
                <div class="col-span-1 text-right">Részlet</div>
            </div>

            <!-- Static rows -->
            <div class="grid grid-cols-12 px-4 py-3 border-b border-neutral-900 items-center">
                <div class="col-span-3 text-neutral-300">2025.10.10. 14:32</div>
                <div class="col-span-2 text-neutral-300">Kártya</div>
                <div class="col-span-2 text-neutral-100 font-semibold">88%</div>
                <div class="col-span-2 text-neutral-300">92%</div>
                <div class="col-span-2 text-neutral-300">1 p 05 mp</div>
                <div class="col-span-1 text-right">
                    <button class="px-2 py-1 rounded-lg border border-neutral-700 text-neutral-200 text-xs">Részletek</button>
                </div>
            </div>

            <div class="grid grid-cols-12 px-4 py-3 border-b border-neutral-900 items-center">
                <div class="col-span-3 text-neutral-300">2025.10.08. 09:12</div>
                <div class="col-span-2 text-neutral-300">Képfelismerés</div>
                <div class="col-span-2 text-neutral-100 font-semibold">79%</div>
                <div class="col-span-2 text-neutral-300">84%</div>
                <div class="col-span-2 text-neutral-300">1 p 42 mp</div>
                <div class="col-span-1 text-right">
                    <button class="px-2 py-1 rounded-lg border border-neutral-700 text-neutral-200 text-xs">Részletek</button>
                </div>
            </div>

            <div class="grid grid-cols-12 px-4 py-3 items-center">
                <div class="col-span-3 text-neutral-300">2025.10.05. 16:20</div>
                <div class="col-span-2 text-neutral-300">Térkép</div>
                <div class="col-span-2 text-neutral-100 font-semibold">75%</div>
                <div class="col-span-2 text-neutral-300">80%</div>
                <div class="col-span-2 text-neutral-300">2 p 01 mp</div>
                <div class="col-span-1 text-right">
                    <button class="px-2 py-1 rounded-lg border border-neutral-700 text-neutral-200 text-xs">Részletek</button>
                </div>
            </div>
        </div>

        <p id="live" class="sr-only" role="status" aria-live="polite"></p>
    </section>
</template>

<script setup>
// intentionally empty: display-only
</script>
