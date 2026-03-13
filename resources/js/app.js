import { createApp } from 'vue'
import HomePage from './Pages/HomePage.vue'

// Фоллбэк для dev-режима (когда PHP не работает)
window.__APP_CONFIG__ = window.__APP_CONFIG__ || {
    apiBase: '/api',
    locale: 'ru-RU',
    version: '1.0.0',
}

createApp(HomePage).mount('#app')