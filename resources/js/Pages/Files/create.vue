<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] p-6 sm:p-10 h-full bg-white mx-auto">
      <div class="flex justify-center mb-6 sm:mb-10">
        <h1 class="text-blue text-2xl sm:text-3xl">Ajout d'un Fichier</h1>
      </div>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="space-y-6">
          <InputComponent
            name="file_link"
            type="file"
            :errors="$page.props.errors.file_link"
            placeholder="Sélectionner le fichier"
            @input="fileChange"
            divClass="w-full border"
            inputClass="w-full"
          />
          <SelectComponent
            name="folders"
            :options="folders"
            :errors="$page.props.errors.folders"
            v-model="form.folder_id"
            placeholder="Sélectionner un Dossier"
            divClass="w-full"
          />
          <div class="flex justify-center">
            <ButtonComponent
              content="Créer le fichier"
              customClass="bg-blue text-white px-6 py-2"
            />
          </div>
        </div>
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import InputComponent from '../components/InputComponent.vue';
import SelectComponent from '../components/SelectComponent.vue';
import ButtonComponent from '../components/ButtonComponent.vue';
import Dashboard from '../dashboard/dashboard.vue';

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
  form.post(route('files.store'));
};
</script>