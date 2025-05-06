<template>
  <div class="account-view">
    <v-container class="py-10 px-4">
      <h1 class="text-h4 text-center mb-8">Mon compte</h1>

      <!-- Section Infos utilisateur -->
      <v-card class="account-card mb-6" elevation="6">
        <v-card-title class="text-red text-h6 font-weight-bold">Mes informations</v-card-title>
        <v-divider />
        <v-card-text class="d-flex flex-column align-center">
          <v-avatar size="100" class="mb-3">
            <v-img src="/logo.png" alt="Avatar" />
          </v-avatar>
          <div class="text-subtitle-1"><strong> 👤  Username : </strong> {{ userStore.user?.username }}</div>
          <div class="text-subtitle-1 mt-1"><strong> 📧 Email : </strong> {{ userStore.user?.email }}</div>
        </v-card-text>
        <v-card-actions class="justify-center flex-wrap">
          <v-btn class="ma-2" color="darkprimary" border prepend-icon="mdi-account-edit"
            @click="openEditDialog"  
          >Modifier mes infos</v-btn>
          <v-btn class="ma-2" color="darkprimary" border prepend-icon="mdi-lock-reset" 
            @click="showPasswordDialog = true"
          >Changer mot de passe</v-btn>
          <v-btn class="ma-2" color="error" prepend-icon="mdi-logout" @click="handleLogout">Se déconnecter</v-btn>
        </v-card-actions>
      </v-card>

      <!-- Section Favoris -->
      <v-card class="account-card mb-6" elevation="6">
        <v-card-title class="text-red text-h6 font-weight-bold">Mes favoris</v-card-title>
        <v-divider />
        <v-card-text class="px-4 py-4">
          <v-row align="center">
            <v-col cols="9">
              <span class="text-body-1">❤️ {{ favoritesCount }} activités de relaxation</span>
            </v-col>
            <v-col cols="3" class="text-right">
              <v-btn variant="text" color="darkprimary" @click="goToFavorites">Voir</v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>

      <!-- Section Historique -->
      <v-card class="account-card" elevation="6">
        <v-card-title class="text-red text-h6 font-weight-bold">Mes historiques</v-card-title>
        <v-divider />
        <v-card-text class="px-4 py-4">
          <v-row align="center">
            <v-col cols="9">
              <span class="text-body-1">
                <strong>📌 Dernière activité :</strong> {{ lastActivity }}
              </span>
            </v-col>
            <v-col cols="3" class="text-right">
              <v-btn variant="text" color="darkprimary" @click="goToHistory">Voir</v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-container>
  </div>

  <!-- Modale modification des données -->
  <v-dialog v-model="showEditDialog" max-width="500px">
    <v-card>
      <v-card-title class="text-h6 font-weight-bold">
        Modifier mes informations
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-text-field
          v-model="editedUser.username"
          variant="outlined"
          label="Nom d'utilisateur"
          prepend-inner-icon="mdi-account"
        />
        <v-text-field
          v-model="editedUser.email"
          variant="outlined"
          label="Adresse e-mail"
          prepend-inner-icon="mdi-email"
          type="email"
        />
      </v-card-text>
      <v-card-actions class="justify-end">
        <v-btn variant="text" color="grey" @click="showEditDialog = false">Annuler</v-btn>
        <v-btn variant="flat" color="primary" @click="saveUserInfos">Enregistrer</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- SNACKBAR SUCCÈS -->
  <v-snackbar v-model="showSuccess" color="green" timeout="3000">
    Profil mis à jour avec succès !
  </v-snackbar>

  <!-- SNACKBAR ERREUR -->
  <v-snackbar v-model="showError" color="red" timeout="3000">
    Une erreur est survenue lors de la mise à jour.
  </v-snackbar>

  <!-- Modale changer le mot de passe -->
  <v-dialog v-model="showPasswordDialog" max-width="400" persistent>
    <v-card class="pa-4" rounded="xl" elevation="8">
      <v-card-title class="text-h6 text-center">Modifier le mot de passe</v-card-title>
      <v-card-text>
        <v-alert v-if="passwordError" type="error" dense>{{ passwordError }}</v-alert>
        <v-text-field
          v-model="passwordForm.old"
          label="Mot de passe actuel"
          type="password"
          prepend-inner-icon="mdi-lock"
          variant="outlined"
          class="mb-3"
        />
        <v-text-field
          v-model="passwordForm.new"
          label="Nouveau mot de passe"
          type="password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          class="mb-3"
        />
        <v-text-field
          v-model="passwordForm.confirm"
          label="Confirmer le mot de passe"
          type="password"
          prepend-inner-icon="mdi-lock-check"
          variant="outlined"
        />
      </v-card-text>
      <v-card-actions class="justify-end">
        <v-btn variant="text" @click="showPasswordDialog = false">Annuler</v-btn>
        <v-btn color="darkprimary" @click="savePassword">Valider</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <v-snackbar v-model="passwordSuccess" color="success" timeout="3000">
    Mot de passe modifié avec succès 🎉
  </v-snackbar>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import { userService } from '@/api/services/userService'
import type { User } from '@/api/interfaces/User'

const router = useRouter()
const userStore = useUserStore()
const token = computed(() => userStore.token)

const handleLogout = () => {
  userStore.logout()
  router.push('/login')
}

onMounted(async () => {
  if (!userStore.user && userStore.token) {
    await userStore.fetchUserProfile()
  }
})

const favoritesCount = 18
const lastActivity = 'Exercice de respiration'

const goToFavorites = () => {
  router.push('/favorites')
}

const goToHistory = () => {
  router.push('/history')
}

// ================= MODALE MODIF INFOS =========================
const showSuccess = ref(false)
const showError = ref(false)
const showEditDialog = ref(false)
const editedUser = ref<User>({
  id_user: 0,
  username: '',
  email: '',
  password: '',
  is_admin: false // valeur par défaut
})

const openEditDialog = () => {
  if (userStore.user) {
    editedUser.value = {
      id_user: userStore.user.id_user,
      username: userStore.user.username,
      email: userStore.user.email,
      password: '',
      is_admin: userStore.user.is_admin
    }
    showEditDialog.value = true
  }
}

const saveUserInfos = async () => {
  try {
    if (!token.value || !userStore.user) return
    const updatedData: any = {
      id_user: userStore.user.id_user,
      username: editedUser.value.username,
      email: editedUser.value.email,
      is_admin: editedUser.value.is_admin 
    }

    // On ajoute password SEULEMENT s'il est non vide
    if (editedUser.value.password && editedUser.value.password.trim() !== '') {
      updatedData.password = editedUser.value.password
    }
    const response = await userService.updateProfile(token.value as string, updatedData)

    userStore.setUser(response.profile)
    showEditDialog.value = false
    showSuccess.value = true
  } catch (error) {
    console.error('Erreur lors de la mise à jour :', error)
    showError.value = true
  }
}

// ================= MODALE changer Mot De Passe =========================

const showPasswordDialog = ref(false)
const passwordSuccess = ref(false)
const passwordError = ref('')

const passwordForm = ref({
  old: '',
  new: '',
  confirm: '',
})

const savePassword = async () => {
  passwordError.value = ''
  if (passwordForm.value.new !== passwordForm.value.confirm) {
    passwordError.value = "Les mots de passe ne correspondent pas."
    return
  }
  if (!userStore.user || !token.value) return

  try {
    await userService.changePassword(token.value, {
      id_user: userStore.user.id_user,
      old_password: passwordForm.value.old,
      new_password: passwordForm.value.new
    })
    showPasswordDialog.value = false
    passwordSuccess.value = true
    passwordForm.value = { old: '', new: '', confirm: '' }
  } catch (error: any) {
    passwordError.value = error.response?.data?.error || "Erreur lors du changement de mot de passe."
  }
}
</script>

<style scoped>
.account-view {
  background-image: url('@/assets/images/background.jpg');
  background-size: cover;
  background-position: center;
  min-height: 100vh;
}

.account-card {
  border-radius: 16px;
  background-color: rgba(255, 255, 255, 0.95);
}
</style>
