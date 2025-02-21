<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  logoUrl: {
    type: String,
    required: true, // URL du logo obligatoire
  },
  alt: {
    type: String,
    default: 'img', // Texte alternatif par défaut
  },
  content: {
    type: String,
    required: true, // Contenu requis
  },
  customClass: {
    type: String,
    default: '', // Largeur par défaut
  },
  imgWiddth: {
    type: String,
    default: 'w-[150px]', // Largeur par défaut
  },
});
</script>

<template>
  <div :class="`text-center flex justify-center p-3 ${customClass}  rounded-lg cursor-pointer`">
    <div class="p-1 flex  items-center justify-between">
      <div v-if="logoUrl" :class="`${imgWiddth} justify-center items-center`">
        <img :src="logoUrl" :alt="alt" class="max-w-full h-auto" />
        <span class="text-[10px] text-blue font-medium">{{ content }}</span>
      </div>
    </div>
  </div>
</template>
