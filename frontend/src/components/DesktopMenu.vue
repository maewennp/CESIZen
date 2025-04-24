<template>
  <v-navigation-drawer
    permanent
    class="desktop-menu"
    width="260"
  >
    <!-- Logo -->
    <div class="d-flex align-center px-4 py-6">
      <v-avatar size="64" class="logo-avatar mr-3">
        <v-img src="/logo.png" alt="Logo CESIZen" cover />
      </v-avatar>
      <span class="logo-title">CESIZen</span>
    </div>

    <!-- Menu Items -->
    <v-list nav dense>
      <v-list-item
        v-for="item in navItems"
        :key="item.label"
        :to="item.route"
        link
        class="nav-item"
      >
        <template #prepend>
          <v-icon color="darkprimary">{{ item.icon }}</v-icon>
        </template>
        <v-list-item-title>{{ item.label }}</v-list-item-title>
      </v-list-item>

      <!-- Divider + Modules -->
      <v-divider class="my-2" />

      <v-list-subheader class="text-uppercase font-weight-bold text-grey">Modules</v-list-subheader>

      <v-list-item
        v-for="module in moduleItems"
        :key="module.label"
        :to="module.route"
        link
        class="nav-item"
      >
        <template #prepend>
          <v-icon color="darkprimary">{{ module.icon }}</v-icon>
        </template>
        <v-list-item-title>{{ module.label }}</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script setup lang="ts">
const navItems = [
  { label: 'Accueil', route: '/', icon: 'mdi-home' },
  { label: 'Informations', route: '/informations', icon: 'mdi-information-outline' }
]

const moduleItems = [
  { label: 'Activité de relaxation', route: '/relaxation', icon: 'mdi-spa' },
  { label: 'Exercice de respiration', route: '/breathing', icon: 'mdi-weather-windy' },
  { label: 'Diagnostic de stress', route: '/diagnostic', icon: 'mdi-heart-pulse' },
  { label: "Tracker d'émotion", route: '/tracker', icon: 'mdi-emoticon-outline' },
]
</script>

<style scoped>
.desktop-menu {
  background-color: rgba(255, 246, 242, 0.95);
  backdrop-filter: blur(6px);
  border-right: 1px solid rgba(0, 0, 0, 0.05);
}

.logo-avatar {
  background-color: white;
  border: 2px solid #B8F1D8;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.logo-title {
  font-size: 1.6rem;
  font-weight: 700;
  color: #35b995;
}

.nav-item {
  border-radius: 8px;
  transition: background-color 0.2s;
}

.nav-item:hover {
  background-color: rgba(78, 124, 104, 0.08);
}
</style>
