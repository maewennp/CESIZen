<template>
  <div class="breathing-container">
    <v-container class="text-center py-10">
      <h1 class="text-h4 mb-8">Exercice de respiration</h1>

      <p class="text-body-1 mb-6">
        La respiration est un outil puissant pour réguler le stress, retrouver le calme et améliorer votre bien-être mental. Choisissez l’exercice qui vous convient et suivez le rythme.
      </p>

      <v-select
        v-model="selectedExercise"
        :items="exerciseOptions"
        label="Choix de l'exercice"
        item-value="value"
        outlined
        class="mb-4"
      />

      <!-- Texte explicatif en fonction du choix -->
      <p v-if="selectedExercise" class="text-caption text-black mb-6">
        {{ exerciseDescriptions[selectedExercise] }}
      </p>

      <v-btn
        color="primary"
        class="mb-8"
        :disabled="!selectedExercise"
        @click="isRunning ? stopBreathing() : startBreathing()"
      >
        {{ isRunning ? 'Arrêter' : 'Lancer' }}
      </v-btn>

      <div v-if="isRunning" class="breathing-visual">
        <div
          class="circle"
          :style="{ transform: `scale(${currentScale})` }"
        ></div>
        <p class="text-h5 mt-6 text-darkprimary font-weight-bold">
          {{ instruction }}
        </p>
        <p class="text-subtitle-2 text-black">
          Temps restant : {{ secondsLeft }}s · Cycle {{ currentCycle }} / 20
        </p>
      </div>
    </v-container>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const isRunning = ref(false)
const phase = ref<'inspire' | 'pause' | 'expire'>('inspire')
const instruction = ref('')
const secondsLeft = ref(0)
const currentCycle = ref(1)
const currentScale = ref(1) // départ à taille moyenne
import { useBreathingHistoryStore } from '@/stores/useBreathingHistoryStore'

const breathingHistoryStore = useBreathingHistoryStore()

let timer: any
let animationTimer: any
let phases: { label: string; time: number; type: 'inspire' | 'pause' | 'expire' }[] = []
let step = 0
let duration = 0
let sessionStart: number

const selectedExercise = ref('')

const exerciseOptions = [
  '7-4-8',
  '5-0-5',
  '4-0-6',
]

const exerciseDescriptions: Record<string, string> = {
  '7-4-8': '7 secondes d’inspiration, 4 secondes de pause, 8 secondes d’expiration.',
  '5-0-5': '5 secondes d’inspiration, 0 secondes d’apnée, 5 secondes d’expiration.',
  '4-0-6': '4 secondes d’inspiration, 0 secondes d’apnée, 6 secondes d’expiration.',
}

const startBreathing = () => {
  const [inTime, pauseTime, outTime] = selectedExercise.value.split('-').map(Number)
  phases = [
    { label: 'Inspirez...', time: inTime, type: 'inspire' },
    { label: 'Retenez...', time: pauseTime, type: 'pause' },
    { label: 'Expirez...', time: outTime, type: 'expire' }
  ]
  isRunning.value = true
  step = 0
  currentCycle.value = 1
  sessionStart = Date.now()
  nextPhase()
  // breathingHistoryStore.addSession({
  //   date: new Date().toISOString(),
  //   duration: timer.value, 
  //   exercise: selectedExercise.value 
  // })
}

const nextPhase = () => {
  const current = phases[step]
  instruction.value = current.label
  phase.value = current.type
  secondsLeft.value = current.time
  duration = current.time

  clearInterval(animationTimer)
  if (phase.value === 'inspire') animateScale(currentScale.value, 1.6)
  else if (phase.value === 'expire') animateScale(currentScale.value, 0.6)
  else currentScale.value = currentScale.value // pause → stable

  timer = setInterval(() => {
    secondsLeft.value--
    if (secondsLeft.value <= 0) {
      clearInterval(timer)
      step = (step + 1) % phases.length
      if (step === 0) currentCycle.value++
      if (currentCycle.value > 20) stopBreathing()
      else nextPhase()
    }
  }, 1000)
}

const stopBreathing = () => {
  clearInterval(timer)
  clearInterval(animationTimer)
  isRunning.value = false
  instruction.value = ''
  phase.value = 'inspire'
  secondsLeft.value = 0
  currentCycle.value = 1
  currentScale.value = 1

  if (sessionStart) {
    const durationSec = Math.round((Date.now() - sessionStart) / 1000)
    breathingHistoryStore.addSession({
      date: new Date().toISOString(),
      duration: durationSec,
      exercise: selectedExercise.value
    })
    sessionStart = 0
  }
  currentCycle.value = 1
}

// Anime progressivement le scale
const animateScale = (start: number, end: number) => {
  const stepCount = duration * 10 // 10 frames/sec
  const diff = (end - start) / stepCount
  let currentStep = 0
  clearInterval(animationTimer)
  animationTimer = setInterval(() => {
    currentStep++
    currentScale.value = +(currentScale.value + diff).toFixed(4)
    if (currentStep >= stepCount) clearInterval(animationTimer)
  }, 100)
}
</script>

<style scoped>
.breathing-container {
  background-image: url('@/assets/images/background.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  min-height: 100vh;
}

.breathing-visual {
  height: 320px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.circle {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background-color: rgba(72, 182, 157, 0.3);
  transition: transform 0.2s ease;
  margin-bottom: 30px;
}
</style>
