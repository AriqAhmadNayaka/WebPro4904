import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";

// https://vite.dev/config/
export default defineConfig({
  base: "./",
  plugins: [react()],
  server: {
    proxy: {
      "/api": {
        target: "http://localhost",
        changeOrigin: true,
        rewrite: (path) =>
          path.replace(/^\/api/, "WebPro4904/Pekan-12/backend-ci3/api"),
      },
    },
  },
});
