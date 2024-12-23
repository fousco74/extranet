<template>
    <NavBarComponent></NavBarComponent>
    <div class="flex flex-col items-center p-4">
      <h1 class="text-2xl font-semibold mb-6">Règlement Intérieur</h1>
      <!-- Affichage du PDF -->
      <iframe
        :src="pdfUrl"
        class="w-full h-[400px] border "
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
  /* Aucune règle CSS spécifique ici, tout est géré avec Tailwind */
  </style>
  