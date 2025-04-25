<template>
  <v-app>
    <!-- Menu général (Mobile ou Desktop) -->
    <AppMenu @login="goToLogin" v-if="!hideMenu" />

    <!-- Zone principale -->
    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppMenu from '@/components/AppMenu.vue'

const route = useRoute()
const router = useRouter()

const goToLogin = () => {
  router.push('/login')
}

// Cacher le menu sur certaines pages
const hideMenu = computed(() => {
  return ['login', 'register'].includes(route.name as string)
})
</script>

<style scoped>
.v-application {
  background-color: transparent !important;
}
</style>
