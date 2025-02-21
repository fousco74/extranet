<template>
      <div class="bg-white rounded-lg shadow-lg p-2 h-[86%] relative">        
        <h2 class="text-sm  text-center my-4"><span class="font-bold">Vue d'ensemble des réservations du</span> <br><span>{{ day }} {{ month }} {{ year }}</span></h2>
    
        <div class="grid grid-cols-2 gap-3">
          <div 
            v-for="hour in timeSlots" 
            :key="hour.time"
            :class="[
              'text-center py-1 rounded cursor-pointer',
              hour.isReserved && !hour.isPast ? 'bg-gradient-pink text-white' : 'bg-white rounded shadow text-black',
            ]"
          >
            {{ hour.time }}
          </div>
        </div>
      </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  
  const props = defineProps(['reservations', 'day', 'month', 'year', 'monthNumber', 'dayName']);

    // Initialisation de la date actuelle
const today = new Date();

const currentDay = ref(today.getDate()); // Jour actuel
const currentMonth = ref(today.getMonth() + 1); // Mois actuel (0-indexé, donc +1)
const currentYear = ref(today.getFullYear()); // Année actuelle
const currentHour = ref(today.getHours()); // Heure actuelle
const currentMinute = ref(today.getMinutes()); // Minute actuelle
  
  const timeSlots = computed(() => {
  const times = [];
  
  // Créer les créneaux horaires de 9h à 18h, avec les demis
  for (let h = 9; h <= 18; h++) {
    times.push({ time: `${h}:00`, isReserved: false, isPast: false });
    if (h !== 18) times.push({ time: `${h}:30`, isReserved: false, isPast: false });
  }
  
  // Marquer les heures réservées
  props.reservations.forEach(res => {
    const start = parseFloat(res.startClock.replace(':', '.'));
    const end = parseFloat(res.endClock.replace(':', '.'));
    times.forEach(slot => {
      const slotTime = parseFloat(slot.time.replace(':', '.'));
      if (slotTime >= start && slotTime <= end) slot.isReserved = true;
    });
  });
  
  // Marquer les créneaux passés
  times.forEach(slot => {
    const [slotHour, slotMinute] = slot.time.split(":").map(Number);

    // Créer un objet Date pour le créneau horaire
    const slotDate = new Date(props.year, props.monthNumber, props.day, slotHour, slotMinute);

    // Créer un objet Date pour l'heure actuelle
    const currentDate = new Date(
      currentYear.value,
      currentMonth.value - 1, // Soustraction de 1 car le mois est indexé à partir de 0
      currentDay.value,
      currentHour.value,
      currentMinute.value
    );

    // Comparer la date actuelle avec le créneau horaire
    if (slotDate.getTime() < currentDate.getTime()) {
      slot.isPast = true; // Marque comme passé si la date du créneau est avant l'heure actuelle
    }
  });

  return times;
});


  </script>
  
  <style scoped>
  /* Ajout de styles spécifiques au popup */
  </style>
  