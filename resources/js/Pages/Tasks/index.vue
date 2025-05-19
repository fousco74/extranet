<template>
    <FrontendContent>
      <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h1 class="text-3xl font-bold text-gray-800 mb-1">
                {{ selectedProject.title || 'Tous les projets' }}
              </h1>
              <p  class="text-lg" v-if="selectedProject.nature">Nature : <span class="bg-red-300 text-white">{{ selectedProject.nature }}</span></p>
              <p class="text-gray-600">
                <span v-if="state.projectFilter">Tâches pour le projet</span>
                <span v-else>Tâches pour tous les projets</span>
              </p>
            </div>

            <!-- Sélecteur de projet -->
            <div class="relative w-64">
              <select
                v-model="state.projectFilter"
                class="w-full pl-4 pr-8 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none"
              >
                <option :value="null">Tous les projets</option>
                <option
                  v-for="project in projects"
                  :key="project.id"
                  :value="project.id"
                >
                  {{ project.title }}
                </option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                <i class="fas fa-chevron-down text-gray-400"></i>
              </div>
            </div>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="flex items-center bg-gray-50 px-4 py-2 rounded-lg">
                <i class="far fa-calendar text-gray-500 mr-2"></i>
                <span class="text-sm font-medium text-gray-700">{{ formattedDate }}</span>
              </div>
            </div>
          </div>
        </header>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
          <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                <i class="fas fa-tasks"></i>
              </div>
              <div>
                <p class="text-gray-500">Tâches totales</p>
                <h3 class="text-2xl font-bold">{{ stats.total }}</h3>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <i class="fas fa-clock"></i>
              </div>
              <div>
                <p class="text-gray-500">En cours</p>
                <h3 class="text-2xl font-bold">{{ stats.inprogress }}</h3>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <i class="fas fa-clock"></i>
              </div>
              <div>
                <p class="text-gray-500">En pause</p>
                <h3 class="text-2xl font-bold">{{ stats.on_hold }}</h3>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <i class="fas fa-clock"></i>
              </div>
              <div>
                <p class="text-gray-500">À Faire</p>
                <h3 class="text-2xl font-bold">{{ stats.todo }}</h3>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
              <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <i class="fas fa-check-circle"></i>
              </div>
              <div>
                <p class="text-gray-500">Terminées</p>
                <h3 class="text-2xl font-bold">{{ stats.done }}</h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
          <div class="flex flex-wrap items-center gap-3">
            <h3 class="text-lg font-semibold text-gray-700 mr-4">Filtrer par :</h3>
            <button
              class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
              @click="resetFilters"
            >
              <i class="fas fa-list-ul mr-2"></i>Tout voir
            </button>

            <!-- Priorité -->
            <div class="dropdown relative" ref="priorityDropdown">
              <button @click="toggleDropdown('priority')" class="dropdown-toggle px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition flex items-center">
                <i class="fas fa-flag mr-2"></i>Priorité
                <i class="fas fa-chevron-down ml-2 text-xs"></i>
              </button>
              <div :class="dropdownClass('priority')">
                <a href="#" @click.prevent="setFilter('priority', 'high')" class="dropdown-item">Haute</a>
                <a href="#" @click.prevent="setFilter('priority', 'medium')" class="dropdown-item">Moyenne</a>
                <a href="#" @click.prevent="setFilter('priority', 'low')" class="dropdown-item">Basse</a>
              </div>
            </div>

            <!-- Date -->
            <div class="dropdown relative" ref="dateDropdown">
              <button @click="toggleDropdown('date')" class="dropdown-toggle px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>Date
                <i class="fas fa-chevron-down ml-2 text-xs"></i>
              </button>
              <div :class="dropdownClass('date')">
                <a href="#" @click.prevent="setFilter('date', 'today')" class="dropdown-item">Aujourd'hui</a>
                <a href="#" @click.prevent="setFilter('date', 'week')" class="dropdown-item">Cette semaine</a>
                <a href="#" @click.prevent="setFilter('date', 'month')" class="dropdown-item">Ce mois</a>
              </div>
            </div>

            <!-- Statut -->
            <div class="dropdown relative" ref="statusDropdown">
              <button @click="toggleDropdown('status')" class="dropdown-toggle px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition flex items-center">
                <i class="fas fa-check-circle mr-2"></i>Statut
                <i class="fas fa-chevron-down ml-2 text-xs"></i>
              </button>
              <div :class="dropdownClass('status')">
                <a href="#" @click.prevent="setFilter('status', 'todo')" class="dropdown-item">À faire</a>
                <a href="#" @click.prevent="setFilter('status', 'inprogress')" class="dropdown-item">En cours</a>
                <a href="#" @click.prevent="setFilter('status', 'done')" class="dropdown-item">Terminée</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des tâches -->
        <section>
          <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <span class="w-2 h-5 bg-blue-500 rounded-full mr-3"></span>
            {{ filteredTasks.length }} tâche(s) trouvée(s)
          </h2>
          <div v-if="filteredTasks.length" class="grid grid-cols-1 gap-4">
            <TaskCard v-for="task in filteredTasks" :key="task.id" :task="task" />
          </div>
          <div v-else class="text-center py-12 bg-gray-50 rounded-lg">
            <i class="fas fa-tasks text-3xl text-gray-400 mb-4"></i>
            <p class="text-gray-500">Aucune tâche correspond aux critères</p>
          </div>
        </section>
      </div>
    </FrontendContent>
  </template>

  <script setup>
  import { ref, reactive, computed } from 'vue';
  import TaskCard from '../components/Tasks/TaskCard.vue';
  import FrontendContent from '../dashboard/frontendContent.vue';
  import { onClickOutside } from '@vueuse/core';

  const props = defineProps({
    tasks: Array,
    projects: Array,
    users: Array
  });

  const dropdownRefs = {
    priority: ref(null),
    date: ref(null),
    status: ref(null)
  };

  const openDropdown = ref(null);

  const toggleDropdown = (type) => {
    openDropdown.value = openDropdown.value === type ? null : type;
  };

  const dropdownClass = (type) =>
    `dropdown-menu absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 ${
      openDropdown.value === type ? 'block' : 'hidden'
    }`;

  Object.entries(dropdownRefs).forEach(([key, refEl]) => {
    onClickOutside(refEl, () => {
      if (openDropdown.value === key) openDropdown.value = null;
    });
  });

  const state = reactive({
    priorityFilter: null,
    dateFilter: null,
    statusFilter: null,
    projectFilter: null
  });

  const setFilter = (type, value) => {
    state[`${type}Filter`] = value;
    openDropdown.value = null;
  };

  const resetFilters = () => {
    state.priorityFilter = null;
    state.dateFilter = null;
    state.statusFilter = null;
    state.projectFilter = null;
  };

  const formattedDate = computed(() =>
    new Date().toLocaleDateString('fr-FR', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  );

  const isToday = (dateStr) => {
    const today = new Date();
    const date = new Date(dateStr);
    return (
      today.getFullYear() === date.getFullYear() &&
      today.getMonth() === date.getMonth() &&
      today.getDate() === date.getDate()
    );
  };

  const selectedProject = computed(() => {
    if (!state.projectFilter) return 'Tous les projets';
    const project = props.projects.find(p => p.id === state.projectFilter);
    return project || 'Projet inconnu';
  });

  const sortedTasks = computed(() => {
    const priorityOrder = { high: 1, medium: 2, low: 3 };
    const statusOrder = { todo: 1, inprogress: 2, done: 3 };

    return [...props.tasks].sort((a, b) => {
      const dateA = new Date(a.delais);
      const dateB = new Date(b.delais);

      return (
        dateA - dateB ||
        priorityOrder[a.priority] - priorityOrder[b.priority] ||
        statusOrder[a.status] - statusOrder[b.status]
      );
    });
  });

  const filteredTasks = computed(() => {
    return sortedTasks.value.filter((task) => {
      const projectMatch = !state.projectFilter || task.project_id === state.projectFilter;
      const priorityMatch = !state.priorityFilter || task.priority === state.priorityFilter;
      const statusMatch = !state.statusFilter || task.status === state.statusFilter;

      let dateMatch = true;
      if (state.dateFilter === 'today') dateMatch = isToday(task.dueDate);
      else if (state.dateFilter === 'week') {
        const now = new Date();
        const startOfWeek = new Date(now.setDate(now.getDate() - now.getDay()));
        const endOfWeek = new Date(now.setDate(startOfWeek.getDate() + 6));
        const dueDate = new Date(task.dueDate);
        dateMatch = dueDate >= startOfWeek && dueDate <= endOfWeek;
      } else if (state.dateFilter === 'month') {
        const now = new Date();
        const dueDate = new Date(task.dueDate);
        dateMatch = now.getMonth() === dueDate.getMonth() && now.getFullYear() === dueDate.getFullYear();
      }

      return projectMatch && priorityMatch && statusMatch && dateMatch;
    });
  });

  const stats = computed(() => ({
    total: filteredTasks.value.length,
    inprogress: filteredTasks.value.filter(t => t.status === 'inprogress').length,
    done: filteredTasks.value.filter(t => t.status === 'done').length,
    on_hold: filteredTasks.value.filter(t => t.status === 'on_hold').length,
    todo: filteredTasks.value.filter(t => t.status === 'todo').length
  }));
  </script>

  <style scoped>
  .dropdown-menu {
    transition: all 0.3s ease-in-out;
  }

  .dropdown-item {
    display: block;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    color: #4a5568;
    cursor: pointer;
  }

  .dropdown-item:hover {
    background-color: #edf2f7;
  }

  select {
    transition: all 0.2s ease;
  }

  select:hover {
    border-color: #cbd5e0;
  }

  select:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
  }
  </style>
