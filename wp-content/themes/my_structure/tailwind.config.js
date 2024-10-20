/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/**/*.blade.php",
    "./source/assets/js/**/*.js",
    "./source/assets/scss/**/*.scss",
  ],
  theme: {
    extend: {
      fontFamily: {
        nunito: ['Nunito', 'sans-serif'],
        nunitoSans: ['Nunito Sans', 'sans-serif'],
      },
      colors: {
        'custom-green': '#84CE59',
        'custom-dark-green': '#45752c',
        'custom-light-green': '#E8FCCF',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}
