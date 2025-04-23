<template>
  <v-app-bar
    flat
    class="custom-header px-4"
    height="100"
    :class="[{ 
    'header-hidden': !isVisible && isMobile, 
    'header-opaque': isOpaque || !isMobile 
  }]"
    transition="slide-y-transition"
  >
    <!-- LOGO + TEXTE -->
    <div class="d-flex align-center">
      <v-avatar size="100" class="logo-avatar mr-3">
        <v-img src="/logo.png" alt="Logo CESIZen" cover />
      </v-avatar>
      <span class="logo-title">CESIZen</span>
    </div>

    <v-spacer />

    <!-- ICONES DROITES -->
    <div class="d-flex align-center gap-2">
      <v-btn icon @click="$emit('login')">
        <v-icon>mdi-account</v-icon>
      </v-btn>
      <v-btn icon>
        <v-icon>mdi-menu</v-icon>
      </v-btn>
    </div>
  </v-app-bar>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'

const isVisible = ref(true)
const isOpaque = ref(false)
let lastScroll = window.scrollY

const isMobile = computed(() => window.innerWidth < 960)

const handleScroll = () => {
  if (isMobile.value) {
    const current = window.scrollY
    if (current > lastScroll && current > 80) {
      // Scroll vers le bas, cache le header
      isVisible.value = false
    } else if (current < lastScroll) {
      // Scroll vers le haut, montre le header et rends-le opaque
      isVisible.value = true
      isOpaque.value = current > 30
    }
    lastScroll = current
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
.custom-header {
  background: transparent;
  height: 100px;
  transition: background 0.3s, transform 0.3s, opacity 0.25s;
  will-change: transform, background, opacity;
  z-index: 100;
}

/* Desktop - Header toujours visible */
@media (min-width: 960px) {
  .header-opaque {
    background: rgba(255,233,196,0.98) !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.25);
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
  }
  
  .header-hidden {
    transform: none !important;
    opacity: 1 !important;
  }
}

/* Mobile - Comportement au scroll */
@media (max-width: 959px) {
  .header-opaque {
    background: rgba(255,233,196,0.98) !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.25);
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
  }

  .header-hidden {
    transform: translateY(-120%);
    opacity: 0;
  }
}

/*.header-opaque {
  background: rgba(255,233,196,0.98) !important;
  box-shadow: 0 2px 12px rgba(0,0,0,0.25);
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
}

.header-hidden {
  transform: translateY(-120%);
  opacity: 0;
  pointer-events: none;
} */

.logo-avatar {
  width: 100px !important;
  height: 100px !important;
  background-color: white;
  border: 3px solid #B8F1D8;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
}

.logo-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #35b995; 
}
</style>
