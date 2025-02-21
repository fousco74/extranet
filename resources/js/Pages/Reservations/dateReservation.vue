<template>
    <NavBarComponent></NavBarComponent>
    <div class="w-screen h-screen bg-gradient-linear-4colors">
      
      <div class="lg:h-full w-full flex max-md:flex-col justify-center gap-2 px-3 pt-5 border-red border-4">
          <DateReservationDetail
          :day="day"
          :month="month"
          :year="year"
          :dayName="monthName"
          :monthNumber="monthNumber"
          :reservations="reservations"
          @close="closeDetail"
        />
        <FullCalandarComponent :detailIsVisible="detailIsVisible" :reservations="reservations" :daySelect="day" :monthSelect="month" :yearSelect="year" :monthNumber="monthNumber"></FullCalandarComponent>
        <div class="lg:h-[86%] bg-white rounded-md py-8 px-5 overflow-y-auto space-y-3">
          <!-- Vérifier si les réservations sont vides -->
          <div v-if="reservations.length === 0" class="px-2 text-sm">
            <p class="text-center text-gray-500">Aucune réservation disponible.</p>
          </div>
          
          <!-- Affichage des réservations lorsque non vide -->
          <div v-for="reservation of reservations" :key="reservation.id" 
              :class="[
                  isPastEndTime(reservation) ? 'border-l-[12px] border-l-red-600' : 'border-l-[12px] border-l-blue_white',
                ]"
              class="flex flex-col text-blue_black px-2 text-sm border rounded shadow-sm">
            
            <span class="font-semibold">{{ reservation.title }}</span>
            <span class="text-[12px]">{{ reservation.user.first_name }} {{ reservation.user.last_name }} . {{ reservation.startClock }} à {{ reservation.endClock }}</span>
          </div>
        </div>
       

      </div>
    </div>
  </template>
  
  <script setup>
  import { computed, ref } from "vue";
  import { usePage } from "@inertiajs/vue3";
  import ReservationModal from "../components/ReservationModal.vue";
  import DateReservationDetail from "../components/DateReservationDetail.vue";
import NavBarComponent from "../components/NavBarComponent.vue";
import FullCalandarComponent from "../components/FullCalandarComponent.vue";

const { props } = usePage();
const { reservations, day, month, year, monthNumber, detailIsVisible } = props;

// Initialisation de la date actuelle
const today = new Date();

const currentDay = ref(today.getDate()); // Jour actuel
const currentMonth = ref(today.getMonth() + 1); // Mois actuel (0-indexé, donc +1)
const currentYear = ref(today.getFullYear()); // Année actuelle
const currentHour = ref(today.getHours()); // Heure actuelle
const currentMinute = ref(today.getMinutes()); // Minute actuelle



  const isPastEndTime = (reservation) => {
  // Créer des objets Date pour la réservation et l'heure actuelle
  const currentDate = new Date(
    currentYear.value,
    currentMonth.value - 1, // Soustraction de 1 pour que janvier soit 0
    currentDay.value,
    currentHour.value,
    currentMinute.value
  );

  const reservationEndDate = new Date(
    reservation.year,
    reservation.monthNumber, // Soustraction de 1 pour que janvier soit 0
    reservation.day,
    ...reservation.endClock.split(":").map(Number) // Extraire heure et minute
  );

  // Afficher les deux dates pour le débogage
  console.log("current : ", currentDate);
  console.log("reservation : ", reservationEndDate);

  // Comparer les timestamps des deux dates
  return currentDate.getTime() > reservationEndDate.getTime();
};




  </script>
  
  <style scoped>
  .container {
    max-width: 800px;
    margin: auto;
  }
  </style>
  