<template>
  <div class="relaxation-background">
  <v-container class="py-10 px-4">

    <!-- Indicateur de chargement -->
    <v-progress-circular
      v-if="loading"
      indeterminate
      color="primary"
      class="d-flex mx-auto"
    />

    <!-- Message d'erreur si besoin -->
    <v-alert
      v-else-if="error"
      type="error"
      variant="tonal"
      class="mb-4"
    >
      {{ error }}
    </v-alert>

    <!-- Détail de l'activité -->
    <DetailsCard
      v-else-if="selectedItem"
      :id="selectedItem.id_activity"
      :title="selectedItem.activity_label"
      :description="selectedItem.content"
      :image="selectedItem.media_activity"
      default-image="/assets/images/zen.jpg"
      :showFavorite="true"
      @back="goBack"
    />

    <v-alert v-else type="error" variant="tonal">
      Activité de relaxation non trouvée
    </v-alert>

  </v-container>
</div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DetailsCard from '@/components/DetailsCard.vue'
import { relaxActivityService } from '@/api/services/relaxActivityService'
import type { RelaxActivity } from '@/api/interfaces/RelaxActivity'

const route = useRoute()
const router = useRouter()

const selectedItem = ref<RelaxActivity | null>(null)
  const loading = ref<boolean>(false)
  const error = ref<string>('')

onMounted(async () => {
  // Lecture et validation de l'ID passé en param
  const rawId = route.params.id
  const id    = typeof rawId === 'string'
    ? parseInt(rawId, 10)
    : NaN

  if (isNaN(id)) {
    error.value = "Identifiant d'activité invalide."
    return
  }

  loading.value = true
  try {
    // On récupère l'activité « brute »
    const data = await relaxActivityService.getOne(id)
    selectedItem.value = data
  } catch (err: unknown) {
    // Gestion d'erreur typée
    if (err instanceof Error) {
      error.value = err.message
    } else {
      error.value = "Erreur lors du chargement de l'activité."
    }
    selectedItem.value = null
  } finally {
    loading.value = false
  }
})

// Retour à la vue précédente
function goBack(): void {
  router.back()
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