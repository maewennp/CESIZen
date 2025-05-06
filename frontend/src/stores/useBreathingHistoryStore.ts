import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useUserStore } from './userStore'

export interface BreathingSession {
  date: string        // ISO string
  duration: number    // en secondes
  exercise: string    // nom ou type d’exercice
}

export const useBreathingHistoryStore = defineStore('breathingHistory', () => {
  const userStore = useUserStore()
  const sessions = ref<BreathingSession[]>([])

  // Persistance par utilisateur
  function loadSessions() {
    if (!userStore.user?.email) return
    const key = `breathingHistory_${userStore.user.email}`
    const saved = localStorage.getItem(key)
    sessions.value = saved ? JSON.parse(saved) : []
  }

  function saveSessions() {
    if (!userStore.user?.email) return
    const key = `breathingHistory_${userStore.user.email}`
    localStorage.setItem(key, JSON.stringify(sessions.value))
  }

  function addSession(session: BreathingSession) {
    sessions.value.unshift(session) // Ajoute en tête
    saveSessions()
  }

  function clearSessions() {
    sessions.value = []
    saveSessions()
  }

  return { sessions, addSession, loadSessions, clearSessions }
})
