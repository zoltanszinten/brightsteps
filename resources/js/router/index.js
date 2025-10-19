import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/Pages/Login.vue'
import ChildSettings from "@/Pages/ChildSettings.vue";
import ChildrenManager from "@/Pages/ChildrenManager.vue";
import ChildStats from "@/Pages/ChildStats.vue";
import CardGame from "@/Pages/CardGame.vue";
import RecognitionGame from "@/Pages/RecognitionGame.vue";
import MapGame from "@/Pages/MapGame.vue";

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'login', component: Login, meta: { hideNav: true } },
        { path: '/settings', name: 'settings', component: ChildSettings, meta: { hideNav: false } },
        { path: '/children', name: 'children', component: ChildrenManager, meta: { hideNav: false } },
        { path: '/statistics', name: 'statistics', component: ChildStats, meta: { hideNav: false } },
        { path: '/game/cards', name: 'card-game', component: CardGame, meta: { hideNav: false } },
        { path: '/game/recognition', name: 'recognition-game', component: RecognitionGame, meta: { hideNav: false } },
        { path: '/game/map', name: 'map-game', component: MapGame, meta: { hideNav: false } },
    ],
})
