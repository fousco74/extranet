<template>
  <dashboard>
    <div class="border shadow-md w-[90%] md:w-[60%] mx-auto p-10 bg-white">
      <h1 class="text-2xl font-bold mb-6">Détails de la Réservation</h1>
      <p><strong>Titre:</strong> {{ reservation.title }}</p>
      <p><strong>Description:</strong> {{ reservation.description }}</p>
      <p><strong>Date:</strong> {{ reservation.dayName }}, {{ reservation.day }} {{ reservation.month }} {{ reservation.year }}</p>
      <p><strong>Heure Début:</strong> {{ reservation.startClock }}</p>
      <p><strong>Heure Fin:</strong> {{ reservation.endClock }}</p>
      <div class="mt-4">
        <Link
          href="route('reservations.index')"
          class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
        >
          Retour
        </Link>
      </div>
    </div>
  </dashboard>
</template>

<script setup>
import dashboard from '../dashboard/dashboard.vue';

// Définir les props
const Props = defineProps({
  reservation: Object,
});
</script>
