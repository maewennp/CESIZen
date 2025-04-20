import './assets/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'
import { fr } from 'vuetify/locale'

// App
import App from './App.vue'
import router from './router'

// Vuetify config (charte CESIZen)
const vuetify = createVuetify({
  components,
  directives,
  locale: {
    locale: 'fr',
    messages: { fr },
  },
  icons: {
    defaultSet: 'mdi',
  },
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        colors: {
          primary: '#B8F1D8',       // Vert pastel clair
          secondary: '#FFE9C4',     // Jaune doux
          accent: '#000000',        // Noir pour les textes
          info: '#D0F6FF',          // Bleu ciel pâle (états d'info)
          background: '#FEFBFB',    // Blanc cassé
          surface: '#F8F8F8',       // Gris très clair
          muted: '#EFF2E8',         // Gris doux vert (textes secondaires)
          error: '#d32f2f',
          success: '#4CAF50',
          warning: '#FFC107',
        },
      },
    },
  },
})

// Initialisation de l'app Vue
const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(vuetify)
app.mount('#app')

// Enregistrement du service worker (PWA)
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
  })
}
