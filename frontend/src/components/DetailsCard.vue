<template>
  <v-container class="py-10 px-4 details-container">
    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card elevation="6" class="details-card">
          <v-img :src="imageSrc" height="280" :cover="useCover" class="rounded-t" />

          <v-card-text class="pa-6">
            <h1 class="text-h5 font-weight-bold mb-4 text-darkprimary">
              {{ title }}
            </h1>
            <p class="text-body-1 text-justify">
              {{ description }}
            </p>
          </v-card-text>

          <v-card-actions class="d-flex justify-space-between px-4">
            <v-btn color="darkprimary" @click="goBack" variant="text">
              <v-icon start>mdi-arrow-left</v-icon>
              Retour
            </v-btn>

            <v-icon
              v-if="showFavorite && userStore.isAuthenticated"
              @click.stop="favoritesStore.toggleFavorite(props.id)"
              :color="favoritesStore.isFavorite(props.id) ? 'red' : 'grey'"
              size="24"
              class="cursor-pointer"
            >
              {{ favoritesStore.isFavorite(props.id) ? 'mdi-heart' : 'mdi-heart-outline' }}
            </v-icon>
          </v-card-actions>
          
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { computed, defineProps, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useDisplay } from 'vuetify'
import { useUserStore } from '@/stores/userStore'
import { useFavoritesStore } from '@/stores/useFavoritesStore'

const router = useRouter()
const userStore = useUserStore()
const favoritesStore = useFavoritesStore()

const props = defineProps({
  id: { type: Number, required: true },
  title: String,
  description: String,
  image: String,
  defaultImage: {
    type: String,
    default: '/assets/images/zen.jpg',
  },
  showFavorite: {
    type: Boolean,
    default: false,
  }
})

const isFavorite = ref(false)
const toggleFavorite = () => {
  isFavorite.value = !isFavorite.value
}

const goBack = () => {
  router.back()
}

// pour afficher l'image en default (et non cover) quand on est en desktop
const { mdAndUp } = useDisplay()
const useCover = computed(() => !mdAndUp.value) 

// Fallback dynamique
const imageSrc = computed(() => props.image || props.defaultImage)
</script>

<style scoped>
.details-card {
  border-radius: 16px;
  background-color: white;
}

</style>