<template>
  <dashboard>
    <div class="border shadow-md w-full sm:w-[80%] h-screen overflow-auto px-4 sm:px-10 py-3 bg-white">
      <div class="flex justify-center mb-4">
        <h1 class="text-2xl font-bold">Liste des Utilisateurs</h1>
      </div>
      <div class="flex justify-center items-center mb-4">
        <span v-if="$page.props.flash.message" class="text-center bg-orange-700 bg-opacity-25">
          {{ $page.props.flash.message }}
        </span>
      </div>
      
      <!-- Vérification si la liste des utilisateurs est vide -->
      <div v-if="users.length === 0" class="text-center text-gray-500">
        <p>Aucun utilisateur trouvé.</p>
      </div>

      <table v-else class="table-auto w-full border-collapse border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2 text-left">Prénom</th>
            <th class="border p-2 text-left">Nom</th>
            <th class="border p-2 text-left">Email</th>
            <th class="border p-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
            <td class="border p-2">{{ user.first_name }}</td>
            <td class="border p-2">{{ user.last_name }}</td>
            <td class="border p-2">{{ user.email }}</td>
            <td class="border p-2 flex flex-wrap justify-center space-x-2 gap-2 sm:space-x-4">
              <Link
                :href="route('users.edit', user.id)"
                class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600"
              >
                Modifier
              </Link>
              <Link
                :href="route('users.onedrive.edit', user.id)"
                class="bg-blue text-white px-2 py-1 rounded hover:bg-blue-600"
              >
                Liens Onedrive
              </Link>
              <Link
                :href="route('user.roles', user.id)"
                class="bg-blue text-white px-2 py-1 rounded hover:bg-blue-600"
              >
                Roles Utilisateur
              </Link>
              <button
                @click="destroy(user.id)"
                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600"
              >
                Supprimer
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <PaginateComponent :paginator="users"></PaginateComponent>

    </div>
  </dashboard>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import dashboard from '../dashboard/dashboard.vue';
import PaginateComponent from '../components/PaginateComponent.vue';

// Définir les props pour recevoir les utilisateurs
const Props = defineProps({
  users: Array // Doit être un tableau
});

// Initialiser le formulaire pour supprimer un utilisateur
const form = useForm({});

// Fonction pour confirmer et supprimer un utilisateur
const destroy = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
    form.delete(route('users.destroy', id));
  }
};
</script>

<style scoped>
@media (max-width: 640px) {
  /* Adjust table cell padding and make buttons stack on small screens */
  .table-auto th, .table-auto td {
    padding: 0.5rem;
  }

  .flex {
    flex-direction: column;
    align-items: flex-start;
  }

  .gap-2 {
    gap: 0.5rem;
  }

  .space-x-4 {
    margin-right: 1rem;
  }

  .text-2xl {
    font-size: 1.5rem;
  }

  /* Make sure the table is scrollable on small screens */
  .table-auto {
    overflow-x: auto;
  }
}
</style>
