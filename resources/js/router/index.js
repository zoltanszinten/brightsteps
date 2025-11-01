// resources/js/router/index.js
import {createRouter, createWebHistory} from 'vue-router'
import Login from '@/Pages/Login.vue'
import ChildSettings from '@/Pages/ChildSettings.vue'
import ChildrenManager from '@/Pages/ChildrenManager.vue'
import ChildStats from '@/Pages/ChildStats.vue'
import CardGame from '@/Pages/CardGame.vue'
import RecognitionGame from '@/Pages/RecognitionGame.vue'
import MapGame from '@/Pages/MapGame.vue'

import api, {tokenKey, userKey} from '@/api' // adjust path

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {path: '/', name: 'login', component: Login, meta: {hideNav: true}},

        {path: '/settings', name: 'settings', component: ChildSettings, meta: {hideNav: false, requiresAuth: true}},
        {path: '/children', name: 'children', component: ChildrenManager, meta: {hideNav: false, requiresAuth: true}},
        {path: '/statistics', name: 'statistics', component: ChildStats, meta: {hideNav: false, requiresAuth: true}},
        {path: '/game/cards', name: 'card-game', component: CardGame, meta: {hideNav: false, requiresAuth: true}},
        {
            path: '/game/recognition',
            name: 'recognition-game',
            component: RecognitionGame,
            meta: {hideNav: false, requiresAuth: true}
        },
        {path: '/game/map', name: 'map-game', component: MapGame, meta: {hideNav: false, requiresAuth: true}},
    ],
})

router.beforeEach((to, from, next) => {
    const t = to.query.token
    if (!t) return next()

    localStorage.setItem(tokenKey, t)

    const {token, ...rest} = to.query
    next({path: to.path, query: rest, hash: to.hash, replace: true})
})
router.beforeEach(async (to, from, next) => {
    if (!to.meta.requiresAuth) return next()

    try {
        const {data} = await api.get('/api/user', {headers: {Accept: 'application/json'}})
        if (data?.user) {
            localStorage.setItem(userKey, JSON.stringify(data))
            return next()
        } else {
            localStorage.removeItem(tokenKey)
            localStorage.removeItem(userKey)
        }
    } catch {
        localStorage.removeItem(tokenKey)
        localStorage.removeItem(userKey)
    }

    return next({name: 'login', query: {redirect: to.fullPath}})
})

export default router
