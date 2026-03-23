import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/',                component: () => import('../Pages/HomePage.vue') },
        { path: '/admin/register',  component: () => import('../Pages/Admin/RegisterPage.vue') },
        { path: '/login',           component: () => import('../Pages/Admin/LoginPage.vue') },
        { path: '/:pathMatch(.*)*', redirect: '/' },
    ],
})

export default router