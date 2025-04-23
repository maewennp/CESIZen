<template>
  <v-app>
    <v-main class="home-background">
      <HeaderPublic @login="goToLogin" />

      <!-- CONTENU PRINCIPAL -->
      <v-container class="py-10 px-4">
        <!-- INFOS : CARROUSEL -->
        <h1 class="text-h3 text-center mb-6">Informations</h1>

        <v-carousel
          v-model="currentSlide"  
          cycle
          height="500"
          hide-delimiters
          show-arrows="hover"
          interval="8000"
          class="custom-carousel"
        >
          <v-carousel-item
            v-for="(info, index) in contentPages"
            :key="info.id_content"
          >
            <div class="carousel-item-wrapper">
              <v-card class="mx-auto info-card" elevation="4">
                <div class="card-content">
                  <!-- Zone image fixe -->
                  <div class="image-container">
                    <v-img
                      :src="info.media_content"
                      alt="Image"
                      class="info-img"
                      cover
                    />
                  </div>

                  <!-- Zone texte -->
                  <div class="text-container">
                    <h3 class="text-h6 font-weight-bold mb-3">{{ info.content_label }}</h3>
                    <div class="text-body-2 text-justify text-limited">
                      {{ info.body }}
                    </div>
                  </div>
                </div>
              </v-card>
            </div>
          </v-carousel-item>
        </v-carousel>
        <!-- Pagination manuelle style Insta -->
        <div class="pagination-dots mt-2 mb-6 d-flex justify-center">
          <span
            v-for="(info, index) in contentPages"
            :key="index"
            :class="['dot', { active: index === currentSlide }]"
            @click="currentSlide = index"
          />
        </div>
      </v-container>

      <v-container class="py-1 px-4 mb-12">
        <!-- MODULES -->
        <h2 class="text-h4 text-center mb-6">Modules proposés</h2>
        <v-row justify="center" align="center" class="modules-grid" dense>
          <v-col
            v-for="module in modules"
            :key="module.label"
            cols="6"
            sm="6"
            md="3"
            class="d-flex justify-center"
          >
            <v-card
              class="module-card d-flex align-center justify-center text-center"
              elevation="6"
              @click="goToModule(module.route)"
              ripple
            >
            <div class="d-flex flex-column align-center justify-center">
              <v-icon :icon="module.icon" size="36" color="darkprimary" class="mb-2 module-icon" />
              <span class="text-body-1 font-weight-bold">{{ module.label }}</span>
            </div>
            </v-card>
          </v-col>
        </v-row>
    </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import HeaderPublic from '@/components/HeaderPublic.vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const currentSlide = ref(0)

const goToLogin = () => {
  router.push('/login')
}

// ===================== INFOS - CARROUSEL =====================================

const contentPages = [
  {
    id_content: 1,
    content_label: "L'importance de la santé mentale",
    body: 'La santé mentale est primordiale car elle sous-tend notre bien-être global, notre résilience face aux défis et la qualité de nos relations.',
    media_content: new URL('@/assets/images/info/info0.png', import.meta.url).href,
    visible: true,
    created_at: '2025-04-19',
  },
  {
    id_content: 2,
    content_label: 'Les bienfaits de la respiration',
    body: 'La respiration profonde permet de réduire le stress et de recentrer votre attention.',
    media_content: new URL('@/assets/images/info/info1.jpg', import.meta.url).href,
    visible: true,
    created_at: '2025-04-22',
  },
  {
    id_content: 3,
    content_label: 'Relaxation guidée',
    body: 'Découvrez nos contenus audio pour vous détendre à tout moment de la journée.',
    media_content: new URL('@/assets/images/info/info2.jpg', import.meta.url).href,
    visible: true,
    created_at: '2025-04-20',
  },
]


// ========================== MODULES ==================================
const goToModule = (route: string) => {
  router.push(route)
}

const modules = [
  { label: 'Activités de relaxation', route: '/relaxation', icon: 'mdi-spa' },
  { label: 'Exercice de respiration', route: '/breathing', icon: 'mdi-weather-windy' },
  { label: 'Diagnostic de stress', route: '/diagnostic', icon: 'mdi-heart-pulse' },
  { label: "Tracker d'émotion", route: '/tracker', icon: 'mdi-emoticon-outline' },
]
</script>

<style scoped>
.home-background {
  background-image: url('@/assets/images/background.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  min-height: 100vh;
}

.custom-carousel {
  max-width: 800px;
  margin: 0 auto;
}

/* Pour que les point de pagination n'apparaissent pas au dessus du text */
.carousel-item-wrapper { 
  height: 100%;
  padding: 16px;
}

.info-card {
  height: 100%;
  background-color: rgba(255, 255, 255, 0.95);
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  
}

.card-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 16px;
}
.image-container {
  width: 100%;
  height: 280px; /* Taille fixe pour l'image */
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
  border-radius: 12px;
}

.info-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.text-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 16px 10px;
}

.text-limited {
  display: -webkit-box;
  -webkit-line-clamp: 3; /* Nombre de lignes avant troncature */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.4;
  max-height: 4.2em; /* 3 lignes * 1.4 line-height */
}

.pagination-dots .dot {
  width: 10px;
  height: 10px;
  margin: 0 4px;
  background-color: rgba(0, 0, 0, 0.3);
  border-radius: 50%;
  transition: background-color 0.3s ease;
  cursor: pointer;
}

.pagination-dots .dot.active {
  background-color: #4e7c68; /* darkprimary */
}


.module-card {
  width: 100%;
  max-width: 200px;
  height: 120px;
  border-radius: 16px;
  padding: 16px;
  transition: transform 0.3s ease;
  cursor: pointer;
  background-color: #FFE9C4;
}

.module-card:hover {
  transform: translateY(-4px);
  background-color: #fae9cc;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

/* Animation douce des icones uniquement sur desktop */
@media (hover: hover) and (pointer: fine) {
  .module-icon {
    transition: transform 0.3s ease;
  }

  .module-card:hover .module-icon {
    transform: scale(1.2) rotate(-2deg);
  }
}

</style>
