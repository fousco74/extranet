<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-3/4 md:w-2/3 lg:w-[60%] xl:w-[50%] p-10 bg-white mx-auto">
      <div class="flex justify-center mb-10">
        <h1 class="text-blue text-3xl">Ajouter un Lien OneDrive</h1>
      </div>
      <form @submit.prevent="submit">
        <div class="space-y-4">
          <InputComponent
            name="link"
            type="text"
            :errors="$page.props.errors.link"
            placeholder="Lien OneDrive"
            v-model="form.link"
            divClass="mb-4 w-full border"
            inputClass="w-full"
          />
          <InputComponent
            name="name"
            type="text"
            :errors="$page.props.errors.name"
            placeholder="Nom du dossier"
            v-model="form.name"
            divClass="mb-4 w-full border"
            inputClass="w-full"
          />
          <ButtonComponent
            content="Créer le Lien OneDrive"
            customClass="bg-blue text-white px-4 py-2 w-full sm:w-auto"
          />
        </div>
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import InputComponent from '../components/InputComponent.vue';
import ButtonComponent from '../components/ButtonComponent.vue';
import Dashboard from '../dashboard/dashboard.vue';

const form = useForm({
  link: null,
  name : null
});

const submit = () => {
  form.post('/one-drive-links');
};
</script>
