<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] p-6 sm:p-10 bg-white mx-auto">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row justify-between mb-6">
        <h1 class="text-blue text-2xl sm:text-3xl mb-4 sm:mb-0">Liste des Fichiers</h1>
        <a
          href="/files/create"
          class="bg-blue text-white px-4 py-2 rounded text-center"
        >
          Ajouter un Fichier
        </a>
      </div>

      <!-- Flash Message -->
      <div class="flex justify-center items-center mb-4">
        <span
          v-if="$page.props.flash.message"
          class="text-center bg-orange-700 bg-opacity-25 p-2 rounded"
        >
          {{ $page.props.flash.message }}
        </span>
      </div>

      <!-- Empty State -->
      <div v-if="files.length === 0" class="text-center text-gray-500">
        <p>Aucun lien fichier trouvé.</p>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="table-auto w-full max-md:w-[80%] border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-100">
              <th class="border border-gray-300 px-4 py-2">ID</th>
              <th class="border border-gray-300 px-4 py-2">Nom</th>
              <th class="border border-gray-300 px-4 py-2">Dossier</th>
              <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="file in files.data" :key="file.id">
              <td class="border border-gray-300 px-4 py-2">{{ file.id }}</td>
              <td class="border text-wrap border-gray-300 px-4 py-2">{{ file.name }}</td>
              <td class="border border-gray-300 px-4 py-2">
                {{ file.folder?.name || 'Aucun' }}
              </td>
              <td class="border border-gray-300 px-4 py-2">
                <a :href="`/files/${file.id}/edit`" class="text-yellow-500">Modifier</a> |
                <a href="#" class="text-red-500" @click.prevent="deleteFile(file.id)">Supprimer</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6">
        <PaginateComponent :paginator="files"></PaginateComponent>
      </div>
    </div>
  </Dashboard>
</template>

<script setup>
import Dashboard from '../dashboard/dashboard.vue';
import { useForm } from '@inertiajs/vue3';
import PaginateComponent from '../components/PaginateComponent.vue';

const form = useForm({});

const props = defineProps({
  files: Object,
});

const deleteFile = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')) {
    form.delete(route('files.destroy', id));
  }
};
</script>
