<template>
    <div class="w-screen h-screen flex flex-col">
      <!-- Barre de navigation -->
      <NavBarComponent />

      <!-- Contenu principal -->
      <div class="w-full h-full bg-gray-50  flex flex-col md:flex-row py-4 md:py-8 px-10 ">
        <!-- Sidebar -->
        <aside class="px-1 md:px-2 rounded-lg h-fit border bg-white w-full md:max-w-fit">
          <ul class="py-4 space-x-4 md:space-x-0 md:space-y-4 justify-center items-center text-[10px] md:text-[10px] flex md:flex-col md:justify-center md:items-center">
            <SidebarItem
              v-for="item in menuItems"
              :key="item.url"
              :url="item.url"
              :name="item.name"
              :icon="item.icon"
            />
          </ul>
        </aside>

        <!-- Slot pour les pages Inertia -->
        <main class="flex  flex-col w-full px-2 md:px-0">
          <slot />
        </main>
      </div>
    </div>
  </template>

  <script setup>
  import NavBarComponent from '../components/NavBarComponent.vue'
  import SidebarItem from './SidebarItem.vue'


  const menuItems = [
    { name: 'Home', url: '/', icon: 'home' },
    { name: 'Projects', url: '/projects', icon: 'projects' },
    { name: 'taches', url: '/tasks', icon: 'tasks' },
    { name: 'Statistiques', url: '/stats', icon: 'stats' },
    { name: 'Contrats', url: '/contracts', icon: 'contract' },
  ]
  </script>
