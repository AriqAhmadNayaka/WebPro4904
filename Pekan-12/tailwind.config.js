/** @type {import('tailwindcss').Config} */
export default {
  // Daftar file yang dipindai Tailwind agar class utility yang dipakai ikut dibuat.
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
  // Mode dark disiapkan sesuai modul, walaupun tampilan utama praktikum memakai light mode.
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Palet primary mengikuti instruksi modul praktikum Pekan 12.
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
        },
      },
    },
  },
  // Plugin kosong karena modul hanya membutuhkan utility bawaan Tailwind.
  plugins: [],
}
