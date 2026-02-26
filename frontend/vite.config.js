import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  build: {
    // Output built assets directly into Laravel's public directory
    outDir: '../public',
    emptyOutDir: false, // Don't wipe Laravel's index.php, .htaccess etc.
  },
  server: {
    port: 5173,
  },
})
