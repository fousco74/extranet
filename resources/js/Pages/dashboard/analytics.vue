

<template>
  <Dashboard>
    <div class="border shadow-md w-[80%] p-10 bg-white">
        <div class="flex justify-between mb-6 ">
        <h1 class="text-blue text-3xl px-4 py-2 ">Dashboard Analytics</h1>
      </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
    <!-- Carte pour les Fichiers -->
    <div class="bg-blue-500 text-black p-6 rounded-lg shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-file-alt text-4xl mr-4"></i>
            <div>
                <h3 class="text-xl font-semibold">Fichiers</h3>
                <p class="text-2xl">{{ fileCount }}</p>
            </div>
        </div>
    </div>

    <!-- Carte pour les Dossiers -->
    <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-folder text-4xl mr-4"></i>
            <div>
                <h3 class="text-xl font-semibold">Dossiers</h3>
                <p class="text-2xl">{{ folderCount }}</p>
            </div>
        </div>
    </div>

    <!-- Carte pour les Liens OneDrive -->
    <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-cloud-upload-alt text-4xl mr-4"></i>
            <div>
                <h3 class="text-xl font-semibold">Liens OneDrive</h3>
                <p class="text-2xl">{{ oneDriveLinkCount }}</p>
            </div>
        </div>
    </div>

    <!-- Carte pour les Utilisateurs -->
    <div class="bg-red-500 text-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-users text-4xl mr-4"></i>
            <div>
                <h3 class="text-xl font-semibold">Utilisateurs</h3>
                <p class="text-2xl text-white">{{ userCount }}</p>
            </div>
        </div>
    </div>
</div>
    </div>
  </Dashboard>
</template>

<script setup>

const props = defineProps({
    fileCount: String,
    folderCount: String,
    oneDriveLinkCount: String,
    userCount: String
});

import Dashboard from '../dashboard/dashboard.vue';


</script>
