<template>
  <div>
    <!-- VERSION MOBILE & TABLETTE -->
    <v-app-bar
      v-if="!isDesktop"
      
      flat
      class="custom-header px-4"
      height="100"
      :class="[{ 'header-hidden': !isVisible, 'header-opaque': isOpaque }]"
      transition="slide-y-transition"
    >
      <!-- Logo + Nom -->
      <div class="d-flex align-center">
        <v-avatar size="100" class="logo-avatar mr-3">
          <v-img src="/logo.png" alt="Logo CESIZen" cover @click="goTo('/')" />
        </v-avatar>
        <span class="logo-title">CESIZen</span>
      </div>

      <v-spacer />
       <!-- Boutons à droite -->
      <div class="d-flex align-center gap-2">
        <v-btn icon @click="isLoggedIn ? goTo('/profile') : $emit('login')">
          <v-icon>{{ isLoggedIn ? 'mdi-account-circle' : 'mdi-account-circle-outline' }}</v-icon>
        </v-btn>
        <v-menu offset-y transition="slide-y-transition">
          <template #activator="{ props }">
            <v-btn icon v-bind="props">
              <v-icon>mdi-menu</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item class="d-flex align-center gap-2" @click="goTo('/')">
              <v-icon color="darkprimary" class="mr-2">mdi-home</v-icon>
              <span>Accueil</span>
            </v-list-item>
            <v-list-item @click="goTo('/informations')">
              <v-icon color="darkprimary" class="mr-2">mdi-information</v-icon>
              <span>Informations</span>
            </v-list-item>
            <v-divider class="my-2" />
            <v-list-subheader>MODULES</v-list-subheader>
            <v-list-item v-for="mod in filteredModules" :key="mod.route" @click="handleMenuItemClick(mod)">
              <v-icon color="darkprimary" class="mr-2">{{ mod.icon }}</v-icon>
              <span>{{ mod.label }}</span>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-app-bar>

    <!-- VERSION DESKTOP -->
    <v-navigation-drawer
      v-else
      app
      permanent
      class="desktop-drawer"
    >
      <div class="d-flex flex-column align-center py-4">
        <v-avatar size="100" class="logo-avatar mb-2">
          <v-img src="/logo.png" alt="Logo CESIZen" cover @click="goTo('/')" />
        </v-avatar>
        <span class="logo-title">CESIZen</span>
      </div>

      <v-divider class="my-2" />
      <v-list density="compact">
        <v-list-item 
          class="d-flex align-center gap-2" 
          @click="goTo('/')"
        >
          <v-icon color="darkprimary" class="mr-2 my-4">mdi-home</v-icon>
          <span>Accueil</span>
        </v-list-item>
        <v-list-item 
          class="d-flex align-center gap-2" 
          @click="goTo('/informations')"
        >
          <v-icon color="darkprimary" class="mr-2 my-4">mdi-information</v-icon>
          <span>Informations</span>
        </v-list-item>

        <v-divider class="my-2" />
        <v-list-subheader class="text-uppercase font-weight-bold text-grey">Modules</v-list-subheader>
        <v-list-item 
          v-for="mod in filteredModules" 
          :key="mod.route" 
          class="nav-item" 
          @click="handleMenuItemClick(mod)">
          <v-icon color="darkprimary" class="mr-2 my-3">{{ mod.icon }}</v-icon>
          <span>{{ mod.label }}</span>
        </v-list-item>
      </v-list>
      <v-divider class="my-2" />
      <v-list-item 
        class="d-flex align-center gap-2" 
        @click="isLoggedIn ? goTo('/profile') : $emit('login')"
      >
        <v-icon color="darkprimary" class="mr-2 my-4">
          {{ isLoggedIn ? 'mdi-account-circle' : 'mdi-account-circle-outline' }}
        </v-icon>
        <span>{{ isLoggedIn ? 'Mon compte' : 'Se connecter' }}</span>
      </v-list-item> 
        
    </v-navigation-drawer>
  </div>

  <ModuleComingSoon
    :open="isOpen"
    :moduleName="moduleName"
    @close="closeModal"
  />
  
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useDisplay } from 'vuetify'
import ModuleComingSoon from '@/components/ModuleComingSoon.vue'
import { useModuleComingSoon } from '@/stores/useModuleComingSoon'
import { useUserStore } from '@/stores/userStore'

const { mdAndUp } = useDisplay()
const router = useRouter()

const userStore = useUserStore()
const isLoggedIn = computed(() => !!userStore.token)

const isDesktop = computed(() => mdAndUp.value)

const { isOpen, moduleName, openModal, closeModal } = useModuleComingSoon()

const isVisible = ref(true)
const isOpaque = ref(false)
let lastScroll = window.scrollY

const handleScroll = () => {
  const current = window.scrollY
  if (!isDesktop.value) {
    if (current > lastScroll && current > 80) {
      isVisible.value = false
    } else if (current < lastScroll) {
      isVisible.value = true
      isOpaque.value = current > 30
    }
    lastScroll = current
  }
}

onMounted(() => window.addEventListener('scroll', handleScroll))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const goTo = (route: string) => {
  router.push(route)
}

const modules = [
  { label: 'Activité de relaxation', route: '/relax', icon: 'mdi-spa', available: true },
  { label: 'Exercice de respiration', route: '/breathing', icon: 'mdi-weather-windy', available: true },
  { label: 'Diagnostic de stress', route: '/diagnostic', icon: 'mdi-heart-pulse', available: false },
  { label: "Tracker d'émotion", route: '/tracker', icon: 'mdi-emoticon-outline', available: false},
]

// filtre les modules selon la connexion
const filteredModules = computed(() => {
  return modules.filter(mod => {
    if (mod.label === "Tracker d'émotion") {
      return isLoggedIn.value // Tracker seulement si connecté
    }
    return true // Les autres toujours visibles
  })
})

const handleMenuItemClick = (item: any) => {
  if (!item.available) {
    openModal(item.label)
  } else {
    goTo(item.route)
  }
  
}

</script>

<style scoped>
.custom-header {
  background: rgba(255, 246, 242, 0.98);
  height: 100px;
  transition: background 0.3s, transform 0.3s, opacity 0.25s;
  z-index: 100;
  box-shadow: 0 2px 12px rgba(0,0,0,0.25);
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
}

.header-opaque {
  background: rgba(255, 246, 242, 0.98);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.25);
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
}

.header-hidden {
  transform: translateY(-120%);
  opacity: 0;
}

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

.desktop-drawer {
  background: rgba(255, 246, 242);
  padding-top: 20px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.25);
}
</style>
