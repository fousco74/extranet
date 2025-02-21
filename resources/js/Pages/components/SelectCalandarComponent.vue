<template>
  <div :class="divClass">
    <div class="w-fit">
      <img v-if="calandarLogo" :src="calandarLogo" alt="calandar" class="object-cover size-full">
    </div>
    <select
      :name="name"
      v-model="modelValue"
      :class="selectClass"
      @change="$emit('update:modelValue', $event.target.value)"

    >
      <option
        class="bg--white text-gray-700"
        v-for="option in options"
        :key="option.id"
        :value="option.date"
      >
        {{ option.date }} {{ month }} {{ year }}
      </option>
    </select>
  </div>
</template>

<script setup>
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
  divClass: {
    type: String,
    default: '',
  },
  selectClass: {
    type: String,
    default: '',
  },
  month: {
    type: String,
    default: '',
  },
  year: {
    type: String,
    default: '',
  },
  calandarLogo: {
    type: String,
  },
 
});

const  modelValue = defineModel("modelValue");
</script>

<style scoped>
/* Styles optionnels */
</style>
