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
      label="Image (nom du fichier ou URL)"
      prepend-inner-icon="mdi-image"
      variant="outlined"
      :rules="[rules.required]"
      required
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
import { ref, watch, defineProps, defineEmits } from 'vue'

const props = defineProps<{
  modelValue: any
}>()

const emit = defineEmits(['submit', 'cancel'])

const formRef = ref()
const formValid = ref(false)

const localInfo = ref({ ...props.modelValue })

watch(() => props.modelValue, (newVal) => {
  localInfo.value = { ...newVal }
})

const rules = {
  required: (v: string) => !!v || 'Champ requis'
}

const handleSubmit = () => {
  if (formRef.value?.validate()) {
    emit('submit', { ...localInfo.value })
  }
}
</script>
