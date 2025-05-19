<template>
    <li class="w-full cursor-pointer">
      <a
        :href="url"
        class="flex flex-col w-full items-center justify-center md:p-2 rounded "
      >
        <img
          :src="`/icons/${icon}.svg`"
          :alt="icon"
          class="w-5 h-5 md:w-6 md:h-6 mb-1"
        />
        <span class="whitespace-nowrap text-[10px] md:text-sm">{{ name }}</span>
    </a>
    </li>
  </template>
  
  <script setup>
  defineProps({
    name: String,
    url: String,
    icon: String,
  })
  </script>
  