<template>
  <div class="breathing-container">
    <v-container class="text-center py-10">
      <h1 class="text-h4 mb-8">Exercice de respiration</h1>

      <v-select
        v-model="selectedExercise"
        :items="exerciseOptions"
        label="Choix de l'exercice"
        outlined
        class="mb-4"
      />

      <v-btn
        color="primary"
        class="mb-8"
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
        <p class="text-subtitle-2 text-grey">
          Temps restant : {{ secondsLeft }}s · Cycle {{ currentCycle }} / 20
        </p>
      </div>
    </v-container>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const selectedExercise = ref('4-4-4')
const exerciseOptions = ['3-4-5', '4-4-4', '5-5-5']

const isRunning = ref(false)
const phase = ref<'inspire' | 'pause' | 'expire'>('inspire')
const instruction = ref('')
const secondsLeft = ref(0)
const currentCycle = ref(1)
const currentScale = ref(1) // départ à taille moyenne

let timer: any
let animationTimer: any
let phases: { label: string; time: number; type: 'inspire' | 'pause' | 'expire' }[] = []
let step = 0
let duration = 0

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
  nextPhase()
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
