<template>
  <v-app>
    <v-main class="register-background fill-height">
      <v-container
        fluid
        class="d-flex align-center justify-center px-4"
        style="min-height: 100vh"
      >
        <v-card
          max-width="500"
          class="pa-6 register-card fade-in-up"
          elevation="10"
          rounded="2xl"
        >
          <v-card-title
            class="text-h5 text-center mb-4 font-weight-bold"
            style="word-break: break-word; white-space: normal;"
          >
            Créer un compte CESIZen
          </v-card-title>

          <v-form @submit.prevent="submitForm" ref="registerForm" validate-on="input">
            <v-text-field
              v-model="form.username"
              label="Nom d'utilisateur"
              prepend-inner-icon="mdi-account"
              color="darkprimary"
              :rules="[rules.required]"
              density="comfortable"
              variant="outlined"
              class="mb-4"
            />

            <v-text-field
              v-model="form.email"
              label="Adresse email"
              prepend-inner-icon="mdi-email"
              type="email"
              color="darkprimary"
              :rules="[rules.required, rules.email]"
              density="comfortable"
              variant="outlined"
              class="mb-4"
            />

            <v-text-field
              v-model="form.password"
              label="Mot de passe"
              prepend-inner-icon="mdi-lock"
              type="password"
              color="darkprimary"
              :rules="[rules.required, rules.min(6)]"
              density="comfortable"
              variant="outlined"
              class="mb-4"
            />

            <v-checkbox
              v-model="form.terms"
              color="darkprimary"
              label="J'accepte les conditions générales d'utilisation"
              :rules="[rules.terms]"
              class="mt-2"
            />

            <v-btn
              type="submit"
              color="primary"
              block
              class="mt-4 text-white"
              size="large"
            >
              S'inscrire
            </v-btn>
          </v-form>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const form = ref({
  username: '',
  email: '',
  password: '',
  terms: false,
})

const rules = {
  required: (value: string) => !!value || 'Champ requis',
  email: (value: string) =>
    /.+@.+\..+/.test(value) || "L'adresse email est invalide",
  min: (min: number) => (value: string) =>
    value.length >= min || `Minimum ${min} caractères`,
  terms: (value: boolean) => value || 'Vous devez accepter les conditions',
}

function submitForm() {
  console.log('Formulaire soumis', form.value)
  // Exemple : envoi via fetch ou axios ici vers ton backend PHP
  // fetch('/api/register.php', { method: 'POST', body: JSON.stringify(form.value) })
}
</script>

<style scoped>
.register-background {
  width: 100%;
  height: 100vh;
  background: url('@/assets/images/register-landscape.jpg') center center / cover no-repeat fixed;
}

.register-card {
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
