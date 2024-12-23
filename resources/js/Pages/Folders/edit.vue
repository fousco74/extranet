<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] md:w-[60%] lg:w-[50%] xl:w-[40%] p-6 sm:p-8 md:p-10 bg-white">
      <div class="flex justify-center mb-6 sm:mb-8 md:mb-10">
        <h1 class="text-blue text-2xl sm:text-3xl lg:text-4xl">Modifier le Dossier</h1>
      </div>
      <form @submit.prevent="submit">
        <div class="space-y-4">
          <InputComponent
            name="name"
            type="text"
            placeholder="Nom du Dossier"
            v-model="form.name"
            divClass="mb-4 w-full border"
            inputClass="w-full"
          />
          
          <ButtonComponent
            content="Modifier le Dossier"
            customClass="bg-blue text-white px-4 py-2 sm:px-6 sm:py-3 lg:px-8 lg:py-4"
          />
        </div>
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import InputComponent from '../Components/InputComponent.vue';
import ButtonComponent from '../Components/ButtonComponent.vue';
import Dashboard from '../dashboard/dashboard.vue';

const props = defineProps({
  folder: Object
})
const form = useForm({
  id: props.folder.id,
  name: props.folder.name 
});

const submit = () => {
  form.put(`/folders/${form.id}`);
};
</script>
