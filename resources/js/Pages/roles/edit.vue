<template>
  <Dashboard>
    <div class="border shadow-md w-full md:w-[60%] h-screen overflow-auto p-10 bg-white mx-auto">
      <h4 class="text-xl font-semibold mb-6">Modifier un Rôle</h4>
  
      <div class="bg-white shadow-md rounded p-6">
        <form @submit.prevent="submit">
          <div v-if="errors.length" class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
              <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
          </div>
  
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              class="mt-1 py-3 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
              required
            />
          </div>
  
          <div class="flex justify-end space-x-4">
            <button type="submit" class="bg-blue text-white px-4 py-2 rounded shadow hover:bg-blue-600">
              Modifier Rôle
            </button>
            <button type="button" @click="resetForm" class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">
              Annuler
            </button>
          </div>
        </form>
      </div>
    </div>
  </Dashboard>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Dashboard from "../dashboard/dashboard.vue";

const props = defineProps(['role']);

const form = useForm({ name: props.role.name });
const errors = ref([]);

const submit = () => {
  form.put(route('roles.update', props.role.id), {
    onError: (errorBag) => {
      errors.value = Object.values(errorBag).flat();
    },
  });
};

const resetForm = () => {
  form.reset();
};
</script>
