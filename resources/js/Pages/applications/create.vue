<template>
  <Dashboard>
    <div class="border shadow-md w-[80%] p-6 bg-white">
      <div class="flex justify-center mb-10">
        <h1 class="text-blue text-3xl">Ajout d'une Application</h1>
      </div>

      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <InputComponent
            name="name"
            v-model="form.name"
            :errors="$page.props.errors.name"
            placeholder="Nom de l'application"
             divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="logo"
            type="file"
            :errors="$page.props.errors.logo"
            @input="handleFileChange"
            placeholder="Sélectionner le logo"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="link"
            v-model="form.link"
            :errors="$page.props.errors.link"
            placeholder="Lien vers l'application"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <textarea
            name="description"
            v-model="form.description"
            placeholder="Description de l'application"
            class="px-8 py-1 outline-none rounded-sm shadow-sm border bg-lightGray bg-opacity-75 h-20"
          />
        </div>

          <ButtonComponent content="Ajouter l'application" customClass="bg-blue text-white px-4" />
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
  name: null,
  logo: null,
  description: null,
  link: null,
});

const handleFileChange = (event) => {
  form.logo = event.target.files[0];
};

const submit = () => {
  form.post(route('applications.store'));
};
</script>
