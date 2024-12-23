<template>
  <dashboard>
    <div class="border shadow-md w-full sm:w-[80%] h-screen overflow-auto p-6 sm:p-10 bg-white mx-auto">
      <div class="mb-6">
        <h4 class="text-gray-700 text-2xl font-semibold">
          <span class="text-gray-500">Permissions /</span> Liste
        </h4>
      </div>
      <div class="bg-white shadow rounded-lg">
        <div class="flex justify-between items-center px-6 py-4 border-b">
          <h5 class="text-lg font-semibold text-gray-800">Liste des permissions</h5>
          <Link
            :href="route('permissions.create')"
            class="bg-blue text-white px-4 py-2 rounded"
          >
            Nouveau
          </Link>
        </div>

        <div class="p-6 space-y-6">
          <div v-for="(permissions, category) in permissionsByCategory" :key="category" class="mb-6">
            <h6 class="text-lg font-semibold mb-4" v-if="permissions.length">{{ category }}</h6>
            <table class="w-full text-left border border-gray-300 rounded" v-if="permissions.length != 0">
              <thead>
                <tr class="bg-gray-100">
                  <th class="p-3 border-b font-semibold">Libellé</th>
                  <th class="p-3 border-b font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="permission in permissions"
                  :key="permission.id"
                  class="hover:bg-gray-50"
                >
                  <td class="p-3 border-b">{{ permission.name }}</td>
                  <td class="p-3 border-b">
                    <div class="relative">
                      <button
                        type="button"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        @click="toggleDropdown(permission.id)"
                      >
                        drop
                      </button>
                      <div
                        v-if="isDropdownOpen(permission.id)"
                        class="absolute right-0 top-10 bg-white border border-gray-200 rounded shadow z-10 w-40"
                      >
                        <Link
                          :href="`/permissions/${permission.id}/edit`"
                          class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600"
                        >
                          <i class="mdi mdi-pencil-outline mr-2"></i> Edit
                        </Link>
                        <button
                          @click="deletePermission(permission.id)"
                          class="block w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100"
                        >
                          Delete
                        </button>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </dashboard>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import dashboard from '../dashboard/dashboard.vue';

const props = defineProps({
  permissionsByCategory: Object, // Données groupées par catégorie
});

const dropdowns = ref({});

const toggleDropdown = (id) => {
  dropdowns.value[id] = !dropdowns.value[id];
};

const isDropdownOpen = (id) => {
  return dropdowns.value[id] || false;
};

const deletePermission = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette permission ?')) {
    // Inertia supprimera la permission
    router.delete(`/permissions/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        alert('Permission supprimée avec succès.');
      },
    });
  }
};
</script>
