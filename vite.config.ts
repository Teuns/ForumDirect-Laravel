import vue from '@vitejs/plugin-vue2';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import * as path from "node:path";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '~@bybas/latte-ui': path.resolve(__dirname, './node_modules/@bybas/latte-ui'),
            '~bootstrap': path.resolve(__dirname, './node_modules/bootstrap'),
        },
    },
});
