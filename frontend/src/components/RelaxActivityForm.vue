<template>
  <v-form v-model="formValid" ref="formRef" lazy-validation>
    <v-text-field
      v-model="form.activity_label"
      label="Titre de l’activité"
      variant="outlined"
      prepend-inner-icon="mdi-spa"
      :rules="[rules.required]"
      required
    />
    <v-textarea
      v-model="form.content"
      label="Description"
      variant="outlined"
      prepend-inner-icon="mdi-text"
      :rules="[rules.required]"
      auto-grow
      required
    />
    <v-text-field
      v-model="form.category"
      label="Catégorie"
      variant="outlined"
      prepend-inner-icon="mdi-tag"
    />
    <v-text-field
      v-model="form.type"
      label="Type"
      variant="outlined"
      prepend-inner-icon="mdi-format-list-bulleted-type"
    />
    <v-text-field
      v-model="form.media_activity"
      label="Fichier média (image)"
      variant="outlined"
      prepend-inner-icon="mdi-image"
    />
    <v-checkbox
      v-model="form.is_active"
      label="Activer cette activité"
      color="darkprimary"
    />

    <div class="d-flex justify-end mt-4">
      <v-btn text @click="$emit('cancel')">Annuler</v-btn>
      <v-btn
        color="primary"
        class="ml-2"
        :disabled="!formValid"
        @click="submitForm"
      >
        Enregistrer
      </v-btn>
    </div>
  </v-form>
</template>

<script setup lang="ts">
import type { RelaxActivity } from '@/api/interfaces/RelaxActivity';
import { defineEmits, defineProps, ref, watch } from 'vue'

const emit = defineEmits(['submit', 'cancel', 'update:modelValue'])

const props = defineProps<{
  modelValue: RelaxActivity
}>()

const formValid = ref(false)
const formRef = ref()
const form = ref({ ...props.modelValue })

watch(() => props.modelValue, (newVal) => {
  form.value = { ...newVal }
})

const rules = {
  required: (v: string) => !!v || 'Champ requis',
}

const submitForm = () => {
  if (formRef.value?.validate()) {
    emit('submit', { ...form.value })
  }
}
</script>
