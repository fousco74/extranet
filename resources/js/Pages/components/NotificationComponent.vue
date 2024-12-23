<template>
  <div 
    class="absolute p-5 z-50    mt-2.5 flex h-auto max-h-96 lg:w-80 top-12 flex-col rounded-lg border border-stroke bg-white shadow-lg dark:border-strokedark  right-2 max-sm:w-64 overflow-hidden"
  >
    <!-- En-tête de la Notification -->
    <div class="flex justify-between items-center px-4.5 py-3 border-b border-stroke dark:border-strokedark bg-gray-50 dark:bg-meta-4">
      <h5 class="text-sm font-medium text-bodydark2 dark:text-white">Notifications</h5>
      <button 
        @click="showNotification = false" 
        class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white transition"
        aria-label="Fermer"
      >
        &times;
      </button>
    </div>

    <div class="text-center font-medium" v-if="notifications.length === 0">
      <span>Aucune notification</span>
    </div>

    <!-- Liste des Notifications -->
    <ul class="flex h-auto flex-col overflow-y-auto">
      <!-- Notification Individuelle -->
      <li 
        class="border-b border-stroke dark:border-strokedark last:border-none" 
        v-for="notif in notifications" :key="notif.id"
        @click="viewNotificationDetail(notif)"
      >
        <Link 
        :href="route('notifications.read',notif.id)"
        method="get"
          class="flex px-3 flex-col gap-2.5 px-4.5 py-3 hover:bg-gray-100 dark:hover:bg-meta-3 transition"
        >
          <p class="text-sm">
            <span class="font-semibold text-black dark:text-white">{{ notif.data.title }}</span><br>
            <span :class="{ 'text-blue_white font-bold': notif.read_at == null }">{{ getShortMessage(notif.data.message) }}</span>
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400">{{ notif.created_at }}</p>
        </Link>
      </li>
    </ul>

    <!-- Bouton Voir Tout -->
    <div class="p-4 bg-gray-50 dark:bg-meta-4 text-center">
      <a 
        href="/notifications" 
        class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition"
      >
        Voir toutes les notifications
      </a>
    </div>
  </div>
{{ selectedNotification }}
  <!-- Popup/Modal pour afficher les détails -->
  <div v-if="selectedNotification !=null" class="notification-popup">
    <div class="popup-content">
      <h3>{{ selectedNotification.data.title }}</h3>
      <p>{{ selectedNotification.data.message }}</p>
      <p><strong>Créé le :</strong> {{ selectedNotification.created_at }}</p>
      <button @click="closePopup">Fermer</button>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  notifications: Array,
});

const showNotification = ref(true);
const selectedNotification = ref(null);

// Fonction pour obtenir un extrait du message
const getShortMessage = (message) => {
  return message.length > 50 ? message.slice(0, 50) + '...' : message;
};

// Afficher les détails de la notification en cliquant
const viewNotificationDetail = (notif) => {
  selectedNotification.value = notif;
};

// Fermer le popup
const closePopup = () => {
  selectedNotification.value = null;
};
</script>

<style scoped>
.notification-popup {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
  width: 100%;
}
</style>
