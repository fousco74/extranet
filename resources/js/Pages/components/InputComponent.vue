<script setup>
import { defineProps } from 'vue';
const model = defineModel();

const props = defineProps({
  name: {
    type: String,
    required: true, // Nom requis pour éviter des erreurs d'accessibilité
  },
  type: {
    type: String,
    default: 'text', // Par défaut, un champ texte
  },
  label: {
    type: String,
  },
  placeholder: {
    type: String,
    default: '', // Placeholder vide si aucun n'est fourni
  },
  inputClass: {
    type: String,
    default: '', // Classe CSS personnalisée pour l'input
  },
  divClass: {
    type: String,
    default: '', // Classe CSS personnalisée pour le conteneur div
  },
 
  errors: {
    type: Array,
    default: () => [], // Liste des erreurs associées au champ
  },
});


</script>

<template>
  <div :class="divClass">
    <label :for="name" v-if="label">{{ label }}</label>
    <!-- Champ d'entrée -->
    <input
      :type="type"
      :name="name"
      :id="name"
      v-model="model"
      @input="updateValue"
      :placeholder="placeholder"
      :class="`px-8 py-1 outline-none rounded-sm  shadow-sm border bg-lightGray bg-opacity-75 ${inputClass} ${
        errors.length > 0  ? 'border-red-500' : ''
      }`"
    />
    <!-- Affichage des erreurs -->
    <div v-if="errors.length" class="text-red-500">
      {{ errors }}
    </div>
  </div>
</template>
