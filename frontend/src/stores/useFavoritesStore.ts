// src/stores/useFavoritesStore.ts
import { defineStore } from 'pinia'

export const useFavoritesStore = defineStore('favorites', {
  state: () => ({
    favoriteIds: [] as number[],
  }),
  actions: {
    toggleFavorite(id: number) {
      if (this.favoriteIds.includes(id)) {
        this.favoriteIds = this.favoriteIds.filter((favId) => favId !== id)
      } else {
        this.favoriteIds.push(id)
      }
    },
    isFavorite(id: number) {
      return this.favoriteIds.includes(id)
    }
  },
})
