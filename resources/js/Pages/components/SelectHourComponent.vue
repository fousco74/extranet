<template>
    <div class="mb-4 flex items-center border px-2 py-[3px] gap-0 shadow rounded">
        <div class="w-fit">
            <img :src="timeLogo" alt="icon" class="w-full object-cover">
        </div>
      <select
        v-model="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        class="w-full  py-2 overflow-auto border-none rounded focus:outline-none"
      >
        <option v-for="time in timeOptions" :key="time" :value="time">
          {{ time }}
        </option>
      </select>
    </div>
  </template>


  <script setup>
  import { ref } from 'vue';
  import timeLogo from  '../../../../public/icons/time.svg'

  // Génération des heures et demi-heures entre 09:00 et 18:00
  const generateTimeOptions = () => {
    const options = [];
    for (let hour = 9; hour <= 18; hour++) {
      options.push(`${String(hour).padStart(2, '0')}:00`);
      if (hour < 18) options.push(`${String(hour).padStart(2, '0')}:30`);
    }
    return options;
  };

  const modelValue = defineModel({
  type: [String, Number],
  default: null,
});

// Ajout de la prop `error` pour gérer les messages d'erreur
defineProps({
  error: {
    type: String,
    default: null,
  },
});
  const timeOptions = ref(generateTimeOptions()); // Liste des heures disponibles
  </script>
