<template>
  <Dashboard>
    <div class="py-6 px-8">
      <h4 class="text-lg font-semibold mb-4">
        <span class="text-gray-500">Roles /</span> Gérer les Permissions
      </h4>
  
      <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
          <h5 class="text-xl font-semibold">Gérer les permissions pour le rôle : {{ role.name }}</h5>
        </div>
        <div class="p-6">
          <form @submit.prevent="updatePermissions">
            <div v-for="(permissions, category) in permissionsByCategory" :key="category" class="mb-6 space-y-6">
              <div>
                <h6 class="font-semibold text-gray-700 mb-3">{{ category }}</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <div
                    v-for="permission in permissions"
                    :key="permission.id"
                    class="flex items-center space-x-2"
                  >
                    <input
                      type="checkbox"
                      :id="`permission_${permission.id}`"
                      :value="permission.id"
                      v-model="selectedPermissions"
                      class="form-checkbox h-5 w-5 text-blue-600"
                    />
                    <label :for="`permission_${permission.id}`" class="text-gray-700">
                      {{ permission.name }}
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-6 flex justify-end">
              <button
                type="submit"
                class="px-6 py-3 bg-blue text-white rounded hover:bg-blue-700"
              >
                Mettre à jour
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Dashboard>
</template>
  
<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Dashboard from "../dashboard/dashboard.vue";

// Props reçues depuis le composant parent
const props = defineProps({
  role: Object,
  permissionsByCategory: Object,
  rolePermissions: Array,
});

// Gestion des permissions sélectionnées
const selectedPermissions = ref(props.rolePermissions.map((permission) => permission.id));

// Utilisation du formulaire pour les mises à jour
const form = useForm({
  permissions: selectedPermissions.value,
});

// Méthode pour mettre à jour les permissions
const updatePermissions = () => {
  form.permissions = selectedPermissions.value;

  form.put(route("role.updatePermissions", props.role.id), {
    onSuccess: () => {
      alert("Permissions mises à jour avec succès.");
    },
    onError: (errors) => {
      console.error("Erreur lors de la mise à jour :", errors);
      alert("Une erreur s'est produite lors de la mise à jour des permissions.");
    },
  });
};
</script>

<style scoped>
/* Ajoutez des styles spécifiques ici si nécessaire */
</style>
