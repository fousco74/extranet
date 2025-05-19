<template>
    <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white py-6 px-6 sm:px-10 md:px-12 rounded-md shadow-md w-[90%] sm:w-[70%] md:w-[45%] max-h-[90vh] overflow-auto relative">

        <!-- Bouton fermer -->
        <img
          class="absolute top-4 right-4 w-6 cursor-pointer"
          src="/public/icons/quick.svg"
          alt="Fermer"
          @click="$emit('close')"
        />

         <!-- Message de succès -->
         <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-50 rounded-lg flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-green-600" />
                <p class="text-green-700">{{ $page.props.flash.success }}</p>
            </div>

        <!-- Titre -->
        <div class="text-center mb-6">
          <h2 class="text-lg font-semibold text-blue">Ajouter des membres</h2>
          <p class="text-sm text-gray-500">Sélectionnez des utilisateurs et attribuez-leur des rôles</p>
        </div>

        <!-- Sélection membres -->
        <div>
          <label class="text-sm font-medium text-gray-700 mb-2 block">Membres de l'équipe</label>
          <Multiselect
            v-model="form.members"
            :options="users.map(u => ({
              value: u.id,
              label: `${u.first_name} ${u.last_name}`,
              image: u.profile_link
            }))"
            mode="multiple"
            placeholder="Rechercher un membre..."
            label="label"
            track-by="value"
            :searchable="true"
            :classes="{ container: (errors.members || formErrors.members) ? 'multiselect border border-red-500' : 'multiselect' }"
          >
            <template #option="{ option }">
              <div class="relative flex items-center justify-between gap-3 pr-20">
                <div class="flex items-center gap-3">
                  <img v-if="option.image" :src="`/storage/${option.image}`" class="w-8 h-8 rounded-full object-cover" />
                  <div v-else class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-sm text-blue-600">{{ option.label.charAt(0) }}</span>
                  </div>
                  <span>{{ option.label }}</span>
                </div>
                <div class="absolute right-2 text-xs text-gray-500">
                  {{ memberRoles[option.value] || 'Aucun rôle' }}
                </div>
              </div>
            </template>
          </Multiselect>
          <p v-if="errors.members || formErrors.members" class="text-red-500 text-sm mt-2">
            {{ errors.members || formErrors.members }}
          </p>
        </div>

        <!-- Attribution des rôles -->
        <div v-if="form.members.length" class="mt-6 space-y-4">
          <div v-for="memberId in form.members" :key="memberId" class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
            <div class="flex-1">
              <div class="font-medium">
                {{ users.find(u => u.id === memberId)?.first_name }} {{ users.find(u => u.id === memberId)?.last_name }}
              </div>
              <select v-model="memberRoles[memberId]"
                class="mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm"
                :class="{ 'border-red-500': errors[`members.${memberId}.role`] || formErrors[`member_${memberId}`] }"
              >
                <option value="">Sélectionner un rôle</option>
                <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
              </select>
              <p v-if="errors[`members.${memberId}.role`] || formErrors[`member_${memberId}`]"
                class="text-red-500 text-xs mt-1">
                {{ errors[`members.${memberId}.role`] || formErrors[`member_${memberId}`] }}
              </p>
            </div>
            <button @click="form.members = form.members.filter(id => id !== memberId)" class="text-gray-400 hover:text-red-500">
              <XCircle class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Boutons -->
        <div class="mt-6 flex justify-end gap-2">
          <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-sm text-white rounded">
            Annuler
          </button>
          <button type="button" @click="submitForm" class="px-4 py-2 bg-blue-400 hover:bg-blue-600 text-sm text-white rounded">
            Ajouter
          </button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { router, usePage } from '@inertiajs/vue3';
  import Multiselect from '@vueform/multiselect';
  import { CheckCircle, Plus } from 'lucide-vue-next';
  import '@vueform/multiselect/themes/default.css';
  import { XCircle } from 'lucide-vue-next';

  const props = defineProps({
    users: Array,
    projectId: Number
  });

  const page = usePage();
  const errors = page.props.errors || {};
  const formErrors = ref({});

  const roles = [
    'Chef de projet', 'Développeur Frontend', 'Développeur Backend', 'Développeur Fullstack',
    'Designer', 'Testeur', 'Scrum Master', 'Product Owner', 'Analyste fonctionnel',
    'Architecte logiciel', 'DevOps', 'UX/UI Designer', 'Rédacteur technique', 'Responsable qualité',
    'Data Analyst', 'Consultant technique', 'Support technique', 'Administrateur système',
    'Chef de produit'
  ];

  const form = ref({
    members: []
  });

  const memberRoles = ref({});

  const submitForm = () => {
    const payload = {
      members: form.value.members.map(id => ({
        id,
        role: memberRoles.value[id] || ''
      }))
    };

    // Validation minimale avant envoi
    let hasErrors = false;
    formErrors.value = {};

    payload.members.forEach(member => {
      if (!member.role) {
        formErrors.value[`member_${member.id}`] = 'Veuillez sélectionner un rôle';
        hasErrors = true;
      }
    });

    if (hasErrors) return;

    if(!payload.members.length) {
      formErrors.value.members = 'Veuillez sélectionner au moins un membre';
      return;
    }

    console.log('Submitting form with payload:', payload);

    router.post(route('projects.assign-users', props.projectId), payload, {
      onSuccess: () => {
        form.value.members = [];
        memberRoles.value = {};
        emit('close');
      }
    });
  };
  </script>
