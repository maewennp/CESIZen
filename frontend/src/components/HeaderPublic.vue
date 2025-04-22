<template>
  <v-app-bar
    flat
    class="custom-header px-4"
    height="100"
  >
    <!-- LOGO + TEXTE -->
    <div class="d-flex align-center">
      <v-avatar size="100" class="logo-avatar mr-3">
        <v-img src="/logo.png" alt="Logo CESIZen" cover />
      </v-avatar>
      <span class="logo-title">CESIZen</span>
    </div>

    <v-spacer />

    <!-- ICONES DROITES -->
    <div class="d-flex align-center gap-2">
      <v-btn icon @click="$emit('login')">
        <v-icon>mdi-account</v-icon>
      </v-btn>
      <v-btn icon>
        <v-icon>mdi-menu</v-icon>
      </v-btn>
    </div>
  </v-app-bar>
</template>

<script setup lang="ts">

</script>

<style scoped>
.custom-header {
  background: transparent;
  height: 100px;
  padding-top: 8px;
  padding-bottom: 8px;
}

.logo-avatar {
  width: 100px !important;
  height: 100px !important;
  background-color: white;
  border: 3px solid #B8F1D8;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
}

.logo-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #35b995; 
}
</style>
