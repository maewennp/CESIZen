<template>
  <v-menu
    v-model="open"
    location="bottom end"
    offset-y
    transition="slide-y-transition"
  >
    <template #activator="{ props }">
      <v-btn icon v-bind="props">
        <v-icon>mdi-menu</v-icon>
      </v-btn>
    </template>

    <v-list class="menu-list">
      <v-list-item @click="goTo('/')">
        <div class="d-flex align-center">
          <v-icon icon="mdi-home" color="darkprimary" class="mr-2" />
          <span>Accueil</span>
        </div>
      </v-list-item>

      <v-list-item @click="goTo('/informations')">
        <div class="d-flex align-center">
          <v-icon icon="mdi-information-outline" color="darkprimary" class="mr-2" />
          <span>Informations</span>
        </div>
      </v-list-item>

      <!-- Divider + label -->
      <v-divider class="my-2" />
      <v-list-subheader class="text-uppercase text-subtitle-2 pl-4">Modules</v-list-subheader>

      <!-- Modules -->
      <v-list-item @click="goTo('/relaxation')">
        <div class="d-flex align-center">
          <v-icon icon="mdi-spa" color="darkprimary" class="mr-2" />
          <span>Activité de relaxation</span>
        </div>
      </v-list-item>

      <v-list-item @click="goTo('/breathing')">
        <div class="d-flex align-center">
          <v-icon icon="mdi-weather-windy" color="darkprimary" class="mr-2" />
          <span>Exercice de respiration</span>
        </div>
      </v-list-item>

      <v-list-item @click="goTo('/diagnostic')">
        <div class="d-flex align-center">
          <v-icon icon="mdi-heart-pulse" color="darkprimary" class="mr-2"/>
          <span>Diagnostic de stress</span>
        </div>
      </v-list-item>

      <v-list-item @click="goTo('/tracker')">
        <div class="d-flex align-center">
          <v-icon icon="mdi-emoticon-outline" color="darkprimary" class="mr-2" />
          <span>Tracker d'émotion</span>
        </div>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const open = ref(false)

const goTo = (route: string) => {
  open.value = false
  if (route.startsWith('#')) {
    const el = document.querySelector(route)
    el?.scrollIntoView({ behavior: 'smooth' })
  } else {
    router.push(route)
  }
}
</script>

<style scoped>
.menu-list {
  border-radius: 16px;
  background-color: rgba(255, 246, 231, 0.94);
  backdrop-filter: blur(6px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  padding: 4px 0;
}

.v-list-item-title {
  font-weight: 500;
  font-size: 0.95rem;
}

.custom-list-item {
  padding: 8px 16px;
}

.custom-list-item span {
  font-size: 0.95rem;
  font-weight: 500;
}
</style>
