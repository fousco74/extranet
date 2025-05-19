<template>
    <FrontendContent>
      <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
          <div>
            <h1 class="text-2xl font-bold text-gray-800">Liste des contrats RH</h1>
            <p class="text-gray-600">Gérez et suivez l'état des contrats de vos collaborateurs</p>
          </div>
          <a href="/contracts/create"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fas fa-plus"></i>
            <span>Créer un contrat</span>
        </a>
        </div>

        <!-- Filtres simplifiés -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
          <div class="flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-1/3">
              <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
              <select v-model="statusFilter" id="status"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="all">Tous les statuts</option>
                <option value="signed">Signé</option>
                <option value="unsigned">Non signé</option>
              </select>
            </div>
            <div class="w-full md:w-1/3">
              <label for="collab" class="block text-sm font-medium text-gray-700 mb-1">Collaborateur</label>
              <select v-model="userFilter" id="collab"
                      class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">Tous les collaborateurs</option>
                <option v-for="u in users" :key="u.id" :value="u.id">
                  {{ u.first_name }} {{ u.last_name }}
                </option>
              </select>
            </div>
            <div class="w-full md:w-1/3 flex items-end">
              <button @click="applyFilters"
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors">
                Appliquer les filtres
              </button>
            </div>
          </div>
        </div>

        <!-- Vue tableau desktop -->
        <div class="hidden md:block bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr class="table-header">
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nom du contrat
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Collaborateur
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date de création
                  </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date d'expiration
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        STATURE
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    SIGNATURE
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="contract in filteredContracts" :key="contract.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-wrap">
                    {{ contract.contract_type }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-user"></i>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          {{ contract.assigned_user.first_name }} {{ contract.assigned_user.last_name }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(contract.contract_date) }}
                  </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatDate(contract.expiration_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span :class="contractStatus(contract.expiration_date).classes" class="status-badge">
                          <i :class="contractStatus(contract.expiration_ate).icon" class="mr-1"></i>
                          {{ contractStatus(contract.expiration_date).label }}
                        </span>
                    </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatus(contract).classes" class="status-badge">
                      <i :class="getStatus(contract).icon" class="mr-1"></i>
                      {{ getStatus(contract).label }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                    <a :href="`/contracts/${contract?.id}`" class="text-blue-600 cursor-pointer hover:text-blue-900">Voir</a>

                  </td>
                </tr>
                <tr v-if="filteredContracts.length === 0">
                  <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                    Aucun contrat ne correspond à vos critères de recherche
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Vue mobile -->
        <div class="md:hidden space-y-4">
          <div
            v-for="contract in filteredContracts"
            :key="contract.id"
            class="bg-white shadow border border-gray-200 rounded-lg cursor-pointer hover:shadow-lg transition-shadow"
          >
            <div class="px-4 py-5">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">{{ contract.contract_type }}</h3>
                  <p class="mt-1 text-sm text-gray-500">
                    {{ contract.assigned_user.first_name }} {{ contract.assigned_user.last_name }}
                  </p>
                </div>
                <span :class="getStatus(contract).classes" class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full">
                  <i :class="getStatus(contract).icon" class="mr-1"></i>
                  {{ getStatus(contract).label }}
                </span>
              </div>

              <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                  <p class="font-medium">Créé le</p>
                  <p class="mt-1 text-gray-800">{{ formatDate(contract.contract_date) }}</p>
                </div>
                <div>
                  <p class="font-medium">Expiration</p>
                  <p class="mt-1 text-gray-800">{{ formatDate(contract.expiration_date) }}</p>
                </div>
              </div>
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                    <p class="font-medium">Statut</p>
                    <p class="mt-1 text-gray-800">
                        <span :class="contractStatus(contract.expiration_date).classes" class="status-badge">
                        <i :class="contractStatus(contract.expiration_date).icon" class="mr-1"></i>
                        {{ contractStatus(contract.expiration_date).label }}
                        </span>
                    </p>
                </div>

              <div class="mt-4 flex justify-end space-x-2">
                <a
                 :href="`/contracts/${contract.id}`"
                  class="inline-flex cursor-pointer items-center px-3 py-1.5 border border-gray-300 rounded-md text-blue-600 bg-white hover:bg-gray-50 transition"
                >
                  <i class="fas fa-eye mr-1"></i>Voir
              </a>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </FrontendContent>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import FrontendContent from '@/Pages/Dashboard/FrontendContent.vue';
  import html2pdf from 'html2pdf.js';

  // Props
  const props = defineProps({
    contracts: Array,
    users: Array
  });

  // Filters
  const statusFilter = ref('all');
  const userFilter   = ref('all');

  const contractStatus = (date) =>{
    const today = new Date();
    const expirationDate = new Date(date);
    if (expirationDate < today) {
      return {
        icon: 'fas fa-check-circle',
        label: 'Terminé',
        classes: 'bg-red-100 text-red-800'
      };
    } else {
      return {
        icon: 'fas fa-spinner',
        label: 'En cours',
        classes: 'bg-orange-100 text-orange-800'
      };
    }
  }
  // Apply filters (dummy for now)
  function applyFilters() { /* you can call Inertia to refresh */ }

  // Computed filteredContracts
  const filteredContracts = computed(() => {
    return props.contracts.data.filter(c => {
      const matchStatus = statusFilter.value === 'all'
        || (statusFilter.value === 'signed' && c.signe)
        || (statusFilter.value === 'unsigned' && !c.signe);
      const matchUser = userFilter.value === 'all'
        || c.assigned_to === userFilter.value;
      return matchStatus && matchUser;
    });
  });

  // Utilities
  function formatDate(s) {
    if (!s) return 'N/A';
    return new Date(s).toLocaleDateString('fr-FR');
  }
  function getStatus(c) {
    if (c.signe) {
      return { icon: 'fas fa-check-circle', label: 'Signé', classes: 'bg-green-100 text-green-800' };
    }
    const exp = new Date(c.expiration_date || c.contract_date);
    return exp < new Date()
      ? { icon: 'fas fa-clock', label: 'Terminé', classes: 'bg-red-100 text-red-800' }
      : { icon: 'fas fa-spinner', label: 'En cours', classes: 'bg-orange-100 text-orange-800' };
  }





  </script>

  <style scoped>
  .status-badge { @apply inline-flex items-center px-2 py-1 rounded-full text-sm font-medium; }
  .card-hover:hover { @apply shadow-lg transform -translate-y-1 transition; }
  </style>
