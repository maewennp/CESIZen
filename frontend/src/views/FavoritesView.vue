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
import { computed, ref } from 'vue'

const favoritesStore = useFavoritesStore()

const favoriteContents = computed(() =>
  contents.filter(item => favoritesStore.favoriteIds.includes(item.id))
)
// données mockées pour l'instant
const contents = [
  {
    id: 1,
    title: 'Yoga doux',
    description: 'Explorez une série de mouvements lents et fluides qui favorisent la souplesse...',
    image: new URL('@/assets/images/relax/yoga.jpg', import.meta.url).href,
    favorite: true,
  },
  {
    id: 2,
    title: 'Pause détente',
    description: 'Faites une vraie pause mentale grâce à cette activité de recentrage...',
    image: new URL('@/assets/images/relax/pause-detente.png', import.meta.url).href,
    favorite: false,
  },
  {
    id: 3,
    title: 'Visualisation positive',
    description: 'Renforcez votre mental en vous projetant dans des situations apaisantes...',
    image: new URL('@/assets/images/relax/visu-positive.png', import.meta.url).href,
    favorite: true,
  }
]

//const favorites = ref(allActivities.filter((a) => a.favorite))
const router = useRouter()

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
