// import { defineConfig } from 'vite'
// import react from '@vitejs/plugin-react'

// // https://vite.dev/config/
// export default defineConfig({
//   base: './',
//   plugins: [react()],
//   server: {
//     proxy: {
//       '/ci3_project': {
//         target: 'http://localhost',
//         changeOrigin: true,
//       },
//     },
//   },
// })

import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  base: '/Pemrograman_Web/WebPro4904/react-blog-main/dist/',
  plugins: [react()],
  server: {
    proxy: {
      '/Pemrograman_Web/WebPro4904/Pekan-09': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})