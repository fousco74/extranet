<template>
    <div class="container mx-auto py-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Réservations pour le {{ day }} {{ month }} {{ year }}
      </h1>
     
  
      <div class="mb-6">
        <button
          @click="showReservationModal"
          class="bg-blue text-white px-4 py-2 rounded shadow hover:bg-blue-500"
        >
          Ajouter une réservation
        </button>

      </div>
      <div class="bg-white shadow rounded-md p-4">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Liste des réservations</h2>
        <div v-if="reservations.length === 0" class="text-gray-600">
          Aucune réservation pour cette journée.
        </div>
        <table v-else class="w-full text-left border-collapse">
          <thead>
            <tr>
              <th class="border-b py-2">Début</th>
              <th class="border-b py-2">Fin</th>
              <th class="border-b py-2">Utilisateur</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="reservation in reservations" :key="reservation.id">
              <td class="py-2 border-b">{{ reservation.startClock }}</td>
              <td class="py-2 border-b">{{ reservation.endClock }}</td>
              <td class="py-2 border-b">{{ reservation.user.email }}</td>
            </tr>
          </tbody>
        </table>
      </div>

  
      <ReservationModal
        v-if="isModalVisible"
        :message="$page.props.flash.message"
        :day="day"
        :month="month"
        :year="year"
        @close="isModalVisible = false"
      />
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import { usePage } from "@inertiajs/vue3";
  import ReservationModal from "../components/ReservationModal.vue";
  const { props } = usePage();
  const { reservations, day, month, year } = props;
  
  const isModalVisible = ref(false);
  
  const showReservationModal = () => {
    isModalVisible.value = true;
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 800px;
    margin: auto;
  }
  </style>
  