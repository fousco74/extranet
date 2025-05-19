

<script setup>
import { ref } from 'vue';
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
import AssignUserComponent from '../components/assignUserComponent.vue';

const props = defineProps({
    project: Object,
    users: Array,
    tasks: Array,
});

const getProjectStatusColor = (status) => {
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


const getStatusColor = (status) => {
  switch (status) {
    case "done":
      return {
        bg: "bg-green-50",
        text: "text-green-600",
        icon: CheckCircle,
        label: "Terminé",
        progressColor: "bg-green-500"
      };

    case "inprogress":
      return {
        bg: "bg-blue-50",
        text: "text-blue-600",
        icon: Loader,
        label: "En cours",
        progressColor: "bg-blue-500"
      };

    case "on_hold":
      return {
        bg: "bg-yellow-50",
        text: "text-yellow-600",
        icon: Clock,
        label: "En pause",
        progressColor: "bg-yellow-400"
      };

    case "late":
      return {
        bg: "bg-red-50",
        text: "text-red-600",
        icon: AlertCircle,
        label: "En retard",
        progressColor: "bg-red-500"
      };

    case "todo":
      return {
        bg: "bg-gray-50",
        text: "text-gray-600",
        icon: Clock,
        label: "Prévu",
        progressColor: "bg-gray-400"
      };

    default:
      return {
        bg: "bg-gray-100",
        text: "text-gray-500",
        icon: Clock,
        label: "Inconnu",
        progressColor: "bg-gray-300"
      };
  }
};





// Méthodes utilitaires
const formatDate = (dateString) => {
    const options = { day: '2-digit', month: 'long', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
};

const formatFileSize = (bytes) => {
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    if (bytes === 0) return '0 Byte';
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

const fileTypeIcon = (type) => {
    const icons = {
        pdf: 'file-pdf',
        zip: 'file-archive',
        xlsx: 'file-excel',
        default: 'file-alt'
    };
    return `fas fa-${icons[type] || icons.default}`;
};



const progressProject = (project) => {
    const tasksLength = project.tasks.length;

    if (tasksLength === 0) {
        return {
            percent: 0,
            tasksCompleted: 0,
            tasksTotal: 0
        };
    }

    const tasksCompletedLength = project.tasks.filter((task) => task.status === 'done').length;

    return {
        percent: parseInt((tasksCompletedLength / tasksLength) * 100),
        tasksCompleted: tasksCompletedLength,
        tasksTotal: tasksLength
    };
};






// Classes réactives
const timelineDotClasses = (status) => ({
  'bg-green-500 border-green-500': status === 'done',
  'bg-blue-500 border-blue-500': status === 'inprogress',
  'bg-yellow-500 border-yellow-500': status === 'on_hold',
  'bg-red-500 border-red-500': status === 'late',
  'bg-gray-400 border-gray-400': status === 'todo'
});


const statusDotClasses = (status) => ({
    'bg-green-500': status === 'online',
    'bg-yellow-500': status === 'away',
    'bg-gray-500': status === 'offline'
});



// Exemple de données fictives pour `member`
// À remplacer par ta source réelle


const getRoleDescription = (role) => {
  const descriptions = {
    'Chef de projet': 'Supervise la planification et l’exécution des projets.',
    'Développeur Frontend': 'Conçoit l’interface utilisateur (HTML, CSS, JS, frameworks).',
    'Développeur Backend': 'Gère la logique serveur, les bases de données et les APIs.',
    'Développeur Fullstack': 'Maîtrise à la fois le frontend et le backend.',
    'Designer': 'Crée des interfaces visuelles attractives.',
    'Testeur': 'Vérifie la qualité et les bugs du produit.',
    'Scrum Master': 'Facilite le processus Agile.',
    'Product Owner': 'Gère le backlog produit et les priorités.',
    'Analyste fonctionnel': 'Traduit les besoins métier en spécifications techniques.',
    'Architecte logiciel': 'Définit la structure technique des applications.',
    'DevOps': 'Automatise les déploiements et la gestion des environnements.',
    'UX/UI Designer': 'Optimise l’expérience utilisateur et les interfaces.',
    'Rédacteur technique': 'Rédige la documentation produit.',
    'Responsable qualité': 'Assure les normes qualité du projet.',
    'Data Analyst': 'Analyse les données pour en extraire des insights.',
    'Consultant technique': 'Apporte une expertise sur des problématiques techniques.',
    'Support technique': 'Aide les utilisateurs à résoudre leurs problèmes.',
    'Administrateur système': 'Gère les serveurs et infrastructures réseau.',
    'Chef de produit': 'Définit la vision et la stratégie produit.'
  }
  return descriptions[role] || 'Rôle non défini'
}

const getTagClass = (tag) => {
  if (['Chef de projet', 'Scrum Master', 'Product Owner', 'Chef de produit'].includes(tag)) {
    return 'bg-blue-100 text-blue-800'
  }
  if ([
    'Développeur Frontend',
    'Développeur Backend',
    'Développeur Fullstack',
    'Architecte logiciel',
    'DevOps',
    'Consultant technique',
    'Administrateur système'
  ].includes(tag)) {
    return 'bg-purple-100 text-purple-800'
  }
  if (['Designer', 'UX/UI Designer'].includes(tag)) {
    return 'bg-pink-100 text-pink-800'
  }
  if (['Analyste fonctionnel', 'Data Analyst', 'Rédacteur technique'].includes(tag)) {
    return 'bg-yellow-100 text-yellow-800'
  }
  if (['Testeur', 'Responsable qualité'].includes(tag)) {
    return 'bg-green-100 text-green-800'
  }
  if (['Support technique'].includes(tag)) {
    return 'bg-gray-100 text-gray-800'
  }
  return 'bg-gray-200 text-gray-900' // fallback
}

const openModal = ref(false);

const toggleModal = () => {
    open.value = !open.value;
};

const closeModal = () => {
    open.value = false;
};

const tasksByStep = ref([]);
// Initialiser showKanban pour chaque élément
const initializeTasksByStep = () => {
const group = Object.groupBy(props.project.tasks, (task) => task.step_project);

  tasksByStep.value = Object.keys(group).map((key) => {


    const tasks = group[key];
    const total = tasks.length;
    const done = tasks.filter((task) => task.status === 'done').length;
    const late = tasks.filter((task) => task.status === 'late').length;
    const onHold = tasks.filter((task) => task.status === 'on_hold').length;
    const delais = tasks[total-1].delais;

    tasks.map((task) => {

        task.users = [];
        props.tasks.map((taskP) => {

            if(taskP.id == task.id){
                taskP.users.map((user) => {
                    task.users.push(user);
                });
            }

            return  ;

        })


    });

    const pourcent = parseInt((done / total) * 100);

    let status = 'todo';

    if (done === total) {
        status = 'done';
    } else if (onHold === total) {
        status = 'on_hold';
    } else if (late >= Math.ceil(total / 2)) {
        status = 'late';
    } else if (done > 0) {
        status = 'inprogress';
    }

    return {
      title: key,
      tasks,
      progress: pourcent,
      status,
      delais,
      done,
      late,
      onHold,
      total,
      showKanban: false // Ajouter cette propriété
    };
  });
};

initializeTasksByStep();





const getDescriptionProcessus = (nom) => {
    const descriptions = {
        'Analyse des besoins': "Identification des besoins des utilisateurs et des parties prenantes pour définir les objectifs du projet.",
        'Spécifications fonctionnelles': "Documentation détaillée des fonctionnalités attendues du système.",
        'Architecture technique': "Définition de la structure technique de l'application (serveurs, bases de données, frameworks, etc.).",
        'Conception UI/UX': "Création des interfaces utilisateur et définition de l'expérience utilisateur pour garantir l'ergonomie.",
        'Développement Frontend': "Implémentation de l'interface utilisateur (HTML, CSS, JavaScript, frameworks JS, etc.).",
        'Développement Backend': "Développement de la logique métier, gestion des bases de données et de l'authentification.",
        'Création des APIs': "Développement des interfaces permettant la communication entre le frontend, le backend, et d'autres services.",
        'Intégration': "Connexion des différentes composantes du système pour former une application cohérente.",
        'Tests unitaires': "Vérification du bon fonctionnement des plus petites unités de code (fonctions, méthodes).",
        'Tests fonctionnels': "Validation des fonctionnalités selon les spécifications initiales.",
        'Recette utilisateur': "Validation finale du produit par les utilisateurs pour s'assurer qu'il répond à leurs attentes.",
        'Déploiement': "Mise en production de l'application sur les serveurs ou dans le cloud.",
        'Maintenance': "Correction des bugs et ajustements après le déploiement pour assurer la stabilité du système.",
        'Monitoring & logs': "Surveillance de l’application en production pour détecter les erreurs et les comportements anormaux.",
        'Améliorations continues': "Optimisation régulière de l’application en fonction des retours utilisateurs et des évolutions techniques."
    };

    return descriptions[nom] || "Description non disponible.";
}


const toggleItemShowKanban = (item) => {
    item.showKanban = !item.showKanban;
};



</script>

<template>
    <FrontendContent >
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start mb-8 gap-6">
    <div class="flex-1">
        <div class="flex items-center gap-4 mb-2">
            <h1 class="text-3xl font-semibold text-gray-900">{{ project.title }}</h1>
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                :class="getProjectStatusColor(project.status).bg + ' ' + getProjectStatusColor(project.status).text"
            >
                <component :is="getProjectStatusColor(project.status).icon" class="w-4 h-4 mr-1.5" />
                {{ getProjectStatusColor(project.status).label }}
            </span>
        </div>

        <div class="flex flex-wrap items-center gap-3 text-gray-600">
            <!-- Dates -->
            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg text-sm">
                <CalendarDays class="w-4 h-4 mr-2 text-gray-500" />
                <span>{{ formatDate(project.start_date) }} - {{ formatDate(project.end_date) }}</span>
            </div>

            <!-- Members -->
            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg text-sm">
                <Users class="w-4 h-4 mr-2 text-gray-500" />
                <span>{{ project.members.length }} {{ project.members.length > 1 ? 'membres' : 'membre' }}</span>
            </div>

            <!-- Completed Status -->
            <div v-if="project.completed_at" class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg text-sm">
                <CalendarCheck class="w-4 h-4 mr-2 text-gray-500" />
                <div class="flex flex-col">
                    <span>Terminé le {{ formatDate(project.completed_at) }}</span>
                    <span class="text-xs flex items-center">
                        <Clock class="w-3 h-3 mr-1" />
                        {{ project.end_date < project.completed_at ? 'En retard' : 'À temps' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>


   <!-- Timeline -->
<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 p-6 mb-8">
  <div class="flex justify-between">
    <h2 class="text-lg font-semibold text-gray-900 mb-6">Avancement du projet</h2>
    <a v-if="$page.props.auth.user.roles.includes('admin') || $page.props.auth.user.roles.includes('Chef de projet')"
        :href="route('projects.tasks.create', {projectId : project.id} )"
        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-2"
        >
        <i class="fas fa-plus"></i>
        Ajouter une tâtche
    </a>
  </div>
  <div class="relative pl-10">
    <div v-if="tasksByStep.length === 0" class="text-center text-gray-500">
      <p>Aucune tâche à afficher pour le moment.</p>
    </div>
    <div class="absolute left-4 top-0 h-full w-1.5 bg-gray-300/80"></div>
    <div class="space-y-10">
      <div v-for="item in tasksByStep" :key="item.title" class="relative group">
        <!-- Timeline dot avec animation contextuelle -->
        <div class="absolute -left-[34px] w-7 h-7 rounded-full flex items-center justify-center shadow-sm transition-all duration-300"
             :class="[
               timelineDotClasses(item.status),
               {'animate-pulse': item.status === 'inprogress'}
             ]">
          <i :class="{
              'fas fa-check-circle': item.status === 'done',
              'fas fa-spinner fa-spin': item.status === 'inprogress',
              'fas fa-pause-circle': item.status === 'on_hold',
              'fas fa-exclamation-triangle': item.status === 'late',
              'far fa-clock': item.status === 'todo'
            }" class="text-white text-sm"></i>
        </div>

        <!-- Carte avec effet d'interaction -->
        <div :class="`${getStatusColor(item.status).bg} p-6 rounded-xl shadow-xs group-hover:shadow-sm transition-all duration-300`">
          <div class="flex justify-between items-start gap-4">
            <div class="flex-1">
              <h3 :class="`font-semibold ${getStatusColor(item.status).text} mb-2`">{{ item.title }}</h3>
              <p class="text-sm text-gray-600 leading-relaxed">{{ getDescriptionProcessus(item.title) }}</p>
            </div>
            <!-- Badge de statut amélioré -->
            <span :class="`inline-flex items-center text-[0.8rem] px-3 py-1 rounded-full font-medium tracking-wide ${getStatusColor(item.status).text} ${getStatusColor(item.status).bg} bg-opacity-20`">
              {{ getStatusColor(item.status).label }}
            </span>
          </div>

          <!-- Délais avec icône alignée -->
          <div class="mt-4 flex items-center text-xs text-gray-500 space-x-2">
            <i class="far fa-calendar text-gray-400"></i>
            <span>{{ item.delais }}</span>
          </div>

        <!-- Bouton toggle -->
  <div class="flex justify-end mb-4">
    <button @click="toggleItemShowKanban(item)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
      {{ item.showKanban ? 'Masquer les tâches' : 'Afficher les tâches' }}
    </button>
  </div>

  <!-- Kanban principal -->
<transition name="fade">
  <div v-show="item.showKanban" class="p-6 rounded-lg bg-white shadow-xl space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

      <!-- Tâches À faire -->
      <div class="kanban-column bg-gray-50 p-4 rounded-lg shadow-xl transition-transform hover:scale-105">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-semibold text-gray-800">À faire</h3>
          <span class="bg-gray-200 text-gray-800 text-sm px-3 py-1 rounded-full">
            {{ item.tasks.filter((task) => task.status === 'todo').length }}
          </span>
        </div>
        <div class="flex flex-col gap-3">
            <div v-for="task in item.tasks.filter(task => task.status === 'todo')" :key="task.id" class="space-y-4">
            <div class="kanban-card bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start mb-3">
                <h4 class="font-medium text-gray-800">{{ task.title }}</h4>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ task.descriptions }}</p>
                <div v-for="user in task.users" :key="user.email" class="flex justify-between text-xs text-gray-500">
                    <span>Assigné à: <span class="bg-gray-200 text-gray-800">{{ user.first_name }} {{ user.last_name }}</span></span>
                    <span>Échéance: <span class="bg-gray-200 text-gray-800">{{ task.delais }}</span></span>
                </div>
            </div>
            </div>
        </div>
      </div>

      <!-- En cours -->
      <div class="kanban-column bg-gray-50 p-4 rounded-lg shadow-xl transition-transform hover:scale-105">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-semibold text-blue-700">En cours</h3>
          <span class="bg-blue-200 text-blue-800 text-sm px-3 py-1 rounded-full">
            {{ item.tasks.filter((task) => task.status === 'inprogress').length }}
          </span>
        </div>
        <div class="flex flex-col gap-3">

            <div v-for="task in item.tasks.filter(task => task.status === 'inprogress')" :key="task.id" class="space-y-4">
            <div class="kanban-card bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-600 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start mb-3">
                <h4 class="font-medium text-gray-800">{{ task.title }}</h4>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ task.descriptions }}</p>
                <div v-for="user in task.users" :key="user.email" class="flex justify-between text-xs text-gray-500">
                    <span>Assigné à: <span class="bg-blue-200 text-blue-800">{{ user.first_name }} {{ user.last_name }}</span></span>
                    <span>Échéance: <span class="bg-blue-200 text-blue-800">{{ task.delais }}</span></span>
                </div>
            </div>
            </div>
        </div>
      </div>

      <!-- En Pause -->
      <div class="kanban-column bg-gray-50 p-4 rounded-lg shadow-xl transition-transform hover:scale-105">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-semibold text-yellow-700">En Pause</h3>
          <span class="bg-yellow-200 text-yellow-800 text-sm px-3 py-1 rounded-full">
            {{ item.tasks.filter((task) => task.status === 'on_hold').length }}
          </span>
        </div>
        <div class="flex flex-col gap-3">

            <div v-for="task in item.tasks.filter(task => task.status === 'on_hold')" :key="task.id">
            <div class="kanban-card bg-white p-4 rounded-lg shadow-md border-l-4 border-yellow-400 hover:shadow-lg transition-shadow duration-300">
                <div class="flex justify-between items-start mb-3">
                <h4 class="font-medium text-gray-800">{{ task.title }}</h4>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ task.descriptions }}</p>
                <div v-for="user in task.users" :key="user.email" class="flex justify-between text-xs text-gray-500">
                    <span>Assigné à: <span class="bg-yellow-200 text-yellow-800">{{ user.first_name }} {{ user.last_name }}</span></span>
                    <span>Échéance: <span class="bg-yellow-200 text-yellow-800">{{ task.delais }}</span></span>
                </div>
            </div>
            </div>
        </div>
      </div>

      <!-- Terminé -->
      <div class="kanban-column bg-gray-50 p-4 rounded-lg shadow-xl transition-transform hover:scale-105">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-semibold text-green-700">Terminé</h3>
          <span class="bg-green-200 text-green-800 text-sm px-3 py-1 rounded-full">
            {{ item.tasks.filter((task) => task.status === 'done').length }}
          </span>
        </div>
        <div class="flex flex-col gap-3">
            <div v-for="task in item.tasks.filter(task => task.status === 'done')" :key="task.id" >
          <div class="kanban-card bg-white p-4 rounded-lg shadow-md border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex justify-between items-start mb-3">
              <h4 class="font-medium text-gray-800">{{ task.title }}</h4>
            </div>
            <p class="text-sm text-gray-600 mb-2">{{ task.descriptions }}</p>
            <div v-for="user in task.users" :key="user.email" class="flex justify-between text-xs text-gray-500">
              <span>Assigné à: <span class="bg-green-200  text-green-800">{{ user.first_name }} {{ user.last_name }}</span></span>
              <span>Échéance: <span class="bg-green-200 text-green-800">{{ task.delais }}</span></span>
            </div>
          </div>
        </div>
        </div>
      </div>

    </div>
  </div>
</transition>



          <!-- Barre de progression améliorée -->
          <div v-if="item.status === 'inprogress'" class="mt-6 pt-4 border-t border-blue-50">
            <div class="flex items-center justify-between space-x-4 text-sm">
              <span :class="`${getStatusColor(item.status).text} font-medium`">{{ item.progress }}%</span>
              <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                <div
                  :class="`${getStatusColor(item.status).progressColor} h-full rounded-full transition-all duration-500 ease-out`"
                  :style="`width: ${item.progress}%`"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sections Principales -->
                <div class="flex-1 space-y-6">
                    <!-- Membres -->
                    <section id="members" class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Équipe du projet</h2>
                            <button class="text-sm text-blue-600 hover:text-blue-800" @click="openModal = true">
                                <i class="fas fa-user-plus mr-1"></i> Inviter
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="member in project.members" :key="member.id"
                                 class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="relative">
                                    <img :src="`/storage/${member.profile_link}`" :alt="member.first_name" class="w-12 h-12 object-cover rounded-full">
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-900">{{ member.last_name }} {{ member.first_name }}</h3>
                                    <p class="text-xs text-gray-500">{{ member.poste }}</p>
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        <span
                                            :class="`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${getTagClass(member.pivot.role)}`"

                                            :title="getRoleDescription(member.pivot.role)">
                                            {{ member.pivot.role }}
                                        </span>
                                        </div>

                                </div>
                            </div>
                        </div>

                        <AssignUserComponent
                            v-if="openModal"
                            @close="openModal = false"
                            :projectId="project.id"
                            :users="users"
                            :roles="project.roles"
                            v-model:open="openModal"
                            />

                    </section>


                     <!-- Métriques -->
                     <section id="metrics" class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Indicateurs clés</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                            <div  class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-500">Progression globale</h3>
                                </div>
                                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ progressProject(project).percent  }}%</div>
                                <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                    <div
                                    :class="'bg-blue-600 h-2 rounded-full'"
                                    :style="{ width: `${progressProject(project).percent}%` }"
                                    ></div>

                                </div>
                            </div>

                            <div  class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-500">Tâches complétées</h3>
                                </div>
                                <div class="mt-2 text-2xl font-semibold text-gray-900">{{ progressProject(project).tasksCompleted}}/{{ progressProject(project).tasksTotal }}</div>
                                <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                    <div :class="`bg-green-500 h-2 rounded-full`"
                                         :style="`width: ${progressProject(project).percent}%`"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </FrontendContent>
</template>

<style scoped>
.btn-primary {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem; /* py-2 (0.5rem) px-4 (1rem) */
    background-color: #2563eb; /* bg-blue-600 */
    color: #ffffff; /* text-white */
    border-radius: 0.375rem; /* rounded-md (6px) */
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
    transition-property: color, background-color, border-color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.btn-primary:hover {
    background-color: #1d4ed8; /* hover:bg-blue-700 */
}

.sticky {
    position: sticky;
    top: 1rem; /* top-4 (16px) */
}
.border-l-2 {
    border-left-width: 2px;
}

.hover\:shadow-md {
    transition: box-shadow 0.3s ease;
}

.transition-colors {
    transition: background-color 0.3s ease, color 0.3s ease;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.fade-enter-active, .fade-leave-active {
  transition: all 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

</style>
