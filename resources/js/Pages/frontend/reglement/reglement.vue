<template>
  <NavBarComponent></NavBarComponent>
  <div class="flex flex-col items-center p-4 h-screen w-full">
    <h1 class="text-2xl font-semibold mb-4 text-center">Règlement Intérieur</h1>
    <!-- Affichage du PDF en plein écran -->
    <iframe
      :src="pdfUrl"
      class="w-full flex-1 border rounded-lg"
      frameborder="0"
      allowfullscreen
    ></iframe>
    <!-- Option de téléchargement -->
    <a
      :href="pdfDownloadUrl"
      download
      class="mt-4 px-6 py-3 text-white bg-green-500 hover:bg-green-600 rounded-lg"
    >
      Télécharger le règlement intérieur
    </a>
  </div>
</template>

<script setup>
import pdfFile from "../../../../../public/pdf/REGLEMENT _NTERIEUR_AMOAMAN.pdf";
import NavBarComponent from "../../components/NavBarComponent.vue";

// URL pour afficher le PDF dans l'iframe
const pdfUrl = `${pdfFile}#toolbar=0&navpanes=0&scrollbar=0`;

// URL pour le téléchargement du PDF
const pdfDownloadUrl = pdfFile;
</script>

<style scoped>
/* Supprime les marges et gère le plein écran */
html, body {
  height: 100%;
  margin: 0;
}
</style>
