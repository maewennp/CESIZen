<!-- src/views/FavoritesView.vue -->
<template>
  <v-container class="favorites-background py-10 px-4">
    <h1 class="text-h4 text-center mb-8">Mes favoris</h1>

    <v-row justify="center" align="center" dense>
      <v-col
        v-for="item in favoriteContents"
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

    <div v-if="favoriteContents.length === 0" class="text-center mt-10 text-grey">
      Aucun favori pour le moment 😔
    </div>
  </v-container>
</template>

<script setup lang="ts">
import ContentCard from '@/components/ContentCard.vue'
import { useFavoritesStore } from '@/stores/useFavoritesStore'
import { useRouter } from 'vue-router'
import { computed, onMounted, ref } from 'vue'
import { relaxActivityService } from '@/api/services/relaxActivityService'
import type { RelaxActivity } from '@/api/interfaces/RelaxActivity'

const favoritesStore = useFavoritesStore()
const router = useRouter()

const activities = ref<RelaxActivity[]>([])
const loading = ref(false)
const error = ref('')

onMounted(async () => {
  loading.value = true
  try {
    activities.value = await relaxActivityService.getAllActive()
  } catch (e: any) {
    error.value = e.message || 'Erreur lors du chargement des activités'
  } finally {
    loading.value = false
  }
})

// Filtrer uniquement les activités en favoris
const favoriteContents = computed(() =>
  activities.value
    .filter(act => favoritesStore.favoriteIds.includes(act.id_activity))
    .map(act => ({
      id: act.id_activity,
      title: act.activity_label,
      description: act.content,
      image: act.media_activity || '/assets/images/zen.jpg',
    }))
)

const goToDetails = (id: number) => {
  router.push(`/relaxation/${id}`)
}
</script>

<style scoped>
.favorites-background {
  background-image: url('@/assets/images/background.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  min-height: 100vh;
}
</style>
