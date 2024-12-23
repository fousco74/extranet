<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[90%] md:w-[80%] lg:w-[70%] xl:w-[60%] p-6 sm:p-8 md:p-10 bg-white">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-blue text-2xl sm:text-3xl md:text-4xl">Liste des Dossiers</h1>
        <a href="/folders/create" class="bg-blue text-white px-4 py-2 rounded text-sm sm:text-base">Ajouter un Dossier</a>
      </div>
      <div class="flex justify-center items-center mb-4">
        <span v-if="$page.props.flash.message" class="text-center bg-green-100 text-green-800 px-4 py-2 rounded">
          {{ $page.props.flash.message }}
        </span>
      </div>
      <div v-if="folders.length === 0" class="text-center text-gray-500">
        <p>Aucun dossier trouvé.</p>
      </div>
      <table v-else class="table-auto w-full border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-100">
            <th class="border border-gray-300 px-4 py-2">ID</th>
            <th class="border border-gray-300 px-4 py-2">Nom</th>
            <th class="border border-gray-300 px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="folder in folders.data" :key="folder.id">
            <td class="border border-gray-300 px-4 py-2">{{ folder.id }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ folder.name }}</td>
            <td class="border border-gray-300 px-4 py-2">
              <a :href="`/folders/${folder.id}/edit`" class="text-yellow-500 text-sm sm:text-base">Modifier</a> |
              <a href="#" class="text-red-500 text-sm sm:text-base" @click.prevent="deleteFolder(folder.id)">Supprimer</a>
            </td>
          </tr>
        </tbody>
      </table>
      <PaginateComponent :paginator="folders"></PaginateComponent>
    </div>
  </Dashboard>
</template>

<script setup>
import { ref } from 'vue';
import Dashboard from '../dashboard/dashboard.vue';
import { useForm } from '@inertiajs/vue3';
import PaginateComponent from '../components/paginateComponent.vue';

const props = defineProps({
  folders: Object
})

const form = useForm({})

const deleteFolder = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')) {
    form.delete(route('folders.destroy', id));
  }
};
</script>
