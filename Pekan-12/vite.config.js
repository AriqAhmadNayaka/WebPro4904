import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// https://vite.dev/config/

export default defineConfig({

    base: './',

    plugins: [
        react(),
    ],

    server: {
        proxy: {

            '/Pemrograman_Web': {
                target: 'http://localhost',
                changeOrigin: true,
            },

        },
    },

});
