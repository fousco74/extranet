<template>
  <Dashboard>
    <div class="border shadow-md w-[80%] p-10 bg-white">
      <div class="flex justify-center mb-10">
        <h1 class="text-blue text-3xl">Modifier le Fichier</h1>
      </div>
      <form @submit.prevent="submit">
        <div class="space-y-4">
          <InputComponent
            name="file_link"
            type="file"
            placeholder="selectionner le fichier"
            :errors="$page.props.errors.file_link"
            @change="fileChange"
            divClass="mb-4 w-full border"
            inputClass="w-full"
          />
          <SelectComponent
            name="folders"
            :options="folders"
            v-model="form.folder_id"
            :errors="$page.props.errors.folders"
            placeholder="Sélectionner un Dossier"
            divClass="mb-4"
          />
          <ButtonComponent
            content="Modifier le Fichier"
            customClass="bg-blue text-white px-4"
          />
        </div>
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import InputComponent from './../components/InputComponent.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import SelectComponent from '../Components/SelectComponent.vue';
import ButtonComponent from '../Components/ButtonComponent.vue';
import Dashboard from '../dashboard/dashboard.vue';

const props = defineProps(["file", "folders"]) 


const form = ref({
  file_link: null,
  folder_id: props.file.folder_id,
});

const fileChange = (file) => {
  form.value.file_link = file.target.files[0];
}





const submit = () => {

  const formData = new FormData();
  for (const key in form.value) {
    if (!form.value[key]){
        continue
    }
    formData.append(key, form.value[key]);
  }

  formData.append("_method", "put");

router.post(`/files/${props.file.id}`, formData, {
  forceFormData: true
});

};
</script>
