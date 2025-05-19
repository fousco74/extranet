import defaultTheme from 'tailwindcss/defaultTheme'
import colors, { green, pink } from 'tailwindcss/colors'

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
        // Couleurs par défaut de Tailwind
        blue: colors.blue,
        green: colors.green,
        pink: colors.pink,
        purple: colors.purple,
        black: colors.black,
        white: colors.white,
        gray: colors.gray,
        pink: colors.pink,
        orange: colors.orange,
        red: colors.red,
        yellow: colors.yellow,
        teal: colors.teal,
        indigo: colors.indigo,
        emerald: colors.emerald,
        cyan: colors.cyan,
        rose: colors.rose,

        // Couleurs personnalisées
        pink_custom: '#E1877D',
        pink_white: '#618DB9',
        blue_custom: '#0E3151',
        blue_black: '#032A5F',
        blue_white: '#206FB7',
        purple_custom: '#695764',
        lightGray_custom: '#F5F5F5',
      },
      backgroundImage: {
        'gradient-to-custom': 'linear-gradient(to right, #E1877D, #0E3151)',
        'gradient-to-buttom': 'linear-gradient(to right, #E1877D, #E8B7B2)',
        'gradient-to-weather': 'linear-gradient(to right, #618DB9, #2C3F53)',
        'gradient-pink': 'linear-gradient(to right, #FCB6AE, #E1877D)',
        'gradient-blue': 'linear-gradient(to right, #618DB9, #206FB7)',
        'gradient-linear-4colors': 'linear-gradient(90deg, #E1877D 0%, #618DB9 32%, #3C6695 59%, #032A5F 100%)',
      },
      fontFamily: {
        sans: ['Noto Sans', ...defaultTheme.fontFamily.sans],
        display: ['Roboto', 'sans-serif'],
      },
      fontWeight: {
        regular: '400',
        bold: '700',
        semibold: '600',
        light: '300',
        extrabold: '800',
      },
    },
  },
  plugins: [],
}
