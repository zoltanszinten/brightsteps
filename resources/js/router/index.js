import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/Pages/Login.vue'

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'login', component: Login, meta: { hideNav: true } },
    ],
})
