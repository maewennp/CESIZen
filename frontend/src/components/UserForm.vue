<template>
  <v-form @submit.prevent="handleSubmit" v-model="formValid" ref="formRef">
    <v-text-field
      v-model="localModel.username"
      label="Nom d'utilisateur"
      prepend-inner-icon="mdi-account"
      variant="outlined"
      :rules="[rules.required]"
      required
    />

    <v-text-field
      v-model="localModel.email"
      label="Adresse email"
      prepend-inner-icon="mdi-email"
      variant="outlined"
      :rules="[rules.required, rules.email]"
      required
    />

    <!-- Mot de passe (modification possible mais facultative) -->
    <v-text-field
      v-model="localModel.password"
      label="Mot de passe"
      :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
      @click:append-inner="showPassword = !showPassword"
      :type="showPassword ? 'text' : 'password'"
      prepend-inner-icon="mdi-lock"
      variant="outlined"
      :rules="isEdit ? [] : [rules.required, rules.min(6)]"
      :placeholder="isEdit ? 'Laisser vide pour ne pas changer' : ''"
    />

    <v-checkbox
      v-model="localModel.is_admin"
      label="Administrateur"
      color="darkprimary"
      :true-value="true"
      :false-value="false"
    />

    <v-card-actions class="justify-end mt-4">
      <v-btn text @click="$emit('cancel')">Annuler</v-btn>
      <v-btn color="darkprimary" type="submit">Enregistrer</v-btn>
    </v-card-actions>
  </v-form>
</template>

<script setup lang="ts">
import { ref, watch, defineEmits, defineProps } from 'vue'

const emit = defineEmits(['submit', 'cancel'])

const props = defineProps<{
  modelValue: any
  isEdit?: boolean
}>()

const localModel = ref({ ...props.modelValue })

watch(() => props.modelValue, (newVal) => {
  localModel.value = { ...newVal }
})

const formRef = ref()
const formValid = ref(false)
const showPassword = ref(false)

const rules = {
  required: (v: string) => !!v || 'Champ requis',
  email: (v: string) => /.+@.+\..+/.test(v) || 'Email invalide',
  min: (n: number) => (v: string) => !v || v.length >= n || `Minimum ${n} caractères`
}

const handleSubmit = () => {
  if (formRef.value?.validate()) {
    const result = { ...localModel.value }
    if (props.isEdit && !result.password) {
      delete result.password // ne pas inclure si vide
    }
    emit('submit', result)
  }
}
</script>
