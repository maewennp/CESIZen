<template>
  <div class="admin-dashboard">
    <v-container class="py-10">
      <h1 class="text-h4 text-center mb-8">Tableau de bord Administrateur</h1>

      <!-- GRID PRINCIPALE -->
      <v-row justify="center" align="stretch" dense>
        <v-col cols="12" sm="6" md="4" v-for="section in sections" :key="section.label">
          <v-card
            class="admin-card d-flex flex-column justify-space-between"
            @click="selectSection(section.key)"
            height="100"
          >
            <v-card-title class="text-h6">
              <v-icon start class="mr-2" color="darkprimary">{{ section.icon }}</v-icon>
              {{ section.label }}
            </v-card-title>
            <v-card-text class="text-body-2">
              {{ section.description }}
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <!-- ZONE DE CONTENU DYNAMIQUE -->
      <v-expand-transition>
        <AdminTable
          v-if="selectedSection === 'users'"
          title="Gestion des utilisateurs"
          :headers="userHeaders"
          :items="users"
          item-key="id_user"
          :show-toggle="true"
          :show-edit="true"
          :show-delete="true"
          @edit="openEditUser"
          @delete="deleteUser"
          @toggle="toggleUser"
          @add="openCreateUser"
        />

        <AdminTable
          v-else-if="selectedSection === 'informations'"
          title="Gestion des informations"
          :headers="infoHeaders"
          :items="infos"
          :item-key="'id_content'"
          :show-toggle="true"
          @edit="editInformation"
          @delete="deleteInformation"
          @toggle="toggleInformation"
          @add="openCreateInfo"
        />

        <AdminTable
          v-else-if="selectedSection === 'relaxations'"
          title="Gestion des activités"
          :headers="activityHeaders"
          :items="activities"
          :item-key="'activity_label'"
          :show-toggle="true"
          @edit="editActivity"
          @delete="deleteActivity"
          @toggle="toggleActivity"
          @add="openCreateActivity"
        />
      </v-expand-transition>

      <!-- MODALE AJOUT/ MODIF UTILISATEUR -->
      <v-dialog v-model="showUserDialog" max-width="500">
        <v-card>
          <v-card-title class="text-h6 text-darkprimary">
            <v-icon start class="mr-2">mdi-account</v-icon>
            {{ isEditMode ? 'Modifier l’utilisateur' : 'Créer un utilisateur' }}
          </v-card-title>
          <v-card-text>
            <UserForm
              :model-value="userFormModel"
              :is-edit="isEditMode"
              @submit="handleUserSubmit"
              @cancel="closeUserDialog"
            />
          </v-card-text>
        </v-card>
      </v-dialog>

      <!-- MODALE AJOUT/ MODIF INFORMATION -->
      <v-dialog v-model="showInfoDialog" max-width="600">
        <v-card>
          <v-card-title class="text-h6 text-darkprimary">
            <v-icon start class="mr-2">mdi-information</v-icon>
            {{ isEditingInfo ? 'Modifier' : 'Créer' }} une information
          </v-card-title>
          <v-card-text>
            <InfoForm
              :model-value="editedInfo"
              @submit="handleInfoSubmit"
              @cancel="showInfoDialog = false"
            />
          </v-card-text>
        </v-card>
      </v-dialog>

      <!-- MODALE AJOUT/ MODIF ACTIVITE DE RELAXATION -->
      <v-dialog v-model="showActivityDialog" max-width="600">
        <v-card>
          <v-card-title class="text-h6 text-darkprimary">
            <v-icon start class="mr-2">mdi-spa</v-icon>
            {{ isEditingActivity ? 'Modifier' : 'Créer' }} une activité
          </v-card-title>
          <v-card-text>
            <RelaxActivityForm
              :model-value="editedActivity"
              @submit="saveActivity"
              @cancel="showActivityDialog = false"
            />
          </v-card-text>
        </v-card>
      </v-dialog>

    </v-container>

    <!-- MODALE SUPPRESSION -->
    <v-dialog v-model="showDeleteDialog" max-width="400">
      <v-card>
        <v-card-title class="text-h6">Confirmer la suppression</v-card-title>
        <v-card-text>
          Voulez-vous vraiment supprimer 
          <strong>{{ selectedItem?.username || selectedItem?.content_label || selectedItem?.activity_label }}</strong> ?
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="showDeleteDialog = false">Annuler</v-btn>
          <v-btn color="red" @click="confirmDelete">Supprimer</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import AdminTable from '@/components/AdminTable.vue'
import UserForm from '@/components/UserForm.vue'
import InfoForm from '@/components/InfoForm.vue'
import RelaxActivityForm from '@/components/RelaxActivityForm.vue'
import { useUserStore } from '@/stores/userStore'
import { userService } from '@/api/services/userService'
import type { ExtendedUser } from '@/api/interfaces/User'
import type { Info } from '@/api/interfaces/Info'
import { infoService } from '@/api/services/infoService'

// ================  ADMIN - grid principal =================

const userStore = useUserStore()
const token = computed(() => userStore.token)

const sections = [
  {
    key: 'users',
    label: 'Utilisateurs',
    description: 'Gérer les comptes utilisateurs, rôles et statuts.',
    icon: 'mdi-account-multiple',
  },
  {
    key: 'informations',
    label: 'Informations',
    description: 'Ajouter, modifier ou désactiver les informations affichées.',
    icon: 'mdi-information-outline',
  },
  {
    key: 'relaxations',
    label: 'Activités relaxation',
    description: 'Administrer les activités proposées.',
    icon: 'mdi-spa',
  },
]

const selectedSection = ref<string | null>(null)
const selectedItem = ref<any>(null)
const showDeleteDialog = ref(false)

const selectSection = (key: string) => {
  selectedSection.value = key
}

const confirmDelete = async () => {
  if (!token.value || !selectedItem.value) {
    alert('Token manquant ou élément non sélectionné')
    return
  }
  try {
    if (selectedSection.value === 'users') {
      await userService.deleteUser(token.value, selectedItem.value.id_user)
      users.value = users.value.filter(u => u.id_user !== selectedItem.value.id_user)
    } else if (selectedSection.value === 'informations') {
      await infoService.delete(selectedItem.value.id_content, token.value)
      infos.value = infos.value.filter(info => info.id_content !== selectedItem.value.id_content)
    } 
    // else if (selectedSection.value === 'relaxations') {
    //   await relaxActivityService.delete(selectedItem.value.id_activity, token.value)
    //   relaxations.value = relaxations.value.filter(a => a.id_activity !== selectedItem.value.id_activity)
    // }
    showDeleteDialog.value = false
    selectedItem.value = null
  } catch (err: any) {
    alert(err.response?.data?.error || "Erreur lors de la suppression")
  }
}


// =================   USER =======================
const users = ref<ExtendedUser[]>([]) // Liste des utilisateurs

const userHeaders = [
  { title: 'ID', key: 'id_user' },
  { title: 'Nom d’utilisateur', key: 'username' },
  { title: 'Email', key: 'email' },
  { title: 'Admin', key: 'is_admin' },
  { title: 'Actif', key: 'is_active' },
  { title: 'Créé le', key: 'created_at' },
  { title: 'Actions', key: 'actions', sortable: false }
]

onMounted(async () => {
  if (token.value) {
    try {
      users.value = await userService.getAllUsers(token.value)
    } catch (e) {
      console.error('Erreur chargement utilisateurs', e)
    }
  }
})

// Gestion suppression Utilisateur
const deleteUser = (user: any) => {
  selectedItem.value = user
  showDeleteDialog.value = true
}

// const confirmDelete = async () => {
//   try {
//     if (!token.value) {
//       alert('Token manquant, veuillez vous reconnecter')
//       return
//     }
//     await userService.deleteUser(token.value, selectedItem.value.id_user)
//     users.value = users.value.filter(u => u.id_user !== selectedItem.value.id_user)
//     showDeleteDialog.value = false
//     selectedItem.value = null
//   } catch (err: any) {
//     alert(err.response?.data?.error || "Erreur lors de la suppression")
//   }
// }

// Gestion Modale Utilisateur (CRÉATION + ÉDITION)
const showUserDialog = ref(false)
const isEditMode = ref(false)
const userFormModel = ref({
  id: -1,
  username: '',
  email: '',
  password: '',
  is_admin: false,
  active: true,
})

const openCreateUser = () => {
  isEditMode.value = false
  userFormModel.value = {
    id: -1,
    username: '',
    email: '',
    password: '',
    is_admin: false,
    active: true,
  }
  showUserDialog.value = true
}

const openEditUser = (user: any) => {
  isEditMode.value = true
  userFormModel.value = { 
    ...user, 
    password: '',
    is_admin: Boolean(user.is_admin),
    is_active: user.is_active
  }
  showUserDialog.value = true
}

const handleUserSubmit = async (userData: any) => {
  try {
    if (!token.value) {
      alert('Token manquant, veuillez vous reconnecter')
      return
    }
    if (isEditMode.value) {
      // Modification
      userData.is_admin = userData.is_admin ? 1 : 0
      const response = await userService.updateUser(token.value, userData.id_user, userData)
      // Mets à jour la liste locale
      const idx = users.value.findIndex(u => u.id_user === userData.id_user)
      if (idx !== -1) users.value[idx] = response.profile
    } else {
      // Création
      const response = await userService.createUser(token.value, userData)
      users.value.unshift(response.profile)
    }
    closeUserDialog()
  } catch (err: any) {
    alert(err.response?.data?.error || "Erreur lors de l'opération")
  }
}

const closeUserDialog = () => {
  showUserDialog.value = false
  isEditMode.value = false
}

// Toggle actif/inactif
const toggleUser = async (user: ExtendedUser) => {
  try {
    if (!token.value) {
      alert('Token manquant, veuillez vous reconnecter')
      return
    }
    const response = await userService.toggleUser(token.value, user.id_user)
    user.is_active = response.is_active // Mets à jour l'état localement
  } catch (err: any) {
    alert(err.response?.data?.error || "Erreur lors du changement d'état")
  }
}

// ================ INFO =======================

const infos = ref<Info[]>([])
const loadingInfos = ref(false)
const errorInfos = ref('')

const infoHeaders = [
  { title: 'ID', key: 'id_content' },
  { title: 'Titre', key: 'content_label' },
  { title: 'Contenu', key: 'body' },
  { title: 'Image', key: 'media_content' },
  { title: 'Visible', key: 'visible' },
  { title: 'Créé le', key: 'created_at' },
  { title: 'Actions', key: 'actions', sortable: false },
]

onMounted(async () => {
  loadingInfos.value = true
  try {
    if (token.value) {
      infos.value = await infoService.adminGetAll(token.value)
    } else {
      errorInfos.value = "Token d'authentification manquant"
    }
  } catch (e: any) {
    errorInfos.value = e.message || "Erreur lors du chargement des informations"
  } finally {
    loadingInfos.value = false
  }
})

const informations = ref([
  {
    content_label: "L'importance de la santé mentale",
    body: "La santé mentale est primordiale car elle sous-tend notre bien-être global...",
    media_content: 'info0.png',
    visible: true,
    get active() { return this.visible },
    set active(val) { this.visible = val }
  },
  {
    content_label: "Les bienfaits de la respiration",
    body: "La respiration profonde permet de réduire le stress...",
    media_content: 'info1.jpg',
    visible: true,
    get active() { return this.visible },
    set active(val) { this.visible = val }
  }
]) 

const deleteInformation = (item: any) => {
  selectedItem.value = item
  showDeleteDialog.value = true
}

const toggleInformation = async (item: Info) => {
  if (!token.value) return
  try {
    await infoService.toggleVisibility(item.id_content, token.value)
    item.visible = !item.visible
  } catch (e) {
    alert("Erreur lors du changement de visibilité")
  }
}

const showInfoDialog = ref(false)
const isEditingInfo = ref(false)
const editedInfo = ref({
  id_content: -1,
  content_label: '',
  body: '',
  media_content: '',
  visible: true
})

const openCreateInfo = () => {
  isEditingInfo.value = false
  editedInfo.value = {
    id_content: -1,
    content_label: '',
    body: '',
    media_content: '',
    visible: true
  }
  showInfoDialog.value = true
}

const editInformation = (item: any) => {
  isEditingInfo.value = true
  editedInfo.value = { ...item }
  showInfoDialog.value = true
}

const handleInfoSubmit = async (formData: any) => {
  try {
    if (!token.value) {
      alert('Token manquant, veuillez vous reconnecter')
      return
    }
    if (isEditingInfo.value) {
      // Modification
      const updatedData = { ...formData, id_content: editedInfo.value.id_content }
      await infoService.update(updatedData, token.value)
      // Mets à jour la liste locale
      const idx = infos.value.findIndex(i => i.id_content === updatedData.id_content)
      if (idx !== -1) infos.value[idx] = { ...infos.value[idx], ...updatedData }
    } else { // Création
      const result = await infoService.create(formData, token.value)
      if (result.error) {
        alert(result.error)
        return
      }
      // Ajoute la nouvelle info à la liste locale avec l'id retourné
      infos.value.push({
        id_content: Number(result.id_content),
        content_label: formData.content_label,
        body: formData.body,
        media_content: formData.media_content || '',
        visible: formData.visible ?? true,
        created_at: new Date().toISOString(), // ou récupère la date côté API si possible
      })
    }
    showInfoDialog.value = false
    isEditingInfo.value = false
  } catch (err: any) {
    alert(err.response?.data?.error || "Erreur lors de la modification")
  }
}

// ============== RELAX ACTIVITY ======================

const activityHeaders = [
  { title: 'Titre', key: 'activity_label' },
  { title: 'Contenu', key: 'content' },
  { title: 'Catégorie', key: 'category' },
  { title: 'Type', key: 'type' },
  { title: 'Media', key: 'media_activity' },
  { title: 'Actions', key: 'actions', sortable: false },
]

const activities = ref([
  {
    activity_label: "Yoga doux",
    content: "Série de mouvements lents qui relâchent les tensions.",
    category: "détente",
    type: "physique",
    media_activity: 'yoga.jpg',
    is_active: true
  },
  {
    activity_label: "Méditation guidée",
    content: "Laissez-vous porter par une voix apaisante...",
    category: "mental",
    type: "audio",
    media_activity: 'meditation.jpg',
    is_active: false
  }
])

const deleteActivity = (item: any) => {
  selectedItem.value = item
  showDeleteDialog.value = true
}
const toggleActivity = (item: any) => {
  item.active = !item.active
}

const showActivityDialog = ref(false)
const isEditingActivity = ref(false)
const editedActivity = ref({
  activity_label: '',
  content: '',
  category: '',
  type: '',
  media_activity: '',
  is_active: true
})

const openCreateActivity = () => {
  isEditingActivity.value = false
  editedActivity.value = {
    activity_label: '',
    content: '',
    category: '',
    type: '',
    media_activity: '',
    is_active: true
  }
  showActivityDialog.value = true
}

const editActivity = (item: any) => {
  isEditingActivity.value = true
  editedActivity.value = { ...item }
  showActivityDialog.value = true
}

const saveActivity = (data: any) => {
  if (isEditingActivity.value) {
    const index = activities.value.findIndex(a => a.activity_label === data.activity_label)
    if (index !== -1) activities.value[index] = { ...data }
  } else {
    activities.value.push({ ...data })
  }
  showActivityDialog.value = false
}



</script>

<style scoped>
.admin-dashboard {
  background-image: url('@/assets/images/background.jpg');
  background-size: cover;
  min-height: 100vh;
}

.admin-card {
  
  cursor: pointer;
  border-radius: 16px;
  transition: transform 0.3s ease;
  background-color: white;
}

.admin-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}
</style>
