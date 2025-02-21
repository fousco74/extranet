<template>
    <div class="w-full lg:w-[55%] rounded-md h-auto lg:h-[86%] pb-3 bg-white">
  <!-- En-tête avec navigation -->
  <div class="flex flex-wrap lg:flex-nowrap items-center justify-between pb-0 mb-3 px-3 lg:px-5">
    <div class="flex justify-between gap-4 items-center text-center pt-3">
      <button @click="prevMonth" class="ml-2 lg:ml-5 w-auto">
        <img src="/public/icons/arrow-left.png" alt="" class="h-6 w-6 object-cover">
      </button>
      <h2 class="text-md lg:text-lg font-bold text-blue_black">{{ monthName }} {{ year }}</h2>
      <button @click="nextMonth" class="mr-2 lg:mr-5 w-auto">
        <img src="/public/icons/arrow-right.png" alt="" class="h-6 w-6 object-cover">
      </button>
    </div>
    <div class="flex justify-between gap-3 lg:gap-5 pt-3 items-center w-full lg:w-auto">
      <div class="flex items-center gap-2 border text-center py-2 px-2 rounded bg-white shadow">
        <img :src="calandarLogo" alt="calendar" class="h-6 w-6">
        <span class="text-sm lg:text-base">{{ selectedDay }} {{ monthName }} {{ yearSelect }}</span>
      </div>
      <ButtonComponent
        @click="showReservationModal"
        :logoUrl="plusLogo"
        content="Réserver la salle"
        customClass="border cursor-pointer shadow py-2 px-4 rounded text-[10px] lg:text-[12px]"
      />
    </div>
  </div>

  <!-- Grille des jours et calendrier -->
  <div class="grid grid-cols-7 gap-2 px-2 lg:px-5 items-center text-start mx-2 mt-6 lg:mt-12">
    <div
      v-for="day in daysOfWeek"
      :key="day"
      class="font-semibold text-xs lg:text-sm text-gray-600"
    >
      {{ day }}
    </div>
    <div
      v-for="(date, index) in calendarDays"
      :key="index"
      :class="[ 
        'h-[40px] lg:h-[54px] py-1 lg:py-[3px] px-1 lg:px-2 border shadow flex items-start justify-start',
        date.isCurrentMonth ? 'bg-white text-black' : 'bg-gray-100 text-gray-400',
        'hover:bg-lightGray cursor-pointer',
        date.date == selectedDay && date.isCurrentMonth ? 'border-pink border text-pink' : ''
      ]"
      @click="selectDate(date)"
    >
      <span
        :class="[ date.isToday ? 'px-2 py-1 border text-white bg-blue_white rounded text-xs lg:text-sm' : '' ]"
      >
        {{ date.date }}
      </span>
    </div>
  </div>

  <!-- Modale pour réservation -->
  <ReservationModal
    v-if="isModalVisible"
    :message="$page.props.flash.message"
    :day="selectedDay"
    :month="monthName"
    :monthDays="monthDays"
    :dayName="dayName"
    :monthNumber="monthNumber"
    :year="year"
    @close="close"
  />
</div>

  </template>
  
  <script setup>
  import { router } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  import dayjs from 'dayjs';
  import SelectCalandarComponent from './SelectCalandarComponent.vue';
  import plusLogo from '../../../../public/icons/plus.png'
  import calandarLogo from '../../../../public/icons/choiseCalandar.png'

 
  const close = () => {
  isModalVisible.value = false;
  window.location.reload();  // This will reload the page after closing the modal
};



import ButtonComponent from './ButtonComponent.vue';
import ReservationModal from './ReservationModal.vue'

  
// Définir les props
const props = defineProps([ "reservations", "daySelect", "monthSelect", "yearSelect", "monthNumber", "detailIsVisible"]);

// Conversion explicite des props en entier pour éviter les erreurs
const monthNumber = parseInt(props.monthNumber, 10); // Mois sélectionné (0-11)
const yearSelect = parseInt(props.yearSelect, 10); // Année sélectionnée (ex. 2024)

// Référence pour le jour sélectionné
const selectedDay = ref(parseInt(props.daySelect, 10)); // Jour sélectionné, converti en entier

// Date actuelle basée sur le mois et l'année sélectionnés
const currentDate = ref(dayjs().month(monthNumber).year(yearSelect));

const selectedDate = ref(null);

// Année et mois extraits de `currentDate`
const year = computed(() => currentDate.value.year());
const month = computed(() => currentDate.value.month()); // 0 = janvier, 1 = février, etc.
const monthName = computed(() => currentDate.value.format('MMMM'));

// Calcul du nom abrégé du jour de la semaine pour la date sélectionnée
const dayName = computed(() => {
  const day = dayjs()
    .month(month.value)  // Mois sélectionné (0-11)
    .year(year.value)    // Année sélectionnée
    .date(selectedDay.value);  // Jour sélectionné (le jour du mois)
  
  // Vérification si la date est valide
  if (!day.isValid()) {
    console.error('Invalid date:', day.format());
    return 'Invalid date';  // Retourner un message d'erreur si la date est invalide
  }

  // Retourner le nom abrégé du jour (Mon, Tue, ...)
  return day.format('ddd');
});

  const daysOfWeek = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
  
  const calendarDays = computed(() => {
    const startOfMonth = currentDate.value.startOf('month').day();
    const daysInMonth = currentDate.value.daysInMonth(); // Nombre de jours dans le mois
    const days = [];
    const startOffset = startOfMonth === 0 ? 6 : startOfMonth - 1;
  
    // Précédent mois
    const prevMonthDays = currentDate.value.subtract(1, 'month').daysInMonth();
    for (let i = startOffset; i > 0; i--) {
      days.push({
        date: prevMonthDays - i + 1,
        isCurrentMonth: false,
      });
    }

   
  
    // Mois courant
    for (let i = 1; i <= daysInMonth; i++) {
      days.push({
        date: i,
        isCurrentMonth: true,
        isToday: currentDate.value.date() === i && currentDate.value.isSame(dayjs(), 'month'),
      });
    }
  
    // Prochain mois
    const totalDays = days.length;
    for (let i = 1; i <= 42 - totalDays; i++) {
      days.push({
        date: i,
        isCurrentMonth: false,
      });
    }
  
    return days;
  });

  const monthDays = computed(() => calendarDays.value.filter(day => day.isCurrentMonth));

  
 
  const nextMonth = () => {
  currentDate.value = currentDate.value.add(1, 'month');
  selectedDay.value = 1; // Mettre le jour sélectionné sur le 1er du mois
  updateSelectedDate();
};

const prevMonth = () => {
  currentDate.value = currentDate.value.subtract(1, 'month');
  selectedDay.value = 1; // Mettre le jour sélectionné sur le 1er du mois
  updateSelectedDate();
};

// Fonction pour mettre à jour la date sélectionnée et naviguer vers la nouvelle date
const updateSelectedDate = () => {
  const data = {
    date: selectedDay.value,
    month: monthName.value,
    year: year.value,
    monthNumber: month.value,
  };

  // Navigue vers la nouvelle date
  router.get('/reservation/date', data);
};

  
  const selectDate = (date) => {
    if (date.isCurrentMonth) {
      selectedDate.value = date.date;
      const data = {
        date : date.date,
        month: monthName.value,
        year: year.value,
        monthNumber: month.value
      }

      router.get('/reservation/date', data);
    }
  };


  const isModalVisible = ref(false);
  
  const showReservationModal = () => {
    isModalVisible.value = true;
  };
  </script>
  
  <style scoped>
  /* Ajout de styles spécifiques */
  </style>
  