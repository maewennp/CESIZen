import { defineStore } from 'pinia'
import { useUserStore } from './userStore'

export const useFavoritesStore = defineStore('favorites', {
  state: () => ({
    favoriteIds: [] as number[],
  }),
  actions: {
    loadFavorites() {
      const userStore = useUserStore()
      if (!userStore.user?.email) return
      const key = `favorites_${userStore.user.email}`
      const saved = localStorage.getItem(key)
      this.favoriteIds = saved ? JSON.parse(saved) : []
    },
    saveFavorites() {
      const userStore = useUserStore()
      if (!userStore.user?.email) return
      const key = `favorites_${userStore.user.email}`
      localStorage.setItem(key, JSON.stringify(this.favoriteIds))
    },
    toggleFavorite(id: number) {
      if (this.favoriteIds.includes(id)) {
        this.favoriteIds = this.favoriteIds.filter((favId) => favId !== id)
      } else {
        this.favoriteIds.push(id)
      }
      this.saveFavorites()
    },
    isFavorite(id: number) {
      return this.favoriteIds.includes(id)
    },
    clearFavorites() {
      this.favoriteIds = []
      this.saveFavorites()
    }
  },
})
