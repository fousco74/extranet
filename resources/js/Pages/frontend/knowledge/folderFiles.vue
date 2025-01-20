<template>
  <!-- Barre de navigation -->
  <NavBarComponent :routeName="$page.props.routeName" :id="folderId"></NavBarComponent>

  <div class="p-8 bg-gray-50 min-h-screen">
    <h1 class="text-xl font-bold text-gray-700 mb-6">Vos fichiers</h1>
    
    <div v-if="$page.props.files.length === 0" class="text-center text-gray-500">
      <p>Aucun fichier trouvé.</p>
    </div>

    <!-- Grille des fichiers -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <div
        v-for="file in $page.props.files.data"
        :key="file.id"
        class="group bg-white border py-4 px-4 border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center"
      >
        <!-- Icône du fichier en fonction de son extension -->
        
        <!-- Nom du fichier -->
        <h3 class="text-gray-700 text-sm font-medium group-hover:text-blue-600 transition-colors">
          {{ file.name }}
        </h3>

        <!-- Bouton pour afficher le fichier dans un nouvel onglet -->
        <a
          :href="getFileUrl(file.file_link)"
          target="_blank"
          class="mt-4 px-6 py-3 text-blue bg-blue-500 hover:bg-blue-600 rounded-lg"
        >
          Voir le fichier
        </a>

        <!-- Bouton de téléchargement -->
        <a
          :href="getFileUrl(file.file_link)"
          download
          class="mt-4 px-6 py-3 text-white bg-green-500 hover:bg-green-600 rounded-lg"
        >
          Télécharger le fichier
        </a>
      </div>
    </div>

    <!-- Pagination -->
    <PaginateComponent :paginator="$page.props.files"></PaginateComponent>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import NavBarComponent from '../../components/NavBarComponent.vue';
import PaginateComponent from '../../components/PaginateComponent.vue';

// Fonction pour obtenir l'icône du fichier en fonction de son extension
const getFileIcon = (extension) => {
  const icons = {
    'pdf': '/icons/pdf-icon.png',
    'image': '/icons/image-icon.png',
    'txt': '/icons/text-icon.png',
    'docx': '/icons/word-icon.png',
    'xls': '/icons/excel-icon.png',
    'pptx': '/icons/ppt-icon.png',
    'zip': '/icons/zip-icon.png',
    'default': '/icons/file-icon.png',
  };
  return icons[extension.toLowerCase()] || icons['default'];
};

// Fonction pour formater l'URL complète du fichier
const getFileUrl = (file) => {
  return file.startsWith('http') || file.startsWith('https') ? file : `${window.location.origin}/storage/${file}`;
};
</script>

<style scoped>
/* Ajout de styles pour l'affichage */
html, body {
  height: 100%;
  margin: 0;
}
</style>
