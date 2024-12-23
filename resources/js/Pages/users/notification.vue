<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] mx-auto p-8 bg-white rounded-md">
      <div
        v-if="$page.props.flash.success"
        class="text-white bg-green-600 w-full text-center p-2 my-4 rounded mb-4"
      >
        {{ $page.props.flash.success }}
      </div>
      <div class="text-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Envoyer une Notification</h1>
        <p class="text-gray-600 mt-2">Renseignez les informations ci-dessous pour envoyer une notification.</p>
      </div>
      <form @submit.prevent="submit">
        <div class="grid gap-6">
          <!-- Champ pour le titre -->
          <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
            Titre de la Notification
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-10 justify-between items-center">
            <InputComponent
              id="title"
              name="title"
              type="text"
              :errors="$page.props.errors.title"
              placeholder="Titre de la notification"
              v-model="form.title"
              divClass="w-full"
              inputClass="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
            <select v-model="form.equipe" class="border rounded p-2 w-full sm:w-auto">
              <option value="tous" :selected="form.equipe == 'tous'">Tous</option>
              <option value="interne" :selected="form.equipe == 'interne'">Internes</option>
              <option value="externe" :selected="form.equipe == 'externe'">Externes</option>
            </select>
          </div>

          <!-- Champ pour le message -->
          <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
              Message de la Notification
            </label>
            <textarea
              id="message"
              v-model="form.message"
              placeholder="Écrivez le message ici..."
              rows="5"
              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
            <p v-if="$page.props.errors.message" class="text-sm text-red-600 mt-1">
              {{ $page.props.errors.message }}
            </p>
          </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-center sm:justify-end space-x-4 mt-8">
          <ButtonComponent
            content="Envoyer"
            customClass="bg-blue text-white px-6 py-2 rounded-md shadow hover:bg-blue-700 transition"
          />
          <ButtonComponent
            content="Annuler"
            customClass="bg-gray-400 text-white px-6 py-2 rounded-md shadow hover:bg-gray-500 transition"
            type="reset"
          />
        </div>
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import InputComponent from '../Components/InputComponent.vue';
import ButtonComponent from '../Components/ButtonComponent.vue';
import Dashboard from '../dashboard/dashboard.vue';

// Initialisation du formulaire
const form = useForm({
  title: '', // Nouveau champ pour le titre
  message: '', // Champ pour le message de la notification
  equipe: 'tous'
});

// Fonction pour envoyer les notifications
const submit = () => {
  form.post(route('send.notification'), {
    onSuccess: () => {
      // Réinitialiser le formulaire en cas de succès
      form.reset();
      // Afficher une notification de succès (peut être un toast, modal, ou autre)
      alert('Notification envoyée avec succès !');
    },
  });
};
</script>
