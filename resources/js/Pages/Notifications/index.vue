<template>
  <NavBarComponent></NavBarComponent>
  <div class="flex flex-col lg:flex-row h-screen bg-gray-100">
    <!-- Liste des notifications -->
    <aside class="w-full lg:w-1/3 bg-white shadow-md border-b lg:border-r lg:rounded-l-lg">
      <div class="p-4 border-b">
        <h2 class="text-xl font-semibold text-gray-700">Notifications</h2>
      </div>
      <div v-if="$page.props.auth.user.notifications.length==0" class="px-4 my-10">
        <h2 class="text-xl text-gray-700">aucune notification</h2>
      </div>
      <ul class="divide-y divide-gray-200">
        <li 
          v-for="notif in $page.props.auth.user.notifications" 
          :key="notif.id"
          @click="selectNotification(notif)"
          class="p-4 hover:bg-gray-100 cursor-pointer flex justify-between items-center"
          :class="{'bg-gray-100 ': notif.id === selectedNotification?.id}"
        >
          <div>
            <p class="truncate text-sm font-semibold">{{ notif.data.title }}</p>
            <p class="text-sm text-opacity-65 text-black" :class="{ 'text-blue_white font-bold': notif.read_at == null }">{{ truncateMessage(notif.data.message, 5) }}</p>
            <p class="text-xs text-gray-400">{{ formatDate(notif.created_at) }}</p>
          </div>
          <span v-if="!notif.read_at" class="h-2 w-2 bg-blue-500 rounded-full"></span>
        </li>
      </ul>
    </aside>

    <!-- Détails de la notification -->
    <main v-if="selectedNotification" class="flex-1 bg-white px-10 py-20 shadow-md lg:ml-4">
      <h3 class="text-2xl font-semibold text-gray-700">{{ selectedNotification.data.title }}</h3>
      <p class="mt-4 text-gray-600">{{ selectedNotification.data.message }}</p>
      <p class="mt-6 text-sm text-gray-500">
        <strong>Créé le :</strong> {{ formatDate(selectedNotification.created_at) }}
      </p>
    </main>

    <!-- Message lorsque aucune notification n'est sélectionnée -->
    <main v-else class="flex-1 bg-white p-6 shadow-md relative">
      <div class="flex flex-col justify-center items-center gap-4">
        <img src="/public/icons/notif.svg" alt="notification" class="w-[200px]" />
        <div class="text-center">
          <span class="font-[200px]">Sélectionnez un élément pour le lire</span><br>
          <span class="text-opacity-75 text-blue">Aucun élément sélectionné</span>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import NavBarComponent from '../components/NavBarComponent.vue';

const { props } = usePage();
const selectedNotification = ref(null);
const notifications = props.auth.user.notifications || [];

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('fr-FR', options);
};

const truncateMessage = (message, wordLimit) => {
  const words = message.split(' ');
  if (words.length > wordLimit) {
    return words.slice(0, wordLimit).join(' ') + '...';
  }
  return message;
};

const selectNotification = (notif) => {
  router.get(`/notifications/read/${notif.id}`);
};

const markAsRead = async () => {
  if (!selectedNotification.value) return;

  try {
    router.put(`/notifications/${selectedNotification.value.id}/read`);
  } catch (error) {
    console.error("Erreur lors de la mise à jour de la notification :", error);
  }
};

onMounted(() => {
  // Récupérer la notification sélectionnée
  if (props.selectedNotificationId) {
    selectedNotification.value = notifications.find(
      (notif) => notif.id === props.selectedNotificationId
    );
  }
});
</script>
