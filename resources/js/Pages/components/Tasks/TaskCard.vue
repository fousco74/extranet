<!-- TaskCard.vue -->
<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    task: {
        type: Object,
        required: true
    }
})


// Obtenir la date d’aujourd’hui (en format Date seulement)
const today = new Date()
today.setHours(0, 0, 0, 0)

// Date d’échéance de la tâche
const dueDate = computed(() => {
    const d = new Date(props.task.delais)
    d.setHours(0, 0, 0, 0)
    return d
})

// Vérifie si la tâche est en retard
const isOverdue = computed(() => {
    return dueDate.value < today && props.task.status !== 'done'
})

// Vérifie si c’est aujourd’hui
const isToday = computed(() => {
    return dueDate.value.getTime() === today.getTime()
})

// Définir la priorité
const priorityInfo = computed(() => {
    switch (props.task.priority) {
        case 'high':
            return {
                icon: 'fas fa-exclamation-circle',
                text: 'Haute',
                color: 'text-red-500'
            }
        case 'medium':
            return {
                icon: 'fas fa-exclamation',
                text: 'Moyenne',
                color: 'text-yellow-500'
            }
        default:
            return {
                icon: 'fas fa-arrow-down',
                text: 'Basse',
                color: 'text-green-500'
            }
    }
})

// Définir le statut
const statusInfo = computed(() => {
    switch (props.task.status) {
        case 'todo':
            return {
                icon: 'far fa-circle',
                text: 'À faire',
                color: 'text-gray-500'
            }
        case 'inprogress':
            return {
                icon: 'fas fa-spinner',
                text: 'En cours',
                color: 'text-blue-500'
            }
        case 'on_hold':
            return {
                icon: 'fas fa-pause-circle',
                text: 'En pause',
                color: 'text-yellow-500'
            }
        default:
            return {
                icon: 'fas fa-check-circle',
                text: 'Terminée',
                color: 'text-green-500'
            }
    }
})


// Format date
const formattedDate = (date) => {
  const options = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };
  return new Date(date).toLocaleDateString('fr-FR', options);
}


// Bascule du statut
const statusToggle = () => {
    let newStatus
    if (props.task.status === 'todo') {
    props.task.status = 'inprogress'
} else if (props.task.status === 'inprogress') {
    props.task.status = 'on_hold'
} else if (props.task.status === 'on_hold') {
    props.task.status = 'done'
} else {
    props.task.status = 'todo'
}


    // Mettre à jour le statut de la tâche dans la base de données
    router.post('/tasks/update-status', {
        id: props.task.id,
        status: props.task.status
    })

}
</script>

<template>
    <div :class="`task-card bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-center justify-between
        ${isToday ? 'today-highlight' : ''} priority-${task.priority} status-${task.status}`">

        <div class="task-info mb-3 md:mb-0 md:mr-4 flex-1">
            <div class="flex items-start">
                <button @click="statusToggle" :class="`status-toggle mr-3 mt-1 ${statusInfo.color}`">
                    <i :class="`${statusInfo.icon} text-lg`"></i>
                </button>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ task.title }}</h3>
                    <p class="text-gray-600 text-sm">{{ task.description }}</p>
                    <div class="flex flex-wrap items-center mt-2 gap-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700">{{ task.step_project }}</span>
                        <span v-if="isOverdue" class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">En retard</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="task-meta flex items-center space-x-4">
            <div class="text-center">
                <p class="text-xs text-gray-500">Échéance</p>
                <p :class="`text-sm font-medium ${isOverdue ? 'text-red-500' : 'text-gray-700'}`">{{ formattedDate(task.delais) }}</p>
            </div>

            <div class="text-center hidden sm:block">
                <p class="text-xs text-gray-500">Priorité</p>
                <p :class="`text-sm font-medium ${priorityInfo.color}`">
                    <i :class="`${priorityInfo.icon} mr-1`"></i>{{ priorityInfo.text }}
                </p>
            </div>

            <div class="text-center hidden sm:block">
                <p class="text-xs text-gray-500">Statut</p>
                <p :class="`text-sm font-medium ${statusInfo.color}`">{{ statusInfo.text }}</p>
            </div>

            <button class="task-actions p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
    </div>
</template>

<style scoped>
.priority-high {
    border-left: 4px solid #ef4444;
}
.priority-medium {
    border-left: 4px solid #f59e0b;
}
.priority-low {
    border-left: 4px solid #10b981;
}

.status-todo {
    background-color: #f3f4f6;
}
.status-inprogress {
    background-color: #e0f2fe;
}
.status-done {
    background-color: #dcfce7;
}

.today-highlight {
    background-color: #fff7ed;
    box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.1), 0 2px 4px -1px rgba(249, 115, 22, 0.06);
    border-left: 4px solid #f97316;
}

@media (max-width: 640px) {
    .task-card {
        flex-direction: column;
    }
    .task-info {
        margin-bottom: 0.5rem;
    }
}
</style>
