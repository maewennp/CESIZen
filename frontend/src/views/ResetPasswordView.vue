<template>
  <v-app>
    <v-main class="reset-background fill-height">
      <v-container
        fluid
        class="d-flex align-center justify-center px-4"
        style="min-height: 100vh"
      >
        <v-card
          max-width="500"
          class="pa-6 reset-card fade-in-up"
          elevation="10"
          rounded="2xl"
        >
          <v-card-title
            class="text-h5 text-center mb-4 font-weight-bold"
            style="word-break: break-word; white-space: normal;"
          >
            Réinitialiser votre mot de passe
          </v-card-title>

          <v-form @submit.prevent="submit" ref="resetForm" validate-on="input">
            <v-text-field
              v-model="form.password"
              label="Nouveau mot de passe"
              prepend-inner-icon="mdi-lock"
              type="password"
              color="darkprimary"
              :rules="[rules.required, rules.min(6)]"
              density="comfortable"
              variant="outlined"
              class="mb-4"
            />

            <v-text-field
              v-model="form.confirmPassword"
              label="Confirmer le mot de passe"
              prepend-inner-icon="mdi-lock-check"
              type="password"
              color="darkprimary"
              :rules="[rules.required, rules.match]"
              density="comfortable"
              variant="outlined"
              class="mb-4"
            />

            <v-btn
              type="submit"
              color="primary"
              block
              class="mt-2 text-white"
              size="large"
            >
              Réinitialiser
            </v-btn>
          </v-form>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { authService } from '@/api/services/authService'

const form = ref({
  password: '',
  confirmPassword: '',
})

const route = useRoute()
const router = useRouter()
const token = ref('')

onMounted(() => {
  token.value = route.query.token as string
})

const rules = {
  required: (value: string) => !!value || 'Champ requis',
  min: (min: number) => (value: string) =>
    value.length >= min || `Minimum ${min} caractères`,
  match: (value: string) =>
    value === form.value.password || 'Les mots de passe ne correspondent pas',
}

async function submit() {
  if (!token.value) {
    console.error('Token manquant dans l’URL.')
    return
  }

  try {
    await authService.resetPassword(form.value.password, token.value)
    alert('Mot de passe réinitialisé avec succès !')
    router.push({ name: 'login' }) // Redirige vers la page de connexion
  } catch (error) {
    alert('Erreur lors de la réinitialisation du mot de passe.')
  }
}
</script>

<style scoped>
.reset-background {
  width: 100%;
  height: 100vh;
  background: url('@/assets/images/register-landscape.jpg') center center / cover no-repeat fixed;
}

.reset-card {
  background-color: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(4px);
  border-radius: 24px;
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.9s ease-out both;
}
</style>
