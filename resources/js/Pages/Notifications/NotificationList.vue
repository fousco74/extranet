<template>
  <dashboard>
      <div class="border shadow-md w-[80%] max-sm:w-full p-10 bg-white">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-semibold text-gray-700">Listes des Notifications</h1>
        </div>

        <!-- Flash Message -->
        <div class="mb-4">
          <span
            v-if="$page.props.flash.message"
            class="text-center block bg-green-200 text-green-800 py-2 px-4 rounded"
          >
            {{ $page.props.flash.message }}
          </span>
        </div>

        <!-- Empty State -->
        <div v-if="notifications.length === 0" class="text-center text-gray-500">
          <p>Aucune notification trouvée.</p>
        </div>

        <!-- Table -->
        <div v-else>
          <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-200">
                <th class="text-left p-3 text-gray-600 border">Titre</th>
                <th class="text-left p-3 text-gray-600 border">Message</th>
                <th class="text-left p-3 text-gray-600 border">Date</th>
                <th class="p-3 text-gray-600 border text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="notif in notifications.data"
                :key="notif.id"
                class="border-b hover:bg-gray-100"
              >
                <td class="p-3 border">{{ notif.data.title }}</td>
                <td class="p-3 truncate max-w-xs border">{{ notif.data.message }}</td>
                <td class="p-3 border">{{ formatDate(notif.created_at) }}</td>
                <td class="p-3 text-center border">
                  <button
                    class="text-red-500 hover:underline"
                    @click="deleteNotification(notif.id)"
                  >
                    Supprimer
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="notifications.links" class="mt-6">
          <PaginateComponent :paginator="notifications" />
        </div>
      </div>
  </dashboard>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, router, useForm } from '@inertiajs/vue3';
import PaginateComponent from '../components/PaginateComponent.vue';
import dashboard from '../dashboard/dashboard.vue';


const notifications = ref(usePage().props.notifications || []);

const form = useForm({});

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('fr-FR', options);
};

const deleteNotification = async (id) => {
  if (confirm("Voulez-vous vraiment supprimer cette notification ?")) {
    try {
       form.delete(`/admin/notification/${id}`);
      notifications.value = notifications.value.filter((notif) => notif.id !== id);
    } catch (error) {
      console.error("Erreur lors de la suppression de la notification :", error);
    }
  }
};
</script>
