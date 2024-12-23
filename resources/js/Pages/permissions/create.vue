<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] md:w-[60%] lg:w-[50%] p-6 sm:p-10 bg-white mx-auto">
      <div class="flex justify-center mb-10">
        <h1 class="text-blue text-3xl text-center sm:text-left">Ajouter une Permission</h1>
      </div>
      <form @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-4">
          <InputComponent
            name="name"
            type="text"
            :errors="$page.props.errors.name"
            placeholder="Nom de la Permission"
            v-model="form.name"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
        </div>
        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 mt-6">
          <ButtonComponent
            content="Ajouter Permission"
            customClass="bg-blue text-white px-4 py-2 w-full sm:w-auto"
          />
          <ButtonComponent
            content="Annuler"
            customClass="bg-gray-500 text-white px-4 py-2 w-full sm:w-auto"
            type="reset"
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

const form = useForm({
  name: '', // Champ de nom de la permission
});

const submit = () => {
  form.post(route('permissions.store'));
};
</script>
