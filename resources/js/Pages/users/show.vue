<template>
  <Dashboard>
    <div class="border shadow-md w-full sm:w-[80%] p-10 bg-white mx-auto">
      <div class="mb-10">
        <h1 class="text-blue text-3xl">Détails de l'Utilisateur</h1>
      </div>
      <div class="space-y-4">
        <p><strong>ID :</strong> {{ user.id }}</p>
        <p><strong>Nom :</strong> {{ user.first_name }} {{ user.last_name }}</p>
        <p><strong>Email :</strong> {{ user.email }}</p>
        <p><strong>Équipe :</strong> {{ user.equipe }}</p>
        <p><strong>Numéro de téléphone :</strong> {{ user.phone_number }}</p>
        <p><strong>Lien Profil :</strong> <a :href="user.profile_link" target="_blank">{{ user.profile_link }}</a></p>
        <p><strong>Lien LinkedIn :</strong> <a :href="user.linkedin_link" target="_blank">{{ user.linkedin_link }}</a></p>
      </div>
    </div>
  </Dashboard>
</template>

<script setup>
import { ref } from 'vue';
import Dashboard from '../dashboard/dashboard.vue';

const user = ref({
  id: 1,
  first_name: 'Jean',
  last_name: 'Dupont',
  email: 'jean@example.com',
  equipe: 'Marketing',
  phone_number: '0123456789',
  profile_link: 'https://profile.com/jean',
  linkedin_link: 'https://linkedin.com/in/jean',
});
</script>
