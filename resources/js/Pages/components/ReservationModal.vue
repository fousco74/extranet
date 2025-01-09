<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center cursor-pointer z-50">
    <div class="bg-white py-6 relative px-6 sm:px-10 md:px-14 rounded-md shadow-md cursor-default w-[90%] sm:w-[70%] md:w-[45%] max-h-[90vh] overflow-auto">
      <img
        class="absolute top-4 sm:top-6 right-4 sm:right-8 cursor-pointer w-6 sm:w-8"
        @click="$emit('close')"
        src="../../../../public/icons/quick.png"
        alt="Fermer"
      />
      <div class="flex flex-col sm:flex-row text-center items-center mb-4 gap-2 sm:gap-1">
        <img :src="reserverLogo" alt="reserver" class="w-10 sm:w-12" />
        <h2 class="text-base sm:text-lg font-semibold text-blue_white">
          Réserver la salle
        </h2>
      </div>
      <!-- Flash message global -->
      <div
        v-if="$page.props.flash.message"
        class="text-white bg-red-700 w-full text-center p-2 rounded mb-4 text-sm sm:text-base"
      >
        {{ $page.props.flash.message }}
      </div>
      <div
        v-if="$page.props.flash.success"
        class="text-white bg-green-600 w-full text-center p-2 rounded mb-4 text-sm sm:text-base"
      >
        {{ $page.props.flash.success }}
      </div>
      <form @submit.prevent="submitReservation">
        <!-- Champ Titre -->
        <div class="mb-6 sm:mb-8 border-b border-blue border-opacity-75">
          <input
            type="text"
            v-model="title"
            class="w-full border-none outline-none text-sm sm:text-base"
            placeholder="Ajouter un titre"
          />
          <span v-if="$page.props.errors.title" class="text-red-500 text-xs sm:text-sm">
            {{ $page.props.errors.title }}
          </span>
        </div>
        <!-- Section Heures -->
        <div class="mb-12 sm:mb-16 flex flex-col sm:flex-row border-b border-blue border-opacity-75 h-fit gap-3">
          <div
            class="w-full sm:w-auto sm:w-42 h-12 rounded border text-center py-3 px-2 bg-white shadow text-sm sm:text-base"
          >
            {{ dayName }} {{ day }} {{ monthNumber }} {{ year }}
          </div>
          <SelectHourComponent
            :error="$page.props.errors.startClock"
            v-model="startClock"
          />
          <SelectHourComponent
            :error="$page.props.errors.endClock"
            v-model="endClock"
          />
        </div>
        <!-- Champ Description -->
        <div class="mb-6 sm:mb-8 border-b border-blue border-opacity-75">
          <textarea
            v-model="description"
            name="description"
            class="w-full h-10 border-none outline-none text-sm sm:text-base"
            placeholder="Description"
          ></textarea>
          <span v-if="$page.props.errors.description" class="text-red-500 text-xs sm:text-sm">
            {{ $page.props.errors.description }}
          </span>
        </div>
        <!-- Boutons -->
        <div class="flex justify-end gap-2 sm:gap-4">
          <button
            @click="$emit('close')"
            class="px-3 py-2 sm:px-4 sm:py-2 bg-gray-400 text-white rounded hover:bg-gray-300 text-xs sm:text-sm"
          >
            Annuler
          </button>
          <button
            type="submit"
            class="px-3 py-2 sm:px-4 sm:py-2 bg-blue text-white rounded hover:bg-blue-500 text-xs sm:text-sm"
          >
            Confirmer
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import reserverLogo from "../../../../public/icons/reserver.png";
import SelectHourComponent from "./SelectHourComponent.vue";

const startClock = ref("09:00");
const endClock = ref("09:00");
const title = ref("");
const description = ref("");

const props = defineProps([
  "day",
  "month",
  "year",
  "message",
  "monthNumber",
  "dayName",
]);

const submitReservation = () => {
  router.post("/reservations", {
    day: props.day,
    month: props.month,
    dayName: props.dayName,
    title: title.value,
    description: description.value,
    year: props.year,
    monthNumber: props.monthNumber,
    startClock: startClock.value,
    endClock: endClock.value,
  });
};
</script>
