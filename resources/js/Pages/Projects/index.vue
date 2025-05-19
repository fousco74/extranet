<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, reactive, ref, watch } from 'vue'
import FrontendContent from '../dashboard/frontendContent.vue'
import {
  Plus,
  Calendar,
  Eye,
  Edit,
  ChevronLeft,
  ChevronRight,
  Search,
  Clock,
  CheckCircle,
  AlertCircle,
  Loader
} from 'lucide-vue-next'

const props = defineProps({
  projects: {
    type: Object,
    default: () => ({
      data: [
        {
          id: 1,
          name: "Refonte Site Web",
          manager: "Jean Dupont",
          type: "Client",
          status: "En cours",
          start_date: "2023-06-15",
          end_date: "2023-09-30",
          progress: 15
        },
        {
          id: 2,
          name: "Application Mobile",
          manager: "Marie Martin",
          type: "Interne",
          status: "Terminé",
          start_date: "2023-03-01",
          end_date: "2023-05-15",
          progress: 100
        }
      ],
      links: [
        { url: null, label: "&laquo; Previous", active: false },
        { url: "#", label: "1", active: true },
        { url: "#", label: "2", active: false },
        { url: "#", label: "Next &raquo;", active: false }
      ],
      meta: {
        current_page: 1,
        from: 1,
        last_page: 2,
        per_page: 6,
        to: 2,
        total: 8
      }
    })
  }
})

const filters = reactive({
  type: '',
  status: ''
});

console.log(props.projects)
const progressProject = (project) =>{
    const tasksLengh =  project.tasks.length;
    const tasksCompletedLengh = project.tasks.filter((task) => task.status === 'done').length === 0 ? 0 : project.tasks.filter((task) => task.status === 'done').length;
    if (tasksLengh === 0) {
        return 0;
    }
    if (tasksLengh === tasksCompletedLengh) {
        return 100;
    }
    if (tasksLengh < tasksCompletedLengh) {
        return 0;
    }

    return parseInt((tasksCompletedLengh / tasksLengh) * 100);
}

const formattedDate = (date) => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
  return new Date(date).toLocaleDateString('fr-FR', options);
}

const pourcent = ref(40)






const getStatusColor = (status) => {
  switch (status) {
    case "completed":
      return {
        bg: "bg-green-50",
        text: "text-green-600",
        icon: CheckCircle,
        label: "Terminé"
      };

    case "inprogress":
      return {
        bg: "bg-blue-50",
        text: "text-blue-600",
        icon: Loader,
        label: "En cours"
      };

    case "on_hold":
      return {
        bg: "bg-yellow-50",
        text: "text-yellow-600",
        icon: Clock,
        label: "En pause"
      };

    case "planned":
      return {
        bg: "bg-gray-50",
        text: "text-gray-600",
        icon: Clock,
        label: "Prévu"
      };

    default:
      return {
        bg: "bg-gray-100",
        text: "text-gray-500",
        icon: Clock,
        label: "Inconnu"
      };
  }
};


const filterProjects = computed(() => {
  if (!filters.type && !filters.status) {
    return props.projects;
  }

  return {
    ...props.projects,
    data: props.projects.data.filter((project) => {
      const typeMatch = filters.type ? project.nature === filters.type : true;
      const statusMatch = filters.status ? project.status === filters.status : true;
      return typeMatch && statusMatch;
    })
  };
});


const setFilters = (type, value) => {
  filters.value[type] = value;
};

const resetFilters = () => {
  filters.value.type = "";
  filters.value.status = "";
};


</script>

<template>
  <Head title="Liste des Projets" />
  <FrontendContent>
    <div class="min-h-screen bg-gray-50 p-4 md:p-6">
      <!-- Header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Liste des Projets</h1>
        <div v-if="$page.props.auth.user.roles.includes('admin') || $page.props.auth.user.roles.includes('Chef de projet')" class="flex gap-3 w-full md:w-auto">

          <Link
            href="/projects/create"
            class="flex items-center bg-blue_white hover:bg-blue text-white cursor-pointer px-4 py-2 rounded-lg transition-colors whitespace-nowrap"
          >
            <Plus class="w-4 h-4 mr-2" />
            Créer un projet
          </Link>
        </div>
      </div>
      <!-- Filters -->
      <div class="bg-white p-4 md:p-6 rounded-lg shadow-sm mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Filtrer les projets</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Type Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select
              v-model="filters.type"
              class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Tous les types</option>
              <option value="interne">Interne</option>
              <option value="externe">externe</option>
        </select>
          </div>

          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select
              v-model="filters.status"
              class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Tous les statuts</option>
              <option value="inprogress">En cours</option>
              <option value="completed">Terminé</option>
              <option value="planned">En attente</option>
              <option value="late">En retard</option>
                <option value="on_hold">En Pause</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Projects Grid -->
      <div v-if="projects.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div v-for="project in filterProjects.data" :key="project.id" class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow overflow-hidden">
          <div class="p-5">
            <div class="flex justify-between items-start mb-3">
              <h3 class="text-lg font-semibold text-gray-800 truncate">{{ project.title }}</h3>
              <span
                :class="[getStatusColor(project.status).bg, getStatusColor(project.status).text]"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              >
                <component :is="getStatusColor(project.status).icon" class="w-4 h-4" />
                <span class="ml-1">{{ getStatusColor(project.status).label  }} </span>
              </span>
            </div>

            <div class="flex flex-col items-center text-sm text-gray-500 mb-4">
              <span class="truncate">
                Chef de projet: {{
                                project.members.find((user) => user.pivot.role === 'Chef de projet')?.first_name || 'Non défini'
                            }} {{
                                project.members.find((user) => user.pivot.role === 'Chef de projet')?.last_name || 'Non défini'
                            }}
            </span>

            <span v-if="project.nature == 'externe'" class="truncate">
                Client: {{project.costumer_name}}
            </span>
            </div>

            <div class="mb-4">
              <div class="flex justify-between text-sm text-gray-500 mb-1">
                <span>Type: {{ project.type }}</span>
                <span>{{ progressProject(project) }}%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div
                  class="h-2 rounded-full transition-all duration-300"
                  :class="{
                    'bg-green-500': project.status === 'completed',
                    'bg-blue-500': project.status === 'inprogress',
                    'bg-yellow-400': project.status === 'on_hold',
                    'bg-red-500': project.status === 'late',
                    'bg-gray-500' : project.status === 'planned',
                    'bg-gray-300': !project.status
                    }"

                  :style="{ width: progressProject(project)+'%'}"
                ></div>
              </div>
            </div>

            <div class="flex justify-between text-sm text-gray-500">
              <div>
                <div class="font-medium text-gray-700">Début</div>
                <div>{{ formattedDate(project.start_date) }}</div>
              </div>
              <div>
                <div class="font-medium text-gray-700">Échéance</div>
                <div>{{ formattedDate(project.end_date) }}</div>
              </div>
            </div>
          </div>

          <div class="bg-gray-50 px-5 py-3 flex justify-end gap-3 border-t border-gray-200">
            <Link
              :href="`/projects/${project.id}`"
              class="flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
              <Eye class="w-4 h-4 mr-1" />
              Voir
            </Link>
            <Link
              :href="`/projects/${project.id}/edit`"
              class="flex items-center text-gray-600 hover:text-gray-800 text-sm font-medium"
            >
              <Edit class="w-4 h-4 mr-1" />
              Modifier
            </Link>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="projects.data.length" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 rounded-b-lg shadow-sm">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Affichage de {{ filterProjects.from }} à {{ filterProjects.to }} sur {{ filterProjects.total }} projets
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <Link
                v-for="link in filterProjects.links"
                :key="link.label"
                :href="link.url || '#'"
                preserve-state
                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                :class="{
                  'z-10 bg-blue-50 border-blue-500 text-blue-600': link.active,
                  'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active && link.url,
                  'text-gray-300 cursor-not-allowed': !link.url
                }"
                v-html="link.label"
              />
            </nav>
          </div>
        </div>
      </div>

      <div v-else class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
        Aucun projet ne correspond à vos critères de recherche
      </div>
    </div>
  </FrontendContent>
</template>
