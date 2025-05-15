<template>
  <div class="relaxation-background">
    <v-container class="py-10 px-4 text-center">
      <h1 class="text-h4 text-center mb-8">Activités de relaxation</h1>

      <p class="text-body-1 mb-6">
        <strong>Prenez un moment pour vous détendre.</strong><br>
          Cette section vous propose une sélection d’activités de relaxation conçues pour vous aider à relâcher la pression, apaiser votre esprit et recentrer vos émotions.
          Explorez, filtrez selon vos envies, et ajoutez vos préférées à vos favoris pour y revenir facilement.
      </p>

      <v-text-field
        v-model="search"
        label="Rechercher une activité..."
        prepend-inner-icon="mdi-magnify"
        variant="outlined"
        class="mb-6"
        hide-details
        clearable
      />

      <!-- Loader -->
      <v-progress-circular v-if="loading" indeterminate color="primary" class="my-8" />
      <!-- Erreur -->
      <v-alert v-if="error" type="error" dense class="mb-6">{{ error }}</v-alert>

      <!-- Liste des cards d'activité de relaxation -->
      <v-row justify="center" align="center" dense>
        <v-col
          v-for="item in filteredContents"
          :key="item.id"
          cols="12"
          sm="6"
          md="4"
          class="d-flex justify-center"
        >
          <ContentCard
            :id="item.id"
            :title="item.title"
            :description="item.description"
            :image="item.image"
            :showFavorite="true"
            @click="goToDetails(item.id)"
          />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script setup lang="ts">
import type { RelaxActivity } from '@/api/interfaces/RelaxActivity'
import { relaxActivityService } from '@/api/services/relaxActivityService'
import ContentCard from '@/components/ContentCard.vue'
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

interface ActivityCard {
  id: number
  title: string
  description: string
  image: string
}

const router = useRouter()
const search = ref<string>('')

const activities = ref<RelaxActivity[]>([])
const loading = ref<boolean>(false)
const error = ref<string>('')

onMounted(async () => {
  loading.value = true
  try {
    activities.value = await relaxActivityService.getAllActive()
  } catch (err: unknown) {
    if (err instanceof Error) {
      error.value = err.message
    } else {
      error.value = 'Erreur lors du chargement des activités'
    }
  } finally {
    loading.value = false
  }
})

// Mapping pour ContentCard
const mappedContents = computed<ActivityCard[]>(() =>
  activities.value.map(act => ({
    id: act.id_activity,
    title: act.activity_label,
    description: act.content,
    image: act.media_activity || '/assets/images/zen.jpg', // image par défaut si vide
  }))
)

// Filtrage avec la recherche
const filteredContents = computed<ActivityCard[]>(() => {
  const keyword = search.value.trim().toLowerCase()
  if (!keyword) return mappedContents.value

  return mappedContents.value.filter(item =>
    item.title.toLowerCase().includes(keyword) ||
    item.description.toLowerCase().includes(keyword)
  )
})

const goToDetails = (id: number) => {
  router.push(`/relax/${id}`)
}

</script>

<style scoped>

.relaxation-background {
  min-height: 100vh;
  background-image: url('@/assets/images/background.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

</style>