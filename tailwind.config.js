/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.{js,ts}",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  safelist: [
    'bg-sky-600',
  ],
  plugins: [],
}