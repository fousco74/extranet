<template>
  <div :class="divClass">
    <label :for="name" class="block text-sm font-medium text-gray-700">{{ placeholder }}</label>
    <select
      :name="name"
      v-model="modelValue"
      :class="inputClass"
      @change="$emit('update:modelValue', $event.target.value)"
    >
      <option value="" disabled>{{ placeholder }}</option>
      <option
        v-for="option in options"
        :key="option.id"
        :value="option.id"
      >
        {{ option.name }}
      </option>
    </select>
    <span v-if="error" class="text-red-500 text-sm">{{ error }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue';


// Définir les propriétés du composant
const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  options: {
    type: Array,
    required: true,
  },
  placeholder: {
    type: String,
    default: 'Select an option',
  },
  divClass: {
    type: String,
    default: '',
  },
  error: {
    type: String,
    default: '',
  },
});

// Ajouter la prise en charge de v-model via modelValue
const modelValue = defineModel({
  type: [String, Number],
  default: null,
});

// Classe de style pour l'élément select
const inputClass = computed(() => {
  return 'block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm';
});
</script>

<style scoped>
select {
  width: 100%;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  border: 1px solid #d1d5db;
  font-size: 1rem;
  color: #4b5563;
}
select:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 1px #6366f1;
}
</style>
