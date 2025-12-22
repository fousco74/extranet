<template>
  <div class="mb-4 flex items-center border px-2 py-[3px] gap-0 shadow rounded">

    <div class="w-fit">
      <img :src="timeLogo" alt="icon" class="w-full object-cover" />
    </div>

    <select
      class="w-full py-2 border-none rounded outline-none"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
    >
      <option value="">Choisir une heure</option>

      <option
        v-for="option in options"
        :key="option.time"
        :value="option.time"
      >
        {{ option.time }}
      </option>
    </select>

    <span v-if="error" class="text-red-500 text-xs ml-2">
      {{ error }}
    </span>
  </div>
</template>

<script setup>
import timeLogo from "../../../../public/icons/time.svg";

defineProps({
  modelValue: String,   // ← LA BONNE VALEUR LIÉE AU v-model
  error: String,
  options: Array,
});

defineEmits(["update:modelValue"]);
</script>
