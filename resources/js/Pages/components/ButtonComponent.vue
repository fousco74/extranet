<script setup>
import { defineProps } from 'vue';



const props = defineProps({
  content: {
    type: String,
    required: true, // Contenu requis pour le bouton
  },
  logoUrl: {
    type: String,
    required: false, // L'URL du logo est optionnelle
  },
  alt: {
    type: String,
    default: '', // Texte alternatif par défaut
  },
  customClass: {
    type: String,
    default: '', // Classe de largeur par défaut
  },
  divClass: {
    type: String,
    default: '', // Classe de largeur par défaut
  }
});
</script>

<template>
  <button
    :class="`text-center   rounded-sm py-1 ${customClass}`"
    :aria-label="content"
  >
    <div :class="`inline-flex space-x-2 ${divClass} items-center`">
      <span>{{ content }}</span>
      <img v-if="logoUrl" :src="logoUrl" :alt="alt" />
    </div>
  </button>
</template>
