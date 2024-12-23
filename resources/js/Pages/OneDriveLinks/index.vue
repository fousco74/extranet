<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-3/4 md:w-2/3 lg:w-[60%] xl:w-[50%] p-6 bg-white mx-auto">
      <div class="flex justify-between mb-6">
        <h1 class="text-blue text-3xl">Liste des Liens OneDrive</h1>
        <a href="/one-drive-links/create" class="bg-blue text-white px-4 py-2 rounded text-sm sm:text-base">Ajouter un Lien OneDrive</a>
      </div>
      <div class="flex justify-center items-center mb-4">
        <span v-if="$page.props.flash.message" class="text-center bg-orange-700 bg-opacity-25 text-sm sm:text-base">
          {{ $page.props.flash.message }}
        </span>
      </div>
     

      <!-- Tableau avec défilement horizontal pour les petits écrans -->
      <div class="overflow-x-auto">
        <div v-if="links.length === 0" class="text-center text-gray-500 text-sm sm:text-base">
          <p>Aucun lien onedrive trouvé.</p>
        </div>

        <table v-else class="table-auto w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-100">
              <th class="border border-gray-300 px-4 py-2 text-sm sm:text-base">ID</th>
              <th class="border border-gray-300 px-4 py-2 text-sm sm:text-base">Nom du Dossier</th>
              <th class="border border-gray-300 px-4 py-2 text-sm sm:text-base">Lien</th>
              <th class="border border-gray-300 px-4 py-2 text-sm sm:text-base">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="link in links.data" :key="link.id">
              <td class="border border-gray-300 px-4 py-2 text-sm sm:text-base">{{ link.id }}</td>
              <td class="border border-gray-300 px-4 py-2 text-sm sm:text-base">{{ link.name }}</td>
              <td class="border border-gray-300 px-4 py-2 text-sm sm:text-base">
                <a :href="link.link" target="_blank" class="text-blue-500 underline text-sm sm:text-base">
                  {{ link.link }}
                </a>
              </td>
              <td class="border border-gray-300 px-4 py-2 text-sm sm:text-base">
                <a :href="`/one-drive-links/${link.id}/edit`" class="text-yellow-500 text-sm sm:text-base">Modifier</a> |
                <a href="#" class="text-red-500 text-sm sm:text-base" @click.prevent="deleteLink(link.id)">Supprimer</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <PaginateComponent :paginator="links"></PaginateComponent>
    </div>
  </Dashboard>
</template>

<script setup>
import Dashboard from '../dashboard/dashboard.vue';
import { useForm } from '@inertiajs/vue3';
import PaginateComponent from '../components/paginateComponent.vue';

const props = defineProps({
  links: Object
})

// Initialisation du formulaire avec Inertia.js
const form = useForm({});

// Fonction pour supprimer un lien OneDrive
const deleteLink = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce lien OneDrive ?')) {
    form.delete(route('one-drive-links.destroy', id));
  }
};
</script>
