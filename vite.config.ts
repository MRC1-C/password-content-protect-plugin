import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  build: {
    assetsDir: 'dist',
    rollupOptions: {
      output: {
        entryFileNames: 'index.js', // Đặt tên cho file JS đầu ra
        assetFileNames: 'index.[ext]' // Đặt tên cho các tệp khác (như CSS, hình ảnh, v.v.)
      }
    }
  },
  plugins: [react()]
})
