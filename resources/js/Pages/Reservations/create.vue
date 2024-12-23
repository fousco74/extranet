<template>
  <dashboard>
    <div class="border shadow-md w-[90%] md:w-[60%] mx-auto p-10 bg-white">
      <h1 class="text-2xl font-bold mb-6">Ajouter une Réservation</h1>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="title" class="block text-gray-700 font-bold">Titre</label>
          <input type="text" v-model="form.title" class="border w-full p-2" />
        </div>
        <div class="mb-4">
          <label for="description" class="block text-gray-700 font-bold">Description</label>
          <textarea v-model="form.description" class="border w-full p-2"></textarea>
        </div>
        <div class="mb-4">
          <label for="day" class="block text-gray-700 font-bold">Jour</label>
          <input type="number" v-model="form.day" class="border w-full p-2" />
        </div>
        <div class="mb-4">
          <label for="month" class="block text-gray-700 font-bold">Mois</label>
          <input type="text" v-model="form.month" class="border w-full p-2" />
        </div>
        <div class="mb-4">
          <label for="year" class="block text-gray-700 font-bold">Année</label>
          <input type="number" v-model="form.year" class="border w-full p-2" />
        </div>
        <div class="mb-4">
          <label for="startClock" class="block text-gray-700 font-bold">Heure Début</label>
          <input type="time" v-model="form.startClock" class="border w-full p-2" />
        </div>
        <div class="mb-4">
          <label for="endClock" class="block text-gray-700 font-bold">Heure Fin</label>
          <input type="time" v-model="form.endClock" class="border w-full p-2" />
        </div>
        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
          <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Enregistrer
          </button>
          <Link href="route('reservations.index')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Annuler
          </Link>
        </div>
      </form>
    </div>
  </dashboard>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import dashboard from '../dashboard/dashboard.vue';

// Initialiser le formulaire
const form = useForm({
  title: '',
  description: '',
  day: '',
  month: '',
  year: '',
  startClock: '',
  endClock: '',
});

// Soumettre les données
const submit = () => {
  form.post(route('reservations.store'));
};
</script>
