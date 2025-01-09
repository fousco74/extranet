<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] p-6 sm:p-10 h-full bg-white mx-auto">
      <div class="flex justify-center mb-6 sm:mb-10">
        <h1 class="text-blue text-2xl sm:text-3xl">Ajout d'un Fichier</h1>
      </div>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

import Dashboard from './../dashboard/dashboard.vue';

const props = defineProps({
  folders: Object,
});

const form = useForm({
  file_link: '',
  folder_id: null,
});

const fileChange = (file) => {
  form.file_link = file.target.files[0];
};

const submit = () => {
  form.post('/files');
};
</script>