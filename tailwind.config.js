/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Models/Page.php", // To scan classes potentially in JSON content? (Less likely but possible)
  ],
  theme: {
    extend: {
      colors: {
        'brand-primary': '#63ab45', // Gotur Green
        'brand-accent': '#f7921e',  // Gotur Orange
        'brand-dark': '#1d231f',    // Gotur Heading
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
        hand: ['"Just Another Hand"', 'cursive'],
      },
      aspectRatio: {
        '9/16': '9 / 16',
      }
    },
  },
  plugins: [],
}
