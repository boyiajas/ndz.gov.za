import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  build: {
    // Keep built JS/CSS where the Laravel blade shell expects them.
    outDir: '../public/assets',
    assetsDir: '.',
    emptyOutDir: false, // Don't wipe Laravel's index.php, .htaccess etc.
  },
  server: {
    port: 5173,
    watch: {
      // This repo carries a large static media set under frontend/public and a
      // growing dist folder. Ignore both to avoid exhausting file watchers.
      ignored: ['**/dist/**', '**/public/**'],
      usePolling: true,
      interval: 1000,
    },
  },
})
