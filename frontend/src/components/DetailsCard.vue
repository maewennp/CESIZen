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
            <v-btn color="darkprimary" @click="emitBack" variant="text">
              <v-icon start>mdi-arrow-left</v-icon>
              Retour
            </v-btn>

            <v-icon
              v-if="showFav"
              @click.stop="toggleFavorite"
              :color="isFavorite ? 'red' : 'grey'"
              size="24"
              class="cursor-pointer"
            >
              {{  isFavorite ? 'mdi-heart' : 'mdi-heart-outline' }}
            </v-icon>
          </v-card-actions>
          
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { computed, defineProps } from 'vue'
import { useRouter } from 'vue-router'
import { useDisplay } from 'vuetify'
import { useUserStore } from '@/stores/userStore'
import { useFavoritesStore } from '@/stores/useFavoritesStore'

// Props typées
interface Props {
  id: number
  title: string
  description: string
  image?: string
  defaultImage: string    // toujours défini via withDefaults
  showFavorite?: boolean   // toujours défini via withDefaults
}

// Déclaration des props + valeurs par défaut
const props = withDefaults(defineProps<Props>(), {
  defaultImage: '/assets/images/zen.jpg',
  showFavorite: false,
})

// Événement `back`
const emit = defineEmits<{
  (e: 'back'): void
}>()

// Stores Pinia typés
const userStore      = useUserStore()
const favoritesStore = useFavoritesStore()

// Computed pour favoris + affichage du cœur
const isFavorite = computed<boolean>(() =>
  favoritesStore.isFavorite(props.id)
)
const showFav = computed<boolean>(() =>
  Boolean(props.showFavorite) && userStore.isAuthenticated
)

const router = useRouter()

// pour afficher l'image en default (et non cover) quand on est en desktop
const { mdAndUp } = useDisplay()
const useCover = computed<boolean>(() => !mdAndUp.value)

// Fallback dynamique
const imageSrc = computed(() => props.image || props.defaultImage)
// const imageSrc = computed<string>(() =>
//   props.image ?? props.defaultImage ?? '/assets/images/zen.jpg'
// )

function emitBack(): void {
  emit('back')
}

function toggleFavorite(): void {
  favoritesStore.toggleFavorite(props.id)
}
</script>

<style scoped>
.details-card {
  border-radius: 16px;
  background-color: white;
}

</style>