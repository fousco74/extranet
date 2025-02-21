<template>
  <Dashboard>
    <div class="border shadow-md w-[80%] p-10 bg-white">
      <div class="mb-10">
        <h1 class="text-blue text-3xl">Détails du Fichier</h1>
      </div>
      <div class="space-y-4">
        <p><strong>ID :</strong> {{ file.id }}</p>
        <p><strong>Nom :</strong> {{ file.name }}</p>
        <p><strong>Dossier :</strong> {{ file.folder?.name || 'Aucun' }}</p>
        <a href="/files" class="bg-gray-500 text-white px-4 py-2 rounded">Retour</a>
      </div>
    </div>
  </Dashboard>
</template>

<script setup>
import { ref } from 'vue';
import Dashboard from '../dashboard/dashboard.vue';


const file = ref({
  id: 1,
  name: 'Document Example',
  folder: { name: 'Folder A' },
  // Charger dynamiquement les détails
});
</script>
