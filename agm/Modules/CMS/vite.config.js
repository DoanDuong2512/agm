import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fg from 'fast-glob';
import path from 'path';

export default defineConfig({
    server: {
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        hmr: {
            host: "localhost",
        },
        watch: {
            usePolling: true
        }
    },
    resolve: {
        alias: {
            "@tabler/icons": path.resolve(__dirname, "node_modules/@tabler/icons"),
        },
    },
    build: {
        outDir: path.resolve(__dirname, '../../public/build-cms'),
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: [
                ...fg.sync('resources/assets/js/**/*.js', { cwd: __dirname }),
                ...fg.sync('resources/assets/sass/**/*.scss', { cwd: __dirname })
            ],
            output: {
                // Đảm bảo không đặt file vào `.vite`
                assetFileNames: 'assets/[name]-[hash].[ext]'
            }
        },
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-cms',
            input: [
                ...fg.sync('resources/assets/js/**/*.js', { cwd: __dirname }),
                ...fg.sync('resources/assets/sass/**/*.scss', { cwd: __dirname })
            ],
            refresh: [
                'routes/**',
                'resources/views/**', // Theo dõi tất cả file Blade trong module
                'app/**',
                'Modules/CMS/resources/views/**', // Nếu Blade của CMS nằm trong Modules/CMS
            ],
        }),
    ],
});

//export const paths = [
//    'Modules/$STUDLY_NAME$/resources/assets/sass/app.scss',
//    'Modules/$STUDLY_NAME$/resources/assets/js/app.js',
//];
