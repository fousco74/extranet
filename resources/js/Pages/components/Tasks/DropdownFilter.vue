<template>
    <div class="relative" >
      <button  class=" px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition flex items-center">
        <i :class="icon + ' mr-2'"></i>{{ label }}
        <i class="fas fa-chevron-down ml-2 text-xs"></i>
      </button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const props = defineProps({
    icon: String,
    label: String,
  })
  
 
  
  </script>

  <style>
</style>
  