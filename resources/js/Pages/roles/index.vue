<template>
  <dashboard>
    <div class="border shadow-md w-full md:w-[80%] h-screen overflow-auto p-10 bg-white mx-auto">
      <h4 class="text-xl font-semibold mb-6">Liste des Rôles</h4>
  
      <div class="bg-white shadow-md rounded p-6">
        <div class="flex justify-between items-center mb-4">
          <h5 class="text-lg font-medium">Liste des rôles</h5>
          <Link href="/roles/create" class="bg-blue text-white px-4 py-2 rounded shadow hover:bg-blue-600">
            Nouveau Rôle
          </Link>
        </div>
  
        <div class="overflow-x-auto">
          <table class="w-full table-auto border-collapse border border-gray-200">
            <thead>
              <tr>
                <th class="px-4 py-2 border-b">Nom</th>
                <th class="px-4 py-2 border-b">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="role in roles.data" :key="role.id">
                <td class="px-4 py-2 border-b">{{ role.name }}</td>
                <td class="px-4 py-2 border-b">
                  <div class="flex space-x-2">
                    <Link :href="`/roles/${role.id}/edit`" class="text-blue-600 hover:underline">
                      Modifier
                    </Link>
                    <Link :href="`/roles/${role.id}/permissions`" class="text-green-600 hover:underline">
                      Permissions
                    </Link>
                    <button
                      @click="deleteRole(role.id)"
                      class="text-red-600 hover:underline"
                    >
                      Supprimer
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <PaginateComponent :paginator="roles"></PaginateComponent>
      </div>
    </div>
  </dashboard>
</template>

<script setup>
import { ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import PaginateComponent from '../components/PaginateComponent.vue';
import dashboard from "../dashboard/dashboard.vue";
const { props } = usePage();
const roles = ref(props.roles);

const deleteRole = (id) => {
  if (confirm("Voulez-vous vraiment supprimer ce rôle ?")) {
    router.delete(route('roles.destroy', id), {
      onSuccess: () => {
        // Met à jour la liste des rôles après suppression
        roles.value = roles.value.filter((role) => role.id !== id);
      },
      onError: (errors) => {
        // Affiche une erreur en cas d'échec
        console.error("Erreur lors de la suppression :", errors);
        alert("Une erreur s'est produite lors de la suppression du rôle.");
      },
    });
  }
};
</script>
