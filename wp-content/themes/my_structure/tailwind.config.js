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
        nunitoRegular: ['Nunito', 'sans-serif'],
        nunitoBold: ['Nunito', 'sans-serif'],
        nunitoSansRegular: ['Nunito Sans', 'sans-serif'],
        nunitoSansLight: ['Nunito Sans', 'sans-serif'],
      },
      colors: {
        'custom-green': '#84CE59',
        'custom-dark-green': '#45752c',
        'custom-light-green': '#E8FCCF',
        'custom-ink': '#122018',
        'custom-sand': '#F5F1E8',
        'custom-clay': '#D8C8AE',
        'custom-stone': '#697261',
        'custom-forest': '#2F4A2D',
      },
      boxShadow: {
        soft: '0 18px 45px rgba(18, 32, 24, 0.08)',
        card: '0 22px 60px rgba(18, 32, 24, 0.12)',
      },
      borderRadius: {
        '4xl': '2rem',
      },
      animation: {
        blink: 'blink 1s step-end infinite',
        slideInRight: 'slideInRight 0.5s ease-out forwards',
        fadeInUp: 'fadeInUp 0.6s ease-out forwards',
        fadeIn: 'fadeIn 0.6s ease-out forwards'
      },
      keyframes: {
        slideInRight: {
          '0%': { opacity: 0, transform: 'translateX(20px)' },
          '100%': { opacity: 1, transform: 'translateX(0)' }
        },
        fadeInUp: {
          '0%': { opacity: 0, transform: 'translateY(20px)' },
          '100%': { opacity: 1, transform: 'translateY(0)' }
        },
        fadeIn: {
          '0%': { opacity: 0 },
          '100%': { opacity: 1 }
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('tailwind-scrollbar'),
  ],
}
