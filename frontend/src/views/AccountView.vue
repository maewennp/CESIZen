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
          <div class="text-subtitle-1"><strong> 👤  Username : </strong> {{ user.username }}</div>
          <div class="text-subtitle-1 mt-1"><strong> 📧 Email : </strong> {{ user.email }}</div>
        </v-card-text>
        <v-card-actions class="justify-center flex-wrap">
          <v-btn class="ma-2" color="darkprimary" border prepend-icon="mdi-account-edit"
            @click="() => { editedUser = { ...user }; showEditDialog = true }"  
          >Modifier mes infos</v-btn>
          <v-btn class="ma-2" color="darkprimary" border prepend-icon="mdi-lock-reset" 
            @click="showPasswordDialog = true"
          >Changer mot de passe</v-btn>
          <v-btn class="ma-2" color="error" prepend-icon="mdi-logout">Se déconnecter</v-btn>
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

  <!-- Modale changer le mot de passe -->
  <v-dialog v-model="showPasswordDialog" max-width="400" persistent>
    <v-card class="pa-4" rounded="xl" elevation="8">
      <v-card-title class="text-h6 text-center">Modifier le mot de passe</v-card-title>
      <v-card-text>
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const user = ref({
  username: 'zen_user',
  email: 'zen@example.com',
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

const showEditDialog = ref(false)

const editedUser = ref({ ...user })

const saveUserInfos = () => {
  user.value = { ...editedUser.value }
  showEditDialog.value = false
}

// ================= MODALE changer Mot De Passe =========================

const showPasswordDialog = ref(false)
const passwordSuccess = ref(false)

const passwordForm = ref({
  old: '',
  new: '',
  confirm: '',
})

const savePassword = () => {
  if (passwordForm.value.new !== passwordForm.value.confirm) {
    alert("Les mots de passe ne correspondent pas.")
    return
  }

  // ajouter requète API
  console.log('Ancien:', passwordForm.value.old)
  console.log('Nouveau:', passwordForm.value.new)

  // Reset & close
  showPasswordDialog.value = false
  passwordSuccess.value = true
  passwordForm.value = { old: '', new: '', confirm: '' }
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
