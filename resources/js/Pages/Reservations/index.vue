<template>
  <dashboard>
    <div class="border shadow-md w-full md:w-[80%] h-screen overflow-auto p-10 bg-white">
      <div class="flex justify-center mb-10">
        <h1 class="text-2xl font-bold">Liste des Réservations</h1>
      </div>
      <div class="flex justify-center items-center mb-4">
        <Link
          href="route('reservations.create')"
          class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
        >
          Ajouter une Réservation
        </Link>
      </div>
      <div
        v-if="$page.props.flash.success"
        class="text-white bg-green-600 w-full text-center p-2 rounded mb-4"
      >
        {{ $page.props.flash.success }}
      </div>
      <div v-if="reservations.length === 0" class="text-center text-gray-500">
        <p>Aucune réservation trouvée.</p>
      </div>

      <table v-else class="table-auto w-full border-collapse border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2 text-left">Titre</th>
            <th class="border p-2 text-left">Reserver par</th>
            <th class="border p-2 text-left">Description</th>
            <th class="border p-2 text-left">Jour</th>
            <th class="border p-2 text-left">Heure Début</th>
            <th class="border p-2 text-left">Heure Fin</th>
            <th class="border p-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="reservation in reservations.data" :key="reservation.id" class="hover:bg-gray-50">
            <td class="border p-2">{{ reservation.title }}</td>
            <td class="border p-2">{{ reservation.user.first_name }} {{ reservation.user.last_name }}</td>
            <td class="border p-2">{{ reservation.description }}</td>
            <td class="border p-2">{{ reservation.dayName }}, {{ reservation.day }} {{ reservation.month }} {{ reservation.year }}</td>
            <td class="border p-2">{{ reservation.startClock }}</td>
            <td class="border p-2">{{ reservation.endClock }}</td>
            <td class="border p-2 flex space-x-2">
              <Link
                :href="route('reservations.show', reservation.id)"
                class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600"
              >
                Detail
              </Link>
              <button
                @click="destroy(reservation.id)"
                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600"
              >
                Supprimer
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <PaginateComponent :paginator="reservations"></PaginateComponent>
    </div>
  </dashboard>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import dashboard from '../dashboard/dashboard.vue';
import PaginateComponent from '../components/PaginateComponent.vue';

// Définir les props pour recevoir les réservations
const Props = defineProps({
  reservations: Array
});

// Initialiser le formulaire pour supprimer une réservation
const form = useForm({});

// Fonction pour confirmer et supprimer une réservation
const destroy = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
    form.delete(route('reservations.destroy', id));
  }
};
</script>
