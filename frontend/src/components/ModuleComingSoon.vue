<template>
  <v-dialog v-model="internalOpen" max-width="400" @update:modelValue="emitClose" persistent>
    <v-card class="pa-4 text-center rounded-xl">
      <v-card-title class="text-h6 font-weight-bold">
        Module en préparation
      </v-card-title>

      <v-card-text>
        <v-img
          :src="incomingImg"
          alt="Bientôt disponible"
          contain
          class="faded-image mb-4"
        />
        <p>
          Vous retrouverez bientôt votre nouveau module
          <strong>{{ moduleName }}</strong> !
        </p>
      </v-card-text>

      <v-card-actions class="justify-center mt-2">
        <v-btn color="darkprimary" @click="emitClose">Fermer</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { watch, ref } from 'vue'
import incomingImg from '@/assets/images/incoming.jpg'

const props = defineProps<{ open: boolean; moduleName: string }>()
const emit = defineEmits(['close'])

const internalOpen = ref(props.open)

watch(() => props.open, (newVal) => {
  internalOpen.value = newVal
})

const emitClose = () => {
  emit('close')
}
</script>

<style scoped>

.faded-image {
  -webkit-mask-image: radial-gradient(ellipse at center, black 60%, transparent 100%);
  mask-image: radial-gradient(ellipse at center, black 60%, transparent 100%);
  mask-repeat: no-repeat;
  mask-size: cover;
  mask-position: center;
  border-radius: 12px;
}

</style>
