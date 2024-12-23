<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center cursor-pointer z-50">
    <div class="bg-white py-6 relative px-14 rounded-md shadow-md cursor-default w-[45%]">
      <img class="absolute top-6 right-8 cursor-pointer" @click="$emit('close')" src="../../../../public//icons/quick.png" alt="Fermer">
      <div class="flex text-center items-center mb-4 gap-1">
        <img :src="reserverLogo" alt="reserver" />
        <h2 class="text-lg font-semibold text-blue_white">Réserver la salle</h2>
      </div>
      <!-- Flash message global -->
      <div
        v-if="$page.props.flash.message"
        class="text-white bg-red-700 w-full text-center p-2 rounded mb-4"
      >
        {{ $page.props.flash.message }}
      </div>
      <div
        v-if="$page.props.flash.success"
        class="text-white bg-green-600 w-full text-center p-2 rounded mb-4"
      >
        {{ $page.props.flash.success }}
      </div>
      <form @submit.prevent="submitReservation">
        <!-- Champ Titre -->
        <div class="mb-8 border-b border-blue border-opacity-75">
          <input
            type="text"
            v-model="title"
            class="w-full border-none outline-none"
            placeholder="Ajouter un titre"
          />
          <span v-if="$page.props.errors.title" class="text-red-500 text-sm">
            {{ $page.props.errors.title }}
          </span>
        </div>
        <!-- Section Heures -->
        <div class="mb-16 flex border-b border-blue border-opacity-75 h-fit gap-3">
          <div class="w-42 h-12 rounded border text-center py-3 px-2 bg-white shadow">
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
        <div class="mb-8 border-b border-blue border-opacity-75">
          <textarea
            v-model="description"
            name="description"
            class="w-full h-10 border-none outline-none"
            placeholder="Description"
          />
          <span
            v-if="$page.props.errors.description"
            class="text-red-500 text-sm"
          >
            {{ $page.props.errors.description }}
          </span>
        </div>
        <!-- Boutons -->
        <div class="flex justify-end">
          <button
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-300 mr-2"
          >
            Annuler
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue text-white rounded hover:bg-blue-500"
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
