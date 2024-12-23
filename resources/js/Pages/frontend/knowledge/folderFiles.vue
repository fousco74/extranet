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

// Fonction pour formater la taille du fichier
const formatFileSize = (size) => {
  if (size < 1024) return `${size} B`;
  else if (size < 1048576) return `${(size / 1024).toFixed(2)} KB`;
  else if (size < 1073741824) return `${(size / 1048576).toFixed(2)} MB`;
  else return `${(size / 1073741824).toFixed(2)} GB`;
};
const props = defineProps(['folderId'])


</script>

<template>
  <!-- Barre de navigation -->
  <NavBarComponent :routeName="$page.props.routeName" :id="folderId"></NavBarComponent>

  <div class="p-8 bg-gray-50 min-h-screen">
    <h1 class="text-xl font-bold text-gray-700 mb-6">Vos fichiers 
    </h1>
    <div v-if="$page.props.files.length === 0" class="text-center text-gray-500">
        <p>Aucun lien fichier trouvé.</p>
      </div>
    <!-- Grille des fichiers -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3  gap-6">
      <div
        v-for="file in $page.props.files.data" 
        :key="file.id"
        class="group bg-white border py-4 px-4 border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center"
      >
        <!-- Nom du fichier -->
        <h3 class="text-gray-700 text-sm font-medium group-hover:text-blue-600 transition-colors">
          {{ file.name }}
        </h3>
      </div>
    </div>
    <PaginateComponent :paginator="$page.props.files"></PaginateComponent>
  </div>
</template>
