import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
    plugins: [vue()],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: false,
        allowedHosts: true,
        cors: true,
        watch: {
            usePolling: true,
            interval: 100
        },
        proxy: {
            '/api': {
                target: 'http://nginx_6stk42:80',  // ← исправь на nginx, не php-fpm
                changeOrigin: true,
                secure: false,
            }
        }
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '~': path.resolve(__dirname, './')
        }
    },
    build: {
        outDir: 'public/dist',
        assetsDir: 'assets',
        sourcemap: true,
        minify: 'terser',
        terserOptions: {
            compress: { drop_console: true, drop_debugger: true }
        },
        rollupOptions: {
            input: {
                // Точка входа — HTML, не PHP
                main: path.resolve(__dirname, 'index.html')
            },
            output: {
                manualChunks: { vendor: ['vue'] }
            }
        }
    },
    optimizeDeps: {
        include: ['vue']
    }
})