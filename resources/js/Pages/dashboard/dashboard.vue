<script setup>
import { ref } from 'vue';
import InputComponent from '../components/InputComponent.vue';
import ButtonComponent from '../components/ButtonComponent.vue';
import logoUrl from '/public/icons/lineLogoutBlack.png';

const openIndex = ref(null);

const isSidebarVisible = ref(false); // Contrôle de la visibilité du panneau latéral
const toggleSidebar = () => {
  isSidebarVisible.value = !isSidebarVisible.value;
};

import { Link } from '@inertiajs/vue3';
import Notification from '../users/notification.vue';
import NotificationComponent from '../components/NotificationComponent.vue';
import NavBarComponent from '../components/NavBarComponent.vue';

const menu = ref([
  {
    name: 'Utilisateurs',
    icon: 'mdi-account',
    submenu: [
      { name: 'Créer un utilisateur', link: 'users.create' }, // Nom de la route
      { name: 'Liste des utilisateurs', link: 'users.index' }, // Nom de la route
    ],
  },
  {
    name: 'Dossiers',
    icon: 'mdi-folder',
    submenu: [
      { name: 'Créer un dossier', link: 'folders.create' },
      { name: 'Liste des dossiers', link: 'folders.index' },
    ],
  },
  {
    name: 'Fichiers',
    icon: 'mdi-file',
    submenu: [
      { name: 'ajouter un fichier', link: 'files.create' },
      { name: 'Liste des fichiers', link: 'files.index' },
    ],
  },
  {
    name: 'Liens OneDrives',
    icon: 'mdi-microsoft-onedrive',
    submenu: [
      { name: 'Créer un lien oneDrive', link: 'one-drive-links.create' },
      { name: 'Liste des liens oneDrives', link: 'one-drive-links.index' },
    ],
  },
  {
    name: 'Applications',
    icon: 'mdi-apps-box',
    submenu: [
      { name: 'Ajouter une Application', link: 'applications.create' },
      { name: 'Liste des Applications', link: 'applications.index' },
    ],
  },
  {
    name: 'Reservations',
    icon: 'mdi-order-bool-descending-variant',
    submenu: [
      { name: 'Liste des Reservations', link: 'reservations.index' },
    ],
  },
  {
    name: 'Notifications',
    icon: 'mdi-bell-ring',
    submenu: [
      { name: 'Envoyer une notification', link: 'vue.notification' },
    ],
  },
  {
    name: 'Paramètre',
    icon: 'mdi-cog',
    submenu: [
      { name: 'Roles', link: 'roles.index' },
      { name: 'Permissions', link: 'permissions.index' },
    ],
  }
]);

const toggleMenu = (index) => {
  openIndex.value = openIndex.value === index ? null : index;
};



const showNotify = ref(false);
</script>

<template>
  <div class="flex">
   

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r dark:bg-gray-800 dark:border-gray-700 transition-transform duration-300',
        isSidebarVisible ? 'translate-x-0' : '-translate-x-full',
        'sm:translate-x-0',
      ]"
    >
      <div class="h-full px-4 py-6 overflow-hidden">
        <div class="flex flex-col items-center mb-6">
          <a :href="route('dashboard.analytics')">
            <img src="/public/logos/amoamanBlack.png" class="h-12 mb-3" alt="Logo" />
          </a>
          <span class="text-lg font-semibold text-gray-800 dark:text-white">Amoaman & Associés</span>
        </div>
        <ul>
          <li>
            <button class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-left text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
              <span class="flex items-center gap-2">
                <span class="iconify text-4xl" data-icon="mdi-view-dashboard"></span>
                <a :href="route('dashboard.analytics')"><span>Dashboard</span></a>
              </span>
            </button>
          </li>
          <li v-for="(item, index) in menu" :key="index">
            <button
              @click="toggleMenu(index)"
              v-if="$page.props.auth.user.permissionsVoir.some(p => p.toUpperCase().includes(item.name.toUpperCase()))"
              class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-left text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
            >
              <span class="flex items-center gap-2">
                <span class="iconify text-4xl" :data-icon="item.icon"></span>
                <span>{{ item.name }}</span>
              </span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                class="w-5 h-5 transition-transform"
                :class="{ 'rotate-180': openIndex === index }"
                viewBox="0 0 20 20"
              >
                <path d="M10 12l-5-5h10l-5 5z" />
              </svg>
            </button>
            <ul
              v-if="openIndex === index"
              class="pl-6 mt-2 space-y-1 text-gray-700 dark:text-gray-300"
            >
              <li
                v-for="(subitem, subIndex) in item.submenu"
                :key="subIndex"
              >
                <a
                  v-if="$page.props.auth.user.permissions.some(p => p.toUpperCase().includes(subitem.name.toUpperCase()))"
                  :href="route(subitem.link)"
                  class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                  {{ subitem.name }}
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </aside>

    <!-- Contenu principal -->
    <div class="p-0 sm:ml-64 flex-1 bg-gray-50">
      <!-- Barre de navigation -->
  <NavBarComponent :dashboard="true" @toggleSidebar="toggleSidebar" :routeName="$page.props.routeName"></NavBarComponent>
      <!-- Zone de contenu -->
      <div class="h-screen w-full flex justify-center mt-8">
        <slot></slot>
      </div>
    </div>
  </div>
</template>
