<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import FrontendContent from '../dashboard/frontendContent.vue';
import { CheckCircle, XCircle, Plus } from 'lucide-vue-next';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

const props = defineProps({
    users: Array,
    project: Object,
});

const formattedDate = (dateConvert) => {
    if (!dateConvert) return '';
    const date = new Date(dateConvert);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

const currentStep = ref(1);
const totalSteps = 2;
const formErrors = ref({});
const memberRoles = ref({});

// Initialisation des données avec les valeurs existantes
const form = ref({
    title: props.project.title || '',
    description: props.project.description || '',
    start_date: formattedDate(props.project.start_date) || '',
    end_date: formattedDate(props.project.end_date) || '',
    costumer_name: props.project.costumer_name || '',
    type: props.project.type || 'development',
    status: props.project.status || 'planned',
    priority: props.project.priority || 'medium',
    nature: props.project.nature || 'interne',
    members: props.project.members ? props.project.members.map(m => m.id) : [],
});

console.log(props.project.members)

// Initialisation des rôles existants
props.project.members?.forEach(member => {
    memberRoles.value[member.user_id] = member.role;
});

const stepProgress = computed(() => ({
    width: `${(currentStep.value / totalSteps) * 100}%`
}));

const validateStep = () => {
    formErrors.value = {};

    if (currentStep.value === 1) {
        if (!form.value.title.trim()) formErrors.value.title = 'Le nom du projet est requis';
        if (!form.value.description.trim()) formErrors.value.description = 'La description est requise';
        if (!form.value.start_date) formErrors.value.start_date = 'La date de début est requise';
        if (!form.value.end_date) formErrors.value.end_date = 'La date de fin est requise';
        if (!form.value.type) formErrors.value.type = 'Le type de projet est requis';
        if (!form.value.status) formErrors.value.status = 'Le statut est requis';
        if (!form.value.priority) formErrors.value.priority = 'La priorité est requise';
        if (!form.value.nature) formErrors.value.nature = 'La nature du projet est requise';
    }

    if (currentStep.value === 2) {
        if (form.value.members.length === 0) {
            formErrors.value.members = 'Au moins un membre doit être sélectionné';
        } else {
            form.value.members.forEach(memberId => {
                if (!memberRoles.value[memberId]) {
                    formErrors.value[`member_${memberId}`] = 'Le rôle est requis pour ce membre';
                }
            });
        }
    }

    return Object.keys(formErrors.value).length === 0;
};

const nextStep = () => {
    if (validateStep()) {
        currentStep.value = Math.min(currentStep.value + 1, totalSteps);
    }
};

const prevStep = () => {
    currentStep.value = Math.max(currentStep.value - 1, 1);
};

const updateMemberRole = (memberId, role) => {
    memberRoles.value[memberId] = role;
};

const submit = () => {
    if (!validateStep()) return;

    const formattedData = {
        ...form.value,
        _method: 'put', // Spécifie la méthode HTTP PUT pour Laravel
        members: form.value.members.map(memberId => ({
            user_id: memberId,
            role: memberRoles.value[memberId] || 'Membre'
        }))
    };

    router.post(`/projects/${props.project.id}`, formattedData, {
        onSuccess: () => router.visit('/projects'),
    });
};
</script>

<template>
    <FrontendContent>
        <div class="container mx-auto px-4 py-8 max-w-6xl">
            <!-- Message de succès -->
            <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-50 rounded-lg flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-green-600" />
                <p class="text-green-700">{{ $page.props.flash.success }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- En-tête avec progression -->
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800">Créer un nouveau projet</h1>
                    <div class="mt-6 relative pt-4">
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 transform -translate-y-1/2">
                            <div class="h-full bg-blue-600 transition-all duration-300" :style="stepProgress"></div>
                        </div>
                        <div class="flex justify-between">
                            <div v-for="step in totalSteps" :key="step" class="relative z-10 mb-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold"
                                    :class="currentStep >= step ? 'bg-blue-600' : 'bg-gray-300'">
                                    {{ step }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <form @submit.prevent="submit" class="p-6">
                    <!-- Étape 1 -->
                    <div v-show="currentStep === 1" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Titre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Nom du projet</label>
                                <input v-model="form.title" type="text"
                                    :class="{ 'border-red-500': $page.props.errors.title || formErrors.title }"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                <p v-if="$page.props.errors.title || formErrors.title" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.title || formErrors.title }}
                                </p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Description</label>
                                <textarea v-model="form.description" rows="3"
                                    :class="{ 'border-red-500': $page.props.errors.description || formErrors.description }"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"></textarea>
                                <p v-if="$page.props.errors.description || formErrors.description" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.description || formErrors.description }}
                                </p>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">Date de début</label>
                                    <input v-model="form.start_date" type="date"
                                        :class="{ 'border-red-500': $page.props.errors.start_date || formErrors.start_date }"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                    <p v-if="$page.props.errors.start_date || formErrors.start_date" class="text-red-500 text-sm mt-1">
                                        {{ $page.props.errors.start_date || formErrors.start_date }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 required">Date de fin</label>
                                    <input v-model="form.end_date" type="date"
                                        :class="{ 'border-red-500': $page.props.errors.end_date || formErrors.end_date }"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                    <p v-if="$page.props.errors.end_date || formErrors.end_date" class="text-red-500 text-sm mt-1">
                                        {{ $page.props.errors.end_date || formErrors.end_date }}
                                    </p>
                                </div>
                            </div>

                            <!-- Sélecteurs -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Nature</label>
                                <select v-model="form.nature"
                                    :class="{ 'border-red-500': $page.props.errors.nature || formErrors.nature }"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                    <option value="interne">Interne</option>
                                    <option value="externe">Externe</option>
                                </select>
                                <p v-if="$page.props.errors.nature || formErrors.nature" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.nature || formErrors.nature }}
                                </p>
                            </div>

                            <div v-if="form.nature === 'externe'">
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Nom du client</label>
                                <input v-model="form.costumer_name" type="text"
                                    :class="{ 'border-red-500': $page.props.errors.costumer_name || formErrors.costumer_name }"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                <p v-if="$page.props.errors.costumer_name || formErrors.costumer_name" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.costumer_name || formErrors.costumer_name }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Statut</label>
                                <select v-model="form.status"
                                    :class="{ 'border-red-500': $page.props.errors.status || formErrors.status }"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                    <option value="planned">Planifié</option>
                                    <option value="inprogress">En cours</option>
                                    <option value="completed">Terminé</option>
                                    <option value="on_hold">En pause</option>
                                </select>
                                <p v-if="$page.props.errors.status || formErrors.status" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.status || formErrors.status }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Priorité</label>
                                <select v-model="form.priority"
                                    :class="{ 'border-red-500': $page.props.errors.priority || formErrors.priority }"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                    <option value="low">Basse</option>
                                    <option value="medium">Moyenne</option>
                                    <option value="high">Haute</option>
                                </select>
                                <p v-if="$page.props.errors.priority || formErrors.priority" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.priority || formErrors.priority }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 required">Type</label>
                                <select v-model="form.type"
                                    :class="{ 'border-red-500': $page.props.errors.type || formErrors.type }"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                                    <option value="development">Développement</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="design">Design</option>
                                    <option value="research">Recherche</option>
                                    <option value="other">Autre</option>
                                </select>
                                <p v-if="$page.props.errors.type || formErrors.type" class="text-red-500 text-sm mt-1">
                                    {{ $page.props.errors.type || formErrors.type }}
                                </p>
                            </div>
                        </div>
                    </div>


                    <!-- Étape 2 -->
                    <div v-show="currentStep === 2" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Sélection des membres -->
                            <div >
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 required">Membres de l'équipe</label>
                                    <Multiselect
                                        v-model="form.members"
                                        :options="users.map(u => ({
                                            value: u.id,
                                            label: `${u.first_name} ${u.last_name}`,
                                            image: u.profile_link
                                        }))"
                                        mode="multiple"
                                        :searchable="true"
                                        placeholder="Rechercher un membre..."
                                        label="label"
                                        track-by="value"
                                        :classes="{
                                            container: ($page.props.errors.members || formErrors.members)
                                                ? 'multiselect border border-red-500'
                                                : 'multiselect'
                                        }"
                                    >
                                        <template #option="{ option }">
                                            <div class="relative flex items-center justify-between gap-3 pr-20">
                                                <!-- Partie gauche : avatar + nom -->
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

                                                <!-- Partie droite en absolute -->
                                                <div class="absolute right-2 text-xs text-gray-500">
                                                    <!-- Contenu à droite (ex: rôle ou info) -->
                                                    {{ memberRoles[option.value] || 'Aucun rôle' }}
                                                </div>
                                            </div>
                                        </template>
                                    </Multiselect>

                                    <p v-if="$page.props.errors.members || formErrors.members"
                                       class="text-red-500 text-sm mt-2">
                                        {{ $page.props.errors.members || formErrors.members }}
                                    </p>
                                </div>

                                <!-- Rôles des membres -->
                                <div v-if="form.members.length > 0" class="space-y-4">
                                    <div v-for="memberId in form.members" :key="memberId"
                                         class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <div class="font-medium">
                                                {{ users.find(u => u.id === memberId)?.first_name }}
                                                {{ users.find(u => u.id === memberId)?.last_name }}
                                            </div>
                                            <select v-model="memberRoles[memberId]"
                                                :class="{ 'border-red-500': $page.props.errors[`members.${memberId}.role`] || formErrors[`member_${memberId}`] }"
                                                class="mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm">
                                                <option>Sélectionner un rôle</option>
                                                <option value="Chef de projet">Chef de projet</option>
                                                <option value="Développeur Frontend">Développeur Frontend</option>
                                                <option value="Développeur Backend">Développeur Backend</option>
                                                <option value="Développeur Fullstack">Développeur Fullstack</option>
                                                <option value="Designer">Designer</option>
                                                <option value="Testeur">Testeur</option>
                                                <option value="Scrum Master">Scrum Master</option>
                                                <option value="Product Owner">Product Owner</option>
                                                <option value="Analyste fonctionnel">Analyste fonctionnel</option>
                                                <option value="Architecte logiciel">Architecte logiciel</option>
                                                <option value="DevOps">DevOps</option>
                                                <option value="UX/UI Designer">UX/UI Designer</option>
                                                <option value="Rédacteur technique">Rédacteur technique</option>
                                                <option value="Responsable qualité">Responsable qualité</option>
                                                <option value="Data Analyst">Data Analyst</option>
                                                <option value="Consultant technique">Consultant technique</option>
                                                <option value="Support technique">Support technique</option>
                                                <option value="Administrateur système">Administrateur système</option>
                                                <option value="Chef de produit">Chef de produit</option>

                                            </select>
                                            <p v-if="$page.props.errors[`members.${memberId}.role`] || formErrors[`member_${memberId}`]"
                                               class="text-red-500 text-xs mt-1">
                                                {{ $page.props.errors[`members.${memberId}.role`] || formErrors[`member_${memberId}`] }}
                                            </p>
                                        </div>
                                        <button @click="form.members = form.members.filter(id => id !== memberId)"
                                                class="text-gray-400 hover:text-red-500">
                                            <XCircle class="w-5 h-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Aperçu des membres -->
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Aperçu de l'équipe</h3>
                                <div class="space-y-3">
                                    <div v-for="memberId in form.members" :key="memberId"
                                         class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                                        <img v-if="users.find(u => u.id === memberId)?.profile_link"
                                             :src="`/storage/${users.find(u => u.id === memberId)?.profile_link}`"
                                             class="w-8 h-8 rounded-full object-cover">
                                        <div v-else class="w-8 h-8 rounded-full bg-blue_whte flex items-center justify-center">
                                            <span class="text-xs font-medium text-blue">
                                                {{ users.find(u => u.id === memberId)?.first_name?.charAt(0) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ users.find(u => u.id === memberId)?.first_name }}
                                                {{ users.find(u => u.id === memberId)?.last_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ memberRoles[memberId] || 'Rôle non défini' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div v-if="form.members.length === 0"
                                         class="text-center text-gray-400 py-4">
                                        Aucun membre sélectionné
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between pt-6 border-t border-gray-200">
                        <button @click="prevStep" type="button" v-show="currentStep > 1"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                            ← Précédent
                        </button>
                        <button @click="nextStep" type="button" v-show="currentStep < totalSteps"
                                class="ml-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            Suivant →
                        </button>
                        <button type="submit" v-show="currentStep === totalSteps"
                                class="ml-auto px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium">
                            Créer le projet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </FrontendContent>
</template>


<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.error-shake {
    animation: shake 0.5s;
}

@keyframes shake {

    10%,
    90% {
        transform: translateX(-1px);
    }

    20%,
    80% {
        transform: translateX(2px);
    }

    30%,
    50%,
    70% {
        transform: translateX(-4px);
    }

    40%,
    60% {
        transform: translateX(4px);
    }
}
</style>
