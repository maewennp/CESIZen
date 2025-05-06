<template>
  <div class="cgu-container">
    <v-container class="py-10 px-4">
      <v-card elevation="6" class="pa-6 rounded-lg background">
        <v-card-title class="text-h5 text-darkprimary font-weight-bold mb-6">
          Conditions Générales d’Utilisation (CGU)
        </v-card-title>

        <v-card-text class="text-body-1">
          <section class="mb-6" v-for="(section, index) in sections" :key="index">
            <h3 class="text-subtitle-1 text-darkprimary font-weight-medium"><strong>{{ section.title }}</strong></h3>
            <p class="mt-2">{{ section.content }}</p>
          </section>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script setup lang="ts">
const sections = [
  {
    title: '1. Présentation de l’application',
    content:
      "L’application CESIZen est un prototype développé dans un cadre éducatif. Elle propose des outils de suivi du stress, de relaxation et de gestion émotionnelle. Elle est fournie gratuitement, sans vocation médicale ni commerciale.",
  },
  {
    title: '2. Accès à l’application',
    content:
      "L’accès est libre pour les visiteurs, avec inscription optionnelle pour des fonctionnalités avancées. Le service est expérimental et sans garantie de disponibilité continue.",
  },
  {
    title: '3. Données personnelles',
    content:
      "Les données enregistrées (émotions, diagnostics, favoris) sont conservées uniquement dans le cadre du prototype. Aucune donnée n’est transmise à des tiers. L’utilisateur peut demander la suppression de ses données à tout moment.",
  },
  {
    title: '4. Propriété intellectuelle',
    content:
      "Le code, les contenus et le design sont la propriété exclusive de l’auteur. Toute reproduction ou diffusion est interdite sans autorisation.",
  },
  {
    title: '5. Responsabilité',
    content:
      "L’application CESIZen n’a pas de visée médicale. L’auteur ne peut être tenu responsable des effets liés à son usage ou interprétation.",
  },
  {
    title: '6. Évolutions',
    content:
      "CESIZen étant en développement, ces conditions peuvent être mises à jour sans préavis. L’utilisateur est invité à les consulter régulièrement.",
  },
  {
    title: '7. Contact',
    content:
      "Pour toute question ou demande :\nMaewenn PORGEON – Projet CESIZen\n📧 [maewenn.test@gmail.com]\n📁 GitHub : [https://github.com/maewennp/CESIZen]",
  },
]
</script>

<style scoped>
.background {
  background-color: #FEFBFB;
}
.cgu-container {
  background-image: url('@/assets/images/background.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  min-height: 100vh;
}

p {
  white-space: pre-line;
}
</style>
