<template>
  <div class="relaxation-background">
  <v-container class="py-10 px-4">
    <DetailsCard
      v-if="selectedItem"
      :id="selectedItem.id_activity"
      :title="selectedItem?.activity_label"
      :description="selectedItem?.content"
      :image="selectedItem?.media_activity"
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
  const loading = ref(false)
  const error = ref('')

  onMounted(async () => {
  const id = Number(route.params.id)
  if (isNaN(id)) {
    error.value = "Identifiant d'activité invalide."
    return
  }
  loading.value = true
  try {
    const data = await relaxActivityService.getOne(id)
    if (!data || data.error) {
      error.value = data?.error || "Activité non trouvée."
      selectedItem.value = null
    } else {
      selectedItem.value = {
        ...data,
        image: data.media_activity ? data.media_activity : new URL('@/assets/images/zen.jpg', import.meta.url).href
      }
    }
  } catch (e) {
    error.value = "Erreur lors du chargement de l'activité."
    selectedItem.value = null
  } finally {
    loading.value = false
  }
})

const goBack = () => {
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