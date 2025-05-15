<template>
  <div class="relaxation-background">
  <v-container class="py-10 px-4">

    <v-progress-circular 
      v-if="loading"
      indeterminate 
      color="primary"
      class="d-flex mx-auto"
    />
    <v-alert
      v-else-if="error"
      type="error"
      variant="tonal"
      class="mb-4"
    >
      {{ error }}
    </v-alert>

    <DetailsCard
      v-else-if="selectedItem"
      :id="selectedItem.id_content"
      :title="selectedItem.content_label"
      :description="selectedItem.body"
      :image="selectedItem.media_content" 
      default-image="/assets/images/default-info.jpg"
      @back="goBack"
    />
  </v-container>
</div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DetailsCard from '@/components/DetailsCard.vue'
import type { Info } from '@/api/interfaces/Info'
import { infoService } from '@/api/services/infoService'

const route = useRoute()
const router = useRouter()

const selectedItem = ref<Info | null>(null)
const loading = ref<boolean>(false)
const error = ref<string>('')

onMounted(async () => {
  loading.value = true
  // Récupération et validation de l'ID
  const rawId = route.params.id
  const id    = typeof rawId === 'string' ? parseInt(rawId, 10) : NaN
  if (isNaN(id)) {
    error.value   = 'ID invalide'
    loading.value = false
    return
  }

  try {
    // On récupère l'info telle quelle
    const info = await infoService.getOne(id)
    selectedItem.value = info
  } catch (err: unknown) {
    error.value = err instanceof Error
      ? err.message
      : "Erreur lors du chargement de l'information"
  } finally {
    loading.value = false
  }
})

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