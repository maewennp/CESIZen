<template>
  <div class="relaxation-background">
    <v-container class="py-10 px-4">
      <h1 class="text-h4 text-center mb-8">Informations</h1>
      <v-text-field
        v-model="search"
        label="Rechercher une activité..."
        prepend-inner-icon="mdi-magnify"
        variant="outlined"
        class="mb-6"
        hide-details
        clearable
      />
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
import ContentCard from '@/components/ContentCard.vue'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const search = ref('')

const contents = [
  {
    id: 1,
    title: "L'importance de la santé mentale",
    description: `La santé mentale est la base de notre équilibre. Elle influence nos émotions, nos décisions et nos relations. 
      Une bonne santé mentale permet de mieux gérer les défis du quotidien, de rebondir face aux épreuves et de cultiver un sentiment de bien-être durable. 
      Apprendre à en prendre soin est essentiel pour prévenir le stress, l’anxiété ou l’épuisement. 
      Cela passe par l’écoute de soi, le repos, et la mise en place de rituels bienveillants. 
      Une attention portée à notre esprit est une véritable force.`,

    image: new URL('@/assets/images/info/info0.png', import.meta.url).href,
  },
  {
    id: 2,
    title: 'Les bienfaits de la respiration',
    description: `La respiration consciente est un outil simple mais puissant pour réguler le stress. 
      En apprenant à respirer lentement et profondément, on active le système nerveux parasympathique, celui du calme et du repos. 
      Cette technique permet d’apaiser l’esprit, de retrouver de la clarté mentale et de ralentir le rythme intérieur. 
      C’est une pratique accessible à tous, à tout moment de la journée. 
      Respirez… et offrez à votre corps une pause précieuse.`,

    image: new URL('@/assets/images/info/info1.jpg', import.meta.url).href,
  },
  {
    id: 3,
    title: 'Relaxation guidée',
    description: `La relaxation guidée est une invitation à lâcher prise. 
      En suivant une voix douce, vous êtes amené à détendre chaque partie de votre corps, à relâcher les tensions physiques et mentales. 
      Cette méthode améliore la qualité du sommeil, diminue l’irritabilité et augmente la sensation de bien-être. 
      C’est un vrai moment de pause où vous vous reconnectez à votre corps, à votre souffle, à vous-même. 
      Idéale en fin de journée ou après un moment de stress.`,

    image: new URL('@/assets/images/info/info2.jpg', import.meta.url).href,
  }
]

const goToDetails = (id: number) => {
  router.push(`/info/${id}`)
}

const filteredContents = computed(() => {
  const keyword = search.value.toLowerCase().trim()
  if (!keyword) return contents

  return contents.filter(item =>
    item.title.toLowerCase().includes(keyword) ||
    item.description.toLowerCase().includes(keyword)
  )
})
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