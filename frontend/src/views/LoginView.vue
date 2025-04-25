<template>
  <v-app>
    <v-main class="login-page">
      <v-container class="fill-height d-flex justify-center align-center px-4" fluid>
        <div class="login-wrapper">
          <!-- Logo positionné au-dessus -->
          <v-avatar size="150" class="logo-avatar">
            <v-img src="/logo.png" alt="Logo CESIZen" cover />
          </v-avatar>

          <!-- Carte de connexion -->
          <v-card class="pa-6 login-card text-center" max-width="400" elevation="10">
            <v-card-title class="text-h4">Connexion</v-card-title>

            <v-card-text>
              <v-form @submit.prevent="submitLogin">
                <v-text-field
                  v-model="loginForm.email"
                  label="Adresse e-mail"
                  type="email"
                  prepend-inner-icon="mdi-email"
                  variant="outlined"
                  class="mb-4"
                  required
                />
                <!-- <div class="mb-4"> -->
                  <v-text-field
                    v-model="loginForm.password"
                    label="Mot de passe"
                    type="password"
                    prepend-inner-icon="mdi-lock"
                    variant="outlined"
                    class="mb-0"
                    required
                  />

                  <div class="text-right mt-1 mb-4">
                    <v-btn
                      variant="text"
                      size="small"
                      class="forgot-link"
                      @click="forgotDialog = true"
                    >
                      Mot de passe oublié ?
                    </v-btn>
                  </div>
                <!-- </div> -->

                <v-btn block color="primary" class="mt-0 mb-4" type="submit">
                  Se connecter
                </v-btn>
                
                <div class="text-caption">
                  Vous n'avez pas de compte ?
                  <v-btn
                    variant="outlined"
                    size="small"
                    color="#4e7c68"
                    class="mt-2 text-uppercase font-weight-boldn register-btn"
                    @click="goToRegister"
                  >
                    S'inscrire ici
                  </v-btn>
                </div>
              </v-form>
              <v-alert
                  v-if="errorMessage"
                  type="error"
                  variant="tonal"
                  class="mb-4"
                >
                  {{ errorMessage }}
                </v-alert>
            </v-card-text>
          </v-card>
        </div>

        <v-dialog v-model="forgotDialog" max-width="400" persistent>
          <v-card class="pa-4" rounded="xl" elevation="8">
            <v-card-title class="text-h6 text-center">Réinitialiser le mot de passe</v-card-title>
            
            <v-card-text>
              <v-alert
                v-if="forgotSent"
                type="success"
                variant="tonal"
                text="Un lien de réinitialisation a été envoyé par e-mail."
                class="mb-4"
              />
              
              <v-text-field
                v-model="forgotEmail"
                label="Votre adresse email"
                type="email"
                prepend-inner-icon="mdi-email"
                variant="outlined"
                :disabled="forgotSent"
              />
            </v-card-text>

            <v-card-actions class="justify-end">
              <template v-if="forgotSent">
                <v-btn color="darkprimary" @click="forgotDialog = false">OK</v-btn>
              </template>
              <template v-else>
                <v-btn variant="text" @click="forgotDialog = false">Annuler</v-btn>
                <v-btn color="darkprimary" @click="sendForgotPassword" :disabled="forgotSent">Envoyer</v-btn>
              </template>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { authService } from '@/api/services/authService'
import { useUserStore } from '@/stores/userStore'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const userStore = useUserStore()

const loginForm = ref({
  email: '',
  password: '',
})

const errorMessage = ref('')
const loading = ref(false) 

const submitLogin = async () => {
  loading.value = true
  errorMessage.value = ''
  try {
    const response = await authService.login(loginForm.value.email, loginForm.value.password)
    userStore.setToken(response.token)
    router.push('/') // redirection après connexion
  } catch (error: any) {
    errorMessage.value = error.response?.data?.error || 'Erreur de connexion. Login ou mot de passe incorrecte'
  } finally {
    loading.value = false
  }
}

const goToRegister = () => {
  router.push('/register')
}

// gestion mot de passe oublié
const forgotDialog = ref(false)
const forgotEmail = ref('')
const forgotSent = ref(false)

const sendForgotPassword = async () => {
  if (!forgotEmail.value) return

  loading.value = true
  try {
    await authService.forgotPassword(forgotEmail.value)
    forgotSent.value = true
  } catch (error: any) {
    errorMessage.value = error.response?.data?.error || "Erreur lors de l'envoi de l'email"
  } finally {
    loading.value = false
  }
}


</script>

<style scoped>
.login-page {
  background-image: url('@/assets/images/nature-landscape.jpg');
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
  background-color: #fee5d8;
  height: 100vh;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  
}

.login-wrapper {
  position: relative;
  max-width: 400px;
  width: 100%;
  animation: fadeIn 0.9s ease-out both;
}

@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.login-card {
  background-color: rgba(255, 255, 255, 0.88);
  border-radius: 24px;
  backdrop-filter: blur(4px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  padding-top: 60px;
}

.logo-avatar {
  position: absolute;
  top: -130px;
  left: 50%;
  transform: translateX(-50%);
  width: 160px !important;
  height: 160px !important;
  background-color: white;
  border: 2px solid #B8F1D8;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  z-index: 10;
}

.forgot-link {
  font-size: 0.78rem;
  color: #4e7c68;
  text-transform: none;
  font-weight: 500;
  padding: 0;
  min-height: 0;
  margin-top: -15px !important;
  margin-bottom: 10px !important;
}

.forgot-link:hover {
  color: #2e5d52;
  background-color: none;
}



</style>
