<script setup>
import logoUrl from '/public/icons/lineLogoutBlack.png';
import InputComponent from '../../components/InputComponent.vue';
import ButtonComponent from '../../components/ButtonComponent.vue';

import { Link } from '@inertiajs/vue3';
import NavBarComponent from '../../components/NavBarComponent.vue';
import PaginateComponent from '../../components/paginateComponent.vue';

</script>

<template>
    <NavBarComponent :routeName="$page.props.routeName"></NavBarComponent>

  <div class="p-8 bg-gray-50 min-h-screen">
    <h1 class="text-xl font-bold text-gray-700 mb-6">Vos dossiers </h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div v-if=" $page.props.folders.length === 0" class="text-center text-gray-500">
        <p>Aucun dossier trouvé.</p>
      </div>
      <div v-else
        v-for="folder in $page.props.folders.data"
        :key="folder.id"
        class="group bg-white border py-2 border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow  flex flex-col items-center"
      >

      <Link :href="route('folder.files',folder.id)" method="get">
        <div class="w-24bg-blue-100 rounded-full flex justify-center items-center mb-4">
          <img src="/public/icons/folders.png" alt="Folder Icon" class="w-16 h-16">
        </div>
      </Link>

        <h3 class="text-gray-700 text-center text-sm font-medium group-hover:text-blue transition-colors">
          {{ folder.name }}
        </h3>
      </div>
    </div>
    <PaginateComponent :paginator="$page.props.folders"></PaginateComponent>

  </div>
</template>
