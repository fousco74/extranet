<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] md:w-[70%] lg:w-[60%] p-6 sm:p-10 bg-white mx-auto">
      <div class="mb-10">
        <h1 class="text-blue text-3xl text-center sm:text-left">Détails du Lien OneDrive</h1>
      </div>
      <div class="space-y-4">
        <p class="text-sm sm:text-base"><strong>ID :</strong> {{ link.id }}</p>
        <p class="text-sm sm:text-base"><strong>Nom du Dossier :</strong> {{ link.folder_name }}</p>
        <p class="text-sm sm:text-base"><strong>Lien OneDrive :</strong> 
          <a :href="link.link" target="_blank" class="text-blue-500 underline">{{ link.link }}</a>
        </p>
        <a href="/onedrive-links" class="bg-gray-500 text-white px-4 py-2 rounded inline-block text-sm sm:text-base">Retour</a>
      </div>
    </div>
  </Dashboard>
</template>

<script setup>
import { ref } from 'vue';
import Dashboard from '../dashboard/dashboard.vue';

const link = ref({
  id: 1,
  folder_name: 'Dossier A',
  link: 'https://link-a.com',
  // Chargez dynamiquement les détails du lien
});
</script>
