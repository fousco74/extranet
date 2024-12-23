<script setup>
import logoUrl from '/public/icons/lineLogoutBlack.png';
import InputComponent from '../../components/InputComponent.vue';
import ButtonComponent from '../../components/ButtonComponent.vue';
import PaginateComponent from '../../components/paginateComponent.vue';

import NavBarComponent from '../../components/NavBarComponent.vue';
</script>

<template>
    <NavBarComponent :routeName="$page.props.routeName"></NavBarComponent>

  <div class="p-8 bg-gray-50 min-h-screen">
    <h1 class="text-xl font-bold text-gray-700 mb-6">Vos dossiers</h1>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div v-if="$page.props.oneDriveLinks.length === 0" class="text-center text-gray-500">
        <p>Aucun lien onedrive trouvé.</p>
      </div>
      <div v-else
        v-for="oneDrive in $page.props.oneDriveLinks.data"
        :key="oneDrive.id"
        class="group bg-white border py-2 border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow  flex flex-col items-center"
      >

      <a :href="oneDrive.link" >
        <div class="w-32 bg-blue-100 rounded-full flex justify-center items-center mb-4">
          <img src="/public/icons/oneDriveIcon.png" alt="Folder Icon" class="w-16 h-16">
        </div>
      </a>

        <h3 class="text-gray-700 text-center text-sm font-medium group-hover:text-blue transition-colors">
          {{ oneDrive.name }}
        </h3>
      </div>
    </div>
    <PaginateComponent :paginator="$page.props.oneDriveLinks"></PaginateComponent>
  </div>
</template>
