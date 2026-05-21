/** @type {import('tailwindcss').Config} */
export default {
  // Menentukan file mana saja yang akan dipindai oleh Tailwind CSS untuk class utility yang digunakan
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  // Mengaktifkan mode gelap berbasis class (misalnya penambahan class 'dark' pada elemen root)
  darkMode: 'class',
  theme: {
    extend: {
      // Menambahkan palet warna kustom untuk warna 'primary' (nuansa biru premium) dari tingkat 50 sampai 900
      colors: {
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
        }
      }
    },
  },
  plugins: [],
}
