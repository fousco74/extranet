/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        pink: '#E1877D',
        pink_white: '#618DB9',
        blue: '#0E3151',
        blue_black: '#032A5F',
        blue_white: '#206FB7',
        purple: '#695764',
        white: '#FFFFFF',
        black: '#000000',
        lightGray: '#F5F5F5',
      },
      backgroundImage: {
        'gradient-to-custom': 'linear-gradient(to right, #E1877D, #0E3151)',
        'gradient-to-buttom': 'linear-gradient(to right, #E1877D, #E8B7B2)',
        'gradient-to-weather': 'linear-gradient(to right, #618DB9, #2C3F53)',
        'gradient-pink' : 'linear-gradient(to right, #FCB6AE, #E1877D)',
        'gradient-blue' : 'linear-gradient(to right, #618DB9, #206FB7)',
        'gradient-linear-4colors': 'linear-gradient(90deg, #E1877D 0%, #618DB9 32%, #3C6695 59%, #032A5F 100%)',

      },
      fontFamily: {
        sans: ['Noto Sans', 'sans-serif'], // Utilise "Noto Sans" comme police principale
        display: ['Roboto', 'sans-serif'], // Option secondaire pour des titres si nécessaire
      },
      fontWeight: {
        regular: '400', // Poids régulier
        bold: '700', // Poids gras
        semibold: '600', // Poids semi-gras
        light: '300', // Poids léger
        extrabold: '800', // Poids extra-gras
      },
    },
  },
  plugins: [],
};
