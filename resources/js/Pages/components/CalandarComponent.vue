<template>
    <div class="w-full  pb-1">
      <div class="flex  justify-between text-center">
        <button @click="prevMonth" class="ml-5 w-auto">
           <img src="/public/icons/arrow-left.png" alt="" class="size-full object-cover">
        </button>
        <h2 class="text-lg font-bold text-gray-800">{{ monthName }} {{ year }}</h2>

        <button @click="nextMonth" class="mr-5 w-auto">
          <img src="/public/icons/arrow-right.png" alt="" class="size-full object-cover">
        </button>
      </div>
  
      <div class="grid grid-cols-7 gap-2 text-center mx-2">
        <div v-for="day in daysOfWeek" :key="day" class="font-semibold text-gray-600">
          {{ day }}
        </div>
        
        <div
          v-for="(date, index) in calendarDays"
          :key="index"
          :class="[
            'flex items-center justify-center rounded-full',
            date.isCurrentMonth ? 'bg-white text-black' : 'bg-gray-100 text-gray-400',
            date.isToday ? 'border  bg-gradient-to-custom text-white' : '',
            'hover:bg-lightGray  cursor-pointer'
          ]"
          @click="selectDate(date)"
        >
          {{ date.date }}
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { router } from '@inertiajs/vue3';
  import { ref, computed } from 'vue';
  import dayjs from 'dayjs';
  
  const currentDate = ref(dayjs());
  const selectedDate = ref(null);
  
  const year = computed(() => currentDate.value.year());
  const month = computed(() => currentDate.value.month());
  const monthName = computed(() => currentDate.value.format('MMMM'));
  const daysOfWeek = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
  
  const calendarDays = computed(() => {
    const startOfMonth = currentDate.value.startOf('month').day(); // Jour de début du mois
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
  
  const nextMonth = () => {
    currentDate.value = currentDate.value.add(1, 'month');
  };
  
  const prevMonth = () => {
    currentDate.value = currentDate.value.subtract(1, 'month');
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
  </script>
  
  <style scoped>
  /* Ajout de styles spécifiques */
  </style>
  