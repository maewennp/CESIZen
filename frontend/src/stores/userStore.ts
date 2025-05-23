import { defineStore } from 'pinia'
import { ref } from 'vue'
import { userService } from '@/api/services/userService'
import type { User } from '@/api/interfaces/User'
import { useFavoritesStore } from './useFavoritesStore'
import { useBreathingHistoryStore } from './useBreathingHistoryStore'

export const useUserStore = defineStore('user', () => {
  const token = ref<string | null>(localStorage.getItem('token'))
  const isAuthenticated = ref<boolean>(!!token.value)
  //const user = ref<{ username: string; email: string; is_admin: boolean } | null>(null)
  const user = ref<User | null>(null)

  // 🔐 Charger le profil à partir du token
  async function fetchUserProfile() {
    if (!token.value) return
    try {
      const response = await userService.getProfile(token.value)
      user.value = response.profile
      isAuthenticated.value = true
      // Charger les favoris de l'utilisateur connecté
      useFavoritesStore().loadFavorites()
      useBreathingHistoryStore().loadSessions()
    } catch (err) {
      console.error('Erreur chargement profil', err)
      logout()
    }
  }

  // ✅ Après login ou register
  function setToken(newToken: string) {
    token.value = newToken
    localStorage.setItem('token', newToken)
    isAuthenticated.value = true
    fetchUserProfile()
  }

  // 🚪 Déconnexion
  function logout() {
    token.value = null
    user.value = null
    isAuthenticated.value = false
    localStorage.removeItem('token')
  }

  function setUser(updatedUser: User) {
    user.value = updatedUser
  }

  return {
    token,
    isAuthenticated,
    user,
    setToken,
    fetchUserProfile,
    logout,
    setUser
  }
})
