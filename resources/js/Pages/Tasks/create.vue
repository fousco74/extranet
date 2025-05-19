<script setup lang="ts">
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import FrontendContent from '../dashboard/frontendContent.vue';
import Multiselect from '@vueform/multiselect';
import { CheckCircle } from 'lucide-vue-next'; // ✅ Icône à ajouter
import '@vueform/multiselect/themes/default.css';

const props = defineProps({
    projects: Array,
    users: Array,
    project: Object,
});

const form = reactive({
    title: '',
    description: '',
    priority: '',
    status: 'todo',
    delais: '',
    step_project: '',
    project_id: props.project.id,
    assigned_users: []
});

const processusProjet = [
    { id: 1, nom: 'Analyse des besoins' },
    { id: 2, nom: 'Spécifications fonctionnelles' },
    { id: 3, nom: 'Architecture technique' },
    { id: 4, nom: 'Conception UI/UX' },
    { id: 5, nom: 'Développement Frontend' },
    { id: 6, nom: 'Développement Backend' },
    { id: 7, nom: 'Création des APIs' },
    { id: 8, nom: 'Intégration' },
    { id: 9, nom: 'Tests unitaires' },
    { id: 10, nom: 'Tests fonctionnels' },
    { id: 11, nom: 'Recette utilisateur' },
    { id: 12, nom: 'Déploiement' },
    { id: 13, nom: 'Maintenance' },
    { id: 14, nom: 'Monitoring & logs' },
    { id: 15, nom: 'Améliorations continues' }
];




const submit = () => {
    router.post('/tasks', form, {
        preserveScroll: true,
        onSuccess: () => {
            form.title = '';
            form.description = '';
            form.priority = '';
            form.status = 'todo';
            form.order = null;
            form.delais = '';
            form.project_id = '';
            form.assigned_users = [];
            form.step_project = '';
        }
    });
};

const formattedDate = (date) => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
  return new Date(date).toLocaleDateString('fr-FR', options);
}
</script>

<template>
    <FrontendContent>
        <div class="container mx-auto px-4 py-8 max-w-3xl">
            <!-- Message de succès -->
            <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-50 rounded-lg flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-green-600" />
                <p class="text-green-700">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Erreurs -->
            <div v-if="Object.keys($page.props.errors).length" class="mb-6 p-4 bg-red-50 rounded-lg">
                <div v-for="(error, field) in $page.props.errors" :key="field" class="text-red-600 text-sm">
                    {{ error }}
                </div>
            </div>

            <!-- Formulaire -->
            <div class="form-card bg-white rounded-lg p-6 mb-8 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-plus-circle text-blue-500"></i>
                        <span>
                            Nouvelle tâche du projet :
                            <span class="bg-amber-300 text-gray-900 px-2 py-1 rounded-md font-medium">
                                {{ project.title }}
                            </span>
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium text-gray-700">Deadline :</span>
                        <span class="bg-amber-300 text-gray-900 px-2 py-1 rounded-md font-medium">
                            {{ formattedDate(project.end_date) }}
                        </span>
                    </div>
                </h2>


                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Titre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                        <input v-model="form.title" type="text"
                            class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            :class="{ 'border-red-500': $page.props.errors.title }">
                    </div>

                    <!-- Projet -->
                     <input type="hidden" v-model="form.project_id">

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea v-model="form.description" rows="3"
                            class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <!-- Grid de champs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Etape projet *</label>
                            <select v-model="form.step_project"
                                class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.step_project }">
                                <option value="">Sélectionner l'etape du projet</option>
                                <option v-for="processus in processusProjet" :key="processus.id" :value="processus.nom">
                                    {{ processus.nom }}
                                </option>
                            </select>
                        </div>

                        <!-- Utilisateurs assignés -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assignation</label>
                            <Multiselect
                                v-model="form.assigned_users"
                                class="multiselect-blue"
                                :options="project.members?.map(u => ({
                                    value: u.id,
                                    label: `${u.first_name} ${u.last_name}`,
                                    image: u.profile_link
                                }))"
                                mode="multiple"
                                :searchable="true"
                                placeholder="Rechercher un membre..."
                                label="label"
                                track-by="value"
                            >
                                <template #option="{ option }">
                                    <div class="relative flex items-center justify-between gap-3 pr-20">
                                        <!-- Partie gauche -->
                                        <div class="flex items-center gap-3">
                                            <img
                                                v-if="option.image"
                                                :src="`/storage/${option.image}`"
                                                class="w-8 h-8 rounded-full object-cover"
                                            >
                                            <div v-else class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm text-blue-600">
                                                    {{ option.label.charAt(0) }}
                                                </span>
                                            </div>
                                            <span>{{ option.label }}</span>
                                        </div>
                                    </div>
                                </template>
                            </Multiselect>
                        </div>

                        <!-- Priorité -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priorité *</label>
                            <select v-model="form.priority"
                                class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.priority }">
                                <option value="">Sélectionner...</option>
                                <option value="high">Haute</option>
                                <option value="medium">Moyenne</option>
                                <option value="low">Basse</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">statut *</label>
                            <select v-model="form.status"
                                class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.status }">
                                <option value="">Sélectionner...</option>
                                <option value="todo">À faire</option>
                                <option value="inprogress">En cours</option>
                                <option value="done">Terminée</option>
                            </select>
                        </div>





                        <!-- Délais -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Délais *</label>
                            <input v-model="form.delais" type="date"
                                class="input-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.delais }">
                        </div>
                    </div>



                    <!-- Boutons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue_white text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Créer la tâche
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </FrontendContent>
</template>

<style>
.multiselect-blue .multiselect-tag {
    background-color: #dbeafe; /* bg-blue-100 */
    color: #1e40af;            /* text-blue-800 */
}

.multiselect-blue .multiselect-option.is-selected {
    background-color: #dbeafe; /* bg-blue-100 */
    color: #1e40af;            /* text-blue-800 */
}

.multiselect-blue .multiselect-dropdown {
    border: 1px solid #e5e7eb; /* border-gray-200 */
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
                0 4px 6px -4px rgba(0, 0, 0, 0.1); /* shadow-lg */
}

.error-message {
    font-size: 0.875rem; /* text-sm */
    color: #dc2626;       /* text-red-600 */
    margin-top: 0.25rem;  /* mt-1 */
}
</style>
