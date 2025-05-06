<template>
  <div class="relaxation-background">
    <v-container class="py-10 px-4 text-center">
      <h1 class="text-h4 text-center mb-8">Informations</h1>

      <p class="text-body-1 mb-6">
        <strong>Comprendre pour mieux agir.</strong><br>
        Dans cette section, vous trouverez des ressources utiles pour mieux comprendre le stress, ses mécanismes, ses effets, ainsi que des conseils concrets pour l’apprivoiser au quotidien.
        Ces contenus ont pour but de vous accompagner, vous informer et vous encourager à prendre soin de votre santé mentale.
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
      <v-progress-circular v-if="loading" indeterminate color="primary" class="my-8" />
      <v-alert v-if="error" type="error" dense class="mb-6">{{ error }}</v-alert>

      <v-row v-else justify="center" align="center" dense>
        <v-col
          v-for="item in filteredContents"
          :key="item.id"
          cols="12"
          sm="6"
          md="4"
          class="d-flex justify-center"
        >
          <ContentCard
            :title="item.title"
            :description="item.description"
            :image="item.image"
            @click="goToDetails(item.id)"
          />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script setup lang="ts">
import type { Info } from '@/api/interfaces/Info'
import { infoService } from '@/api/services/infoService'
import ContentCard from '@/components/ContentCard.vue'
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const search = ref('')

const infos = ref<Info[]>([])
const loading = ref(false)
const error = ref('')

onMounted(async () => {
  loading.value = true
  try {
    infos.value = await infoService.getAllVisible()
  } catch (e: any) {
    error.value = e.message || 'Erreur lors du chargement des informations'
  } finally {
    loading.value = false
  }
})

// Adaptation des données pour ContentCard
const mappedContents = computed(() =>
  infos.value.map(info => ({
    id: info.id_content,
    title: info.content_label,
    description: info.body,
    image: info.media_content || '/assets/images/default-info.jpg', // image par défaut si vide
  }))
)

// Filtrage avec la recherche
const filteredContents = computed(() => {
  const keyword = search.value.toLowerCase().trim()
  if (!keyword) return mappedContents.value

  return mappedContents.value.filter(item =>
    item.title.toLowerCase().includes(keyword) ||
    item.description.toLowerCase().includes(keyword)
  )
}) 

const goToDetails = (id: number) => {
  router.push(`/info/${id}`)
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