<template>
  <div class="relaxation-background">
  <v-container class="py-10 px-4">
    <DetailsCard
      :title="selectedItem?.title"
      :description="selectedItem?.description"
      :image="selectedItem?.image"
      :showFavorite="true"
      @back="goBack"
    />
  </v-container>
</div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DetailsCard from '@/components/DetailsCard.vue'

const route = useRoute()
const router = useRouter()

const contents = [
  {
    id: 1,
    title: 'Yoga doux',
    description: `Explorez une série de mouvements lents et fluides qui favorisent la souplesse et relâchent les tensions musculaires. 
      La pratique du yoga doux permet une meilleure conscience du corps et une respiration plus profonde. 
      C’est une méthode idéale pour détendre l’esprit et évacuer le stress accumulé dans la journée. 
      Les postures sont accessibles à tous les niveaux et peuvent être réalisées en toute sérénité. 
      Un moment pour soi, pour relier corps et esprit dans le calme.`,
    image: new URL('@/assets/images/relax/yoga.jpg', import.meta.url).href,
  },
  {
    id: 2,
    title: 'Pause détente',
    description: `Faites une vraie pause mentale grâce à cette activité de recentrage. 
      L’objectif est simple : couper le flux de pensées parasites et revenir à l’instant présent. 
      Par des exercices de respiration, de pleine conscience et de relâchement musculaire, vous retrouvez un état de calme profond. 
      C’est idéal pour relancer la concentration ou terminer une journée agitée. 
      Un rituel court mais puissant à intégrer dans votre quotidien.`,
    image: new URL('@/assets/images/relax/pause-detente.png', import.meta.url).href,
  },
  {
    id: 3,
    title: 'Visualisation positive',
    description: `Renforcez votre mental en vous projetant dans des situations apaisantes. 
      Cette technique puissante vous permet de stimuler des sensations de bien-être en vous imaginant dans un environnement rassurant. 
      Elle est souvent utilisée pour diminuer l’anxiété, améliorer la confiance en soi et préparer des événements stressants. 
      En quelques minutes, vous plongez dans un état de détente profonde. 
      Une belle manière de reprogrammer son esprit au positif.`,
    image: new URL('@/assets/images/relax/visu-positive.png', import.meta.url).href,
  },
  {
    id: 4,
    title: 'Méditation guidée',
    description: `Laissez-vous porter par une voix douce qui vous guide vers un apaisement intérieur. 
      La méditation guidée aide à relâcher les tensions physiques et émotionnelles en se concentrant sur des sensations simples. 
      Elle est parfaite pour les débutants comme pour les initiés, et ne nécessite aucune connaissance préalable. 
      Grâce à cette pratique, vous cultivez la présence, la bienveillance envers vous-même et la sérénité. 
      Un moment suspendu pour se reconnecter à l’essentiel.`,
    image: new URL('@/assets/images/relax/meditation.jpg', import.meta.url).href,
  }
]

const selectedItem = ref<any>(null)

onMounted(() => {
  const id = Number(route.params.id)
  selectedItem.value = contents.find((item) => item.id === id)
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