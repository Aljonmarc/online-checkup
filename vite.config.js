import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        chunkSizeWarningLimit: 1500, // Raise the limit if needed
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('vue')) {
                            return 'vue'; // Separate Vue into its own chunk
                        }
                        if (id.includes('vue-router')) {
                            return 'vue-router'; // Separate vue-router
                        }
                        if (id.includes('axios')) {
                            return 'axios'; // Separate axios
                        }
                        return 'vendor'; // Remaining vendor dependencies
                    }
                },
            },
        },
    },
});
