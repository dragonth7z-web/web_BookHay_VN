import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS
                'resources/css/app.css',
                // Core bundle (loaded on every page)
                'resources/js/app.js',
                // Page-specific bundles
                'resources/js/auth/auth.js',
                'resources/js/checkout/checkout.js',
                'resources/js/checkout-success.js',
                'resources/js/checkout-failed.js',
                'resources/js/order-tracking.js',
                'resources/js/pages/faq.js',
                'resources/js/pages/contact.js',            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
