<template>
  <v-container class="py-10 px-4 history-background" fluid>
    <h1 class="text-h4 text-center mb-8">🕘 Mes historiques</h1>

    <!-- Exercice de respiration -->
    <v-card class="mb-6" elevation="4">
      <v-card-title class="text-darkprimary font-weight-bold">
        <v-icon class="mr-2">mdi-weather-windy</v-icon>
        Exercices de respiration
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-row>
          <v-col>
            <v-list v-if="breathing.length">
              <v-list-item v-for="(item, i) in breathing" :key="i">
                <v-list-item-content>
                  <v-list-item-title>{{ formatDate(item.date) }}</v-list-item-title>
                  <v-list-item-subtitle>Durée : {{ formatDuration(item.duration) }} min</v-list-item-subtitle>
                  <v-list-item-subtitle>Exercice : {{ item.exercise }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <p v-else class="text-grey">Aucun exercice enregistré.</p>
          </v-col>

          <v-col cols="auto" class="d-flex align-end justify-end">
            <v-btn icon @click="openDialog('breathing')" variant="text" color="error">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <!-- Tracker d’émotion -->
    <v-card class="mb-6" elevation="4">
      <v-card-title class="text-darkprimary font-weight-bold">
        <v-icon class="mr-2">mdi-emoticon-outline</v-icon>
        Tracker d’émotion
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-row>
          <v-col>
            <v-list v-if="emotions.length">
              <v-list-item v-for="(item, i) in emotions" :key="i">
                <v-list-item-content>
                  <v-list-item-title>{{ item.date }}</v-list-item-title>
                  <v-list-item-subtitle>Émotion : {{ item.emotion }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <p v-else class="text-grey">Aucune émotion enregistrée.</p>
          </v-col>
          <v-col cols="auto" class="d-flex align-end justify-end">
            <v-btn icon @click="openDialog('emotions')" variant="text" color="error">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <!-- Diagnostic de stress -->
    <v-card class="mb-6" elevation="4">
      <v-card-title class="text-darkprimary font-weight-bold">
        <v-icon class="mr-2">mdi-heart-pulse</v-icon>
        Diagnostic de stress
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-row>
          <v-col>
            <v-list v-if="diagnostics.length">
              <v-list-item v-for="(item, i) in diagnostics" :key="i">
                <v-list-item-content>
                  <v-list-item-title>{{ item.date }}</v-list-item-title>
                  <v-list-item-subtitle>Niveau de stress : {{ item.level }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <p v-else class="text-grey">Aucun diagnostic enregistré.</p>
          </v-col>
          <v-col cols="auto" class="d-flex align-end justify-end">
            <v-btn icon @click="openDialog('diagnostics')" variant="text" color="error">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <!-- Modale de confirmation -->
    <v-dialog v-model="confirmDialog" max-width="400">
      <v-card class="pa-4">
        <v-card-title class="text-h6">Confirmer la suppression</v-card-title>
        <v-card-text>
          Êtes-vous sûr(e) de vouloir supprimer tout l’historique <strong>{{ getSectionLabel(sectionToClear) }}</strong> ?
        </v-card-text>
        <v-card-actions class="justify-end">
          <v-btn variant="text" @click="confirmDialog = false">Annuler</v-btn>
          <v-btn color="error" @click="clearSection">Confirmer</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useBreathingHistoryStore } from '@/stores/useBreathingHistoryStore'

const breathingHistoryStore = useBreathingHistoryStore()

// Liste réactive des sessions, la plus récente en premier
const breathing = computed(() => breathingHistoryStore.sessions)

function formatDate(dateStr: string) {
  const date = new Date(dateStr)
  return date.toLocaleString('fr-FR', { dateStyle: 'medium', timeStyle: 'short' })
}

function formatDuration(duration: number) {
  const min = Math.floor(duration / 60)
  const sec = duration % 60
  return `${min} min${sec > 0 ? ' ' + sec + ' s' : ''}`
}

const emotions = ref([
  { date: '-', emotion: '-' },
  // { date: '2025-04-27', emotion: 'Fatigué' },
])

const diagnostics = ref([
  { date: '-', level: '-' },
  // { date: '2025-04-20', level: 'Élevé' },
])

const confirmDialog = ref(false)
const sectionToClear = ref<'breathing' | 'emotions' | 'diagnostics' | null>(null)

const openDialog = (section: typeof sectionToClear.value) => {
  sectionToClear.value = section
  confirmDialog.value = true
}

const clearSection = () => {
  if (sectionToClear.value === 'breathing') breathingHistoryStore.clearSessions()
  else if (sectionToClear.value === 'emotions') emotions.value = []
  else if (sectionToClear.value === 'diagnostics') diagnostics.value = []

  confirmDialog.value = false
}

const getSectionLabel = (section: string | null) => {
  switch (section) {
    case 'breathing': return 'des exercices de respiration'
    case 'emotions': return 'du tracker d’émotion'
    case 'diagnostics': return 'des diagnostics de stress'
    default: return ''
  }
}
</script>

<style scoped>
.history-background {
  background-image: url('@/assets/images/background.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  min-height: 100vh;
}
</style>
