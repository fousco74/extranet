<template>
    <div class="bg-gray-100 min-h-screen p-8 mt-0">
      <!-- Header Section -->
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-blue-600">Découvrez Nos Applications</h1>
        <p class="text-gray-600 mt-2">
          Explorez notre gamme d'applications conçues pour simplifier votre quotidien et répondre à vos besoins.
        </p>
      </div>


      <!-- Applications Grid -->
      <div class="grid sm:grid-cols-3 lg:grid-cols-4 gap-4 max-sm:gap-5 place-self-center ">
        <div
          v-for="app in apps.data"
          :key="app.id"
          class="bg-white  shadow-lg flex flex-col size-[250px] rounded-lg justify-center items-center overflow-hidden hover:shadow-xl transition-shadow"
        >

          <div class="flex justify-center w-28  items-center">
            <!-- Application Logo -->
            <img
                :src="`storage/${app.logo}`"
                :alt="`Logo de ${app.name}`"
                class="w-full object-cover"
            />
          </div>

          <!-- Application Info -->
          <div class="p-4 flex flex-col">
            <h2 class="text-xl font-semibold text-gray-800">{{ app.name }}</h2>
            <p class="text-gray-600 mt-2">{{ app.description }}</p>
            <button
              @click="goToApp(app.link)"
              class="mt-2 bg-blue text-white px-4 py-2 rounded hover:bg-blue-700"
            >
              accéder
            </button>
          </div>
        </div>
      </div>
      <paginateComponent :paginator="$page.props.apps"></paginateComponent>

    </div>
  </template>
<script setup>
import { ref } from 'vue';
import paginateComponent from './PaginateComponent.vue';

// Liste des applications (peut être récupérée dynamiquement via une API)
const props = defineProps(["apps"]);

const goToApp = (link) => {
  window.open(link, '_blank');  // Ouvre le lien dans un nouvel onglet
};
</script>
