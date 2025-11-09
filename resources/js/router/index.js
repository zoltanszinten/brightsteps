import {createRouter, createWebHistory} from 'vue-router'
import Login from '@/Pages/Login.vue'
import ChildrenManager from '@/Pages/ChildrenManager.vue'
import ChildStats from '@/Pages/ChildStats.vue'
import CardGame from '@/Pages/CardGame.vue'
import RecognitionGame from '@/Pages/RecognitionGame.vue'
import MapGame from '@/Pages/MapGame.vue'
import ChildForm from '@/Pages/ChildForm.vue'

import api, {tokenKey, userKey} from '@/api'
import {setLoggedIn} from "@/authState.js";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {path: '/', name: 'login', component: Login, meta: {hideNav: true}},

        {path: '/settings', name: 'settings', component: ChildForm, meta: {hideNav: false, requiresAuth: true}},
        {path: '/children', name: 'children', component: ChildrenManager, meta: {hideNav: false, requiresAuth: true}},
        {
            path: '/statistics/:id',
            name: 'statistics',
            component: ChildStats,
            meta: {hideNav: false, requiresAuth: true}
        },
        {path: '/game/cards', name: 'card-game', component: CardGame, meta: {hideNav: false, requiresAuth: true}},
        {
            path: '/game/recognition',
            name: 'recognition-game',
            component: RecognitionGame,
            meta: {hideNav: false, requiresAuth: true}
        },
        {path: '/game/map', name: 'map-game', component: MapGame, meta: {hideNav: false, requiresAuth: true}},
        {path: '/child', name: 'child-new', component: ChildForm, meta: {hideNav: false, requiresAuth: true}},
        {
            path: '/child/:id',
            name: 'child-edit',
            component: ChildForm,
            props: true,
            meta: {hideNav: false, requiresAuth: true}
        },
    ],
})

function getCachedUser() {
    try {
        return JSON.parse(localStorage.getItem(userKey) || null)
    } catch {
        return null
    }
}

router.beforeEach(async (to, from, next) => {
    const queryToken = typeof to.query.token === 'string' ? to.query.token : null;

    if (queryToken && queryToken !== localStorage.getItem(tokenKey)) {
        localStorage.setItem(tokenKey, queryToken);
        localStorage.removeItem(userKey);
    }

    let cached = getCachedUser();

    if (!cached && localStorage.getItem(tokenKey)) {
        try {
            const {data} = await api.get('/api/user', {
                headers: {Accept: 'application/json'},
            });
            if (data?.user) {
                localStorage.setItem(userKey, JSON.stringify(data));
                cached = data;
                setLoggedIn(true)
            } else {
                localStorage.removeItem(tokenKey);
                localStorage.removeItem(userKey);
                setLoggedIn(false)
            }
        } catch {
            localStorage.removeItem(tokenKey);
            localStorage.removeItem(userKey);
            setLoggedIn(false)
        }
    }

    if (queryToken) {
        const {token, ...rest} = to.query;
        if (cached?.user?.type === 'default') {
            return next({name: 'children', query: rest, replace: true});
        }
        if (cached?.user?.type) {
            return next({name: 'card-game', query: rest, replace: true});
        }
        return next({name: 'login', query: rest, replace: true});
    }

    if (to.meta.requiresAuth && (!localStorage.getItem(userKey) || !localStorage.getItem(tokenKey))) {
        return next({name: 'login', replace: true});
    }

    if (!to.meta.requiresAuth && to.name === 'login' && cached?.user?.type) {
        return next({
            name: cached.user.type === 'default' ? 'children' : 'card-game',
            replace: true,
        });
    }

    return next();
});

export default router
