<template>
  <dashboard>
    <div class="border shadow-md w-[80%] p-10 bg-white">
      <div class="flex justify-between mb-6">
        <h1 class="text-blue text-3xl">Liste des Applications</h1>
        <Link :href="route('applications.create')" class="bg-blue text-white px-4 py-2 rounded">Ajouter une application</Link>
      </div>
      <div class="flex justify-center items-center mb-4">
          <span v-if="$page.props.flash.message" class="text-center bg-orange-700 bg-opacity-25">
                  {{ $page.props.flash.message  }}
        </span>
      </div>
      <div class="space-y-4">
        <div v-for="application in applications.data" :key="application.id" class="flex justify-between items-center border p-4">
          <div class="flex items-center">
            <img :src="'/storage/' + application.logo" alt="Logo" class="w-12 h-12 mr-4" />
            <div>
              <h2 class="font-semibold">{{ application.name }}</h2>
              <p class="text-sm text-gray-600">{{ application.description }}</p>
            </div>
          </div>
          <div class="space-x-4">
            <Link :href="route('applications.edit', application.id)" class="text-blue">Éditer</Link>
            <button @click="deleteApplication(application.id)" class="text-red-500">Supprimer</button>
          </div>
        </div>
      </div>
      <PaginateComponent :paginator="applications"></PaginateComponent>

    </div>
  </dashboard>
</template>

<script setup>
import { defineProps } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import dashboard from '../dashboard/dashboard.vue';
import PaginateComponent from '../components/PaginateComponent.vue';


const props = defineProps({
  applications: Object,
});
const form = useForm({});


const deleteApplication = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette application ?')) {
    form.delete(route('applications.destroy', id));
  }
};
</script>
