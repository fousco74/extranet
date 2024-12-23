<script setup>
import NavBarComponent from '../../components/NavBarComponent.vue';

</script>

<template>
  <NavBarComponent></NavBarComponent>
  <div class="p-8 bg-gray-50 flex flex-col gap-4 min-h-screen">
    <h1 class="text-2xl font-bold text-gray-700 text-center mb-8">
      Organigramme
    </h1>
    <div class="h-screen organigramme w-full"></div>
  </div>
</template>

<style scoped>
.organigramme{
    background-image: url('/public/background/organigramme.png');
    background-repeat: no-repeat;
    background-size: contain;
}
</style>
