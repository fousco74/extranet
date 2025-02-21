<template>
  <dashboard>
    <div class="border shadow-md w-full sm:w-[80%] mx-auto mt-10 p-10 bg-white rounded-lg">
      <div class="flex flex-col items-center mb-10">
        <h1 class="text-2xl font-bold">Gérer les Rôles</h1>
        <p class="text-gray-500 mt-2">Utilisateur : {{ user.first_name }} {{ user.last_name }}</p>
      </div>

      <form @submit.prevent="updateRoles" class="space-y-6">
        <h2 class="text-lg font-semibold mb-4">Sélectionner les rôles</h2>

        <!-- Grid des rôles -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          <div v-for="role in roles" :key="role.id" class="flex items-center space-x-3">
            <input
              type="checkbox"
              :id="'role_' + role.id"
              :value="role.id"
              v-model="selectedRoles"
              class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            />
            <label :for="'role_' + role.id" class="text-gray-700">
              {{ role.name }}
            </label>
          </div>
        </div>

        <button
          type="submit"
          class="bg-blue text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-300"
        >
          Mettre à jour
        </button>
      </form>
    </div>
  </dashboard>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';
import dashboard from '../dashboard/dashboard.vue';

const Props = defineProps({
  user: Object, 
  roles: Array, 
  userRoles: Array
});

const selectedRoles = ref(Props.user.roles.map((role) => role.id)); // Pré-remplir les rôles sélectionnés

const form = useForm({
  roles: selectedRoles.value,
});

// Fonction pour mettre à jour les rôles
const updateRoles = () => {
  form.roles = selectedRoles.value;
  form.put(route('user.updateRoles', Props.user.id), {
    onSuccess: () => {
      alert('Les rôles ont été mis à jour avec succès.');
    },
  });
};
</script>

<style>
/* Ajoutez des styles personnalisés si nécessaire */
</style>
