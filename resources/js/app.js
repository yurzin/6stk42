import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue'
import router from './router'
import '../css/theme.css'
import '../css/card-row.css'

// Фоллбэк для dev-режима (когда PHP не работает)
window.__APP_CONFIG__ = window.__APP_CONFIG__ || {
    apiBase: '/api',
    locale: 'ru-RU',
    version: '1.0.0',
}

createApp(App).use(router).mount('#app')