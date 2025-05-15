<template>
  <v-form @submit.prevent="handleSubmit" v-model="formValid" ref="formRef">
    <v-text-field
      v-model="localInfo.content_label"
      label="Titre de l'information"
      prepend-inner-icon="mdi-label"
      variant="outlined"
      :rules="[rules.required]"
      required
    />

    <v-textarea
      v-model="localInfo.body"
      label="Contenu"
      variant="outlined"
      auto-grow
      :rules="[rules.required]"
      prepend-inner-icon="mdi-text"
      required
    />

    <v-text-field
      v-model="localInfo.media_content"
      label="Image"
      prepend-inner-icon="mdi-image"
      variant="outlined"
    />

    <v-checkbox
      v-model="localInfo.visible"
      label="Visible ?"
      color="darkprimary"
    />

    <v-card-actions class="justify-end mt-4">
      <v-btn text @click="$emit('cancel')">Annuler</v-btn>
      <v-btn color="darkprimary" type="submit">Enregistrer</v-btn>
    </v-card-actions>
  </v-form>
</template>

<script setup lang="ts">
import type { Info } from '@/api/interfaces/Info';
import { ref, watch, defineProps, defineEmits } from 'vue'


interface FormRef {
  validate: () => boolean
}

const props = defineProps<{
  modelValue: Info
}>()

// Déclaration des emits avec payload typé
const emit = defineEmits<{
  (e: 'submit', payload: Info): void
  (e: 'cancel'): void
}>()

// Références réactives
const formRef = ref<FormRef | null>(null)
const formValid = ref(false)

// Copie locale pour l’édition
const localInfo = ref<Info>({ ...props.modelValue })

watch(() => props.modelValue, (newVal) => {
  localInfo.value = { ...newVal }
})

// Règles de validation
const rules = {
  required: (v: unknown) =>
    (!!v && String(v).trim().length > 0) || 'Champ requis'
}

const handleSubmit = () => {
  if (formRef.value?.validate()) {
    emit('submit', { ...localInfo.value })
  }
}
</script>
