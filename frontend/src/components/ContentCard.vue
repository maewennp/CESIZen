<template>
  <v-card
    class="content-card my-2"
    elevation="6"
    @click="handleClick"
  >
    <v-img
      :src="image"
      alt="Illustration"
      height="200"
      class="rounded-t-lg"
      default
    />
    <v-card-text class="pa-4">
      <h3 class="text-subtitle-1 font-weight-bold mb-2 text-darkprimary">
        {{ title }}
      </h3>
      <p class="text-body-2 truncate-text">
        {{ description }}
      </p>
      <span class="text-caption text-darkprimary font-weight-bold mt-2 d-inline-block">
        VOIR PLUS
      </span>
      
    </v-card-text>
    <v-card-actions v-if="showFavorite" class="justify-end pt-0 pb-10 pr-9">
      <v-icon
        @click.stop="toggleFavorite"
        :color="isFavorite ? 'red' : 'grey'"
        size="20"
        class="cursor-pointer"
      >
        {{ isFavorite ? 'mdi-heart' : 'mdi-heart-outline' }}
      </v-icon>
    </v-card-actions>
    
  </v-card>
</template>

<script setup lang="ts">
import { defineProps, defineEmits, ref, computed } from 'vue'
import { useFavoritesStore } from '@/stores/useFavoritesStore'

const favoritesStore = useFavoritesStore()

const props = defineProps({
  id: Number,
  title: String,
  description: String,
  image: String,
  showFavorite: Boolean,
})

const emit = defineEmits(['click'])

//const isFavorite = ref(false)
const isFavorite = computed(() =>
  props.id !== undefined ? favoritesStore.isFavorite(props.id) : false
)

const handleClick = () => {
  emit('click')
}

// const toggleFavorite = () => {
//   isFavorite.value = !isFavorite.value
// }

const toggleFavorite = () => {
  if (props.id !== undefined) {
    favoritesStore.toggleFavorite(props.id)
  }
}
</script>

<style scoped>
.content-card {
  width: 100%;
  max-width: 320px;
  border-radius: 16px;
  cursor: pointer;
  transition: transform 0.3s ease;
  background-color: white;
}

.content-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.truncate-text {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.4;
  max-height: 2.8em;
}

.v-card-actions {
  margin-top: -25px; 
}
</style>