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
      v-if="error"
      type="error"
      variant="tonal"
      class="mb-4"
    >
      {{ error }}
    </v-alert>

    <DetailsCard
      v-if="selectedItem"
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
const loading = ref(false)
const error = ref('')

onMounted(async () => {
  try {
    loading.value = true
    const id = Number(route.params.id)
    
    if (isNaN(id)) throw new Error('ID invalide')
    
    const data = await infoService.getOne(id)
    
    if (!data) {
      error.value = 'Information non trouvée'
      return
    }
    
    selectedItem.value = {
      ...data,
      media_content: data.media_content ? data.media_content : new URL('@/assets/images/default-info.jpg', import.meta.url).href
    }
    
  } catch (err: any) {
    error.value = err.message || "Erreur lors du chargement de l'information"
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