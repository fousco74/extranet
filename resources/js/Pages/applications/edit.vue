<template>
  <Dashboard>
    <div class="border shadow-md w-[80%] p-10 bg-white">
      <div class="flex justify-center mb-10">
        <h1 class="text-blue text-3xl">Modifier l'application</h1>
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
        <ButtonComponent content="Modifier l'application" customClass="bg-blue text-white px-4" />

      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import InputComponent from '../Components/InputComponent.vue';
import ButtonComponent from '../Components/ButtonComponent.vue';
import Dashboard from '../dashboard/dashboard.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  application: Object,
});

const form = ref({
  name: props.application.name,
  logo: null,
  description: props.application.description,
  link: props.application.link,
});

const handleFileChange = (event) => {
  form.value.logo = event.target.files[0];
};


const submit = () => {
console.log("ok")
const formData = new FormData();
for (const key in form.value) {
  if (!form.value[key]){
      continue
  }
  formData.append(key, form.value[key]);
}

formData.append("_method", "put");

router.post(`/applications/${props.application.id}`, formData, {
forceFormData: true
});

};
</script>
