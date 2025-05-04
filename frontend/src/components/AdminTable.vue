<template>
  <v-card class="mt-10 pa-4">
    <v-card-title class="d-flex justify-space-between align-center">
      <span class="text-h6 text-darkprimary">{{ title }}</span>
      <v-btn icon color="darkprimary" @click="$emit('add')">
        <v-icon>mdi-plus</v-icon>
      </v-btn>
    </v-card-title>

    <v-data-table
      :headers="headers"
      :items="items"
      :item-value="itemKey"
      class="elevation-1"
      dense
    >
      <template #item.actions="{ item }">
        <v-tooltip text="Modifier" location="top" v-if="showEdit">
          <template #activator="{ props }">
            <v-icon
              v-bind="props"
              small
              class="mr-2"
              color="blue"
              @click="$emit('edit', item)"
            >
              mdi-pencil
            </v-icon>
          </template>
        </v-tooltip>

        <v-tooltip text="Supprimer" location="top" v-if="showDelete">
          <template #activator="{ props }">
            <v-icon
              v-bind="props"
              small
              class="mr-2"
              color="red"
              @click="$emit('delete', item)"
            >
              mdi-delete
            </v-icon>
          </template>
        </v-tooltip>

        <v-tooltip :text="item.active ? 'Désactiver' : 'Activer'" location="top" v-if="showToggle">
          <template #activator="{ props }">
            <v-icon
              v-bind="props"
              small
              :color="item.is_active ? 'green' : 'grey'"
              @click="$emit('toggle', item)"
            >
              {{ item.is_active ? 'mdi-toggle-switch' : 'mdi-toggle-switch-off' }}
            </v-icon>
          </template>
        </v-tooltip>
      </template>
    </v-data-table>
  </v-card>
</template>

<script setup lang="ts">
import type { DataTableHeader } from 'vuetify'

const props = withDefaults(defineProps<{
  title: string
  headers: DataTableHeader[]
  items: any[]
  itemKey?: string
  showEdit?: boolean
  showDelete?: boolean
  showToggle?: boolean
}>(), {
  itemKey: 'id',
  showEdit: true,
  showDelete: true,
  showToggle: false,
})

defineEmits(['add', 'edit', 'delete', 'toggle'])
</script>
