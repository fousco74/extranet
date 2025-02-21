<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] p-10 bg-white mx-auto">
      <h1 class="text-blue text-3xl mb-6">Modifier les Liens OneDrive de {{ user.first_name }} {{ user.last_name }}</h1>
      <div v-if="allLinks.length === 0" class="text-center text-gray-500">
        <p>Aucun lien onedrive trouvé.</p>
      </div>
      <form v-else @submit.prevent="submit">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div v-for="link in allLinks" :key="link.id" class="flex items-center">
            <input
              type="checkbox"
              :id="`link-${link.id}`"
              :value="link.id"
              v-model="checkedNames"
              class="mr-2"
            />
            <label :for="`link-${link.id}`">{{ link.name }}</label>
          </div>
        </div>
        <button
          type="submit"
          class="bg-blue text-white px-4 py-2 rounded mt-6 hover:bg-blue-600 transition duration-300"
        >
          Enregistrer
        </button>
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import Dashboard from '../dashboard/dashboard.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

// Props passées depuis le backend
const props = defineProps({
  user: Object,
  allLinks: Array,
  userLinks: Array,  // Le tableau des liens associés à l'utilisateur
});

// Référence pour stocker les liens sélectionnés
const checkedNames = ref([]);

// Synchronisation des liens associés à l'utilisateur avec checkedNames
watchEffect(() => {
  checkedNames.value = [...props.userLinks]; // Met à jour checkedNames avec les liens associés à l'utilisateur
});

// Initialisation du formulaire avec Inertia.js
const form = useForm({
  links: checkedNames, // Liaison des liens sélectionnés au formulaire
});

// Fonction pour soumettre le formulaire
const submit = () => {
  form.links = checkedNames.value; // Mettez à jour la valeur des liens avant l'envoi
  form.put(route('users.onedrive.update', props.user.id));
};
</script>
