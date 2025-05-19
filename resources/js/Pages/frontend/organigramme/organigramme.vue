<script setup>
import NavBarComponent from '../../components/NavBarComponent.vue';

</script>

<template>
  <NavBarComponent></NavBarComponent>
  <div class="p-8  bg-white flex flex-col gap-4 min-h-screen lg:justify-center lg:items-center">
    <h1 class="text-2xl font-bold text-gray-700 text-center mb-8">
      Organigramme
    </h1>
    <div class="h-screen lg:w-[900px]  w-full organigramme"></div>
  </div>
</template>

<style scoped>
.organigramme{
    background-image: url('/public/background/organigramme.webp');
    background-repeat: no-repeat;
    background-size:contain;
}
</style>
