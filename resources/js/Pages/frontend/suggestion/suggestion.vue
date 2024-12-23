<template>
  <NavBarComponent></NavBarComponent>
  <div class="container mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-5">Envoyer une suggestion</h1>
    <form @submit.prevent="submit">
      <!-- Champ Objet -->
      <div class="mb-4">
        <label for="objet" class="block text-gray-700 font-bold mb-2">Objet</label>
        <input
          type="text"
          id="objet"
          name="objet"
          v-model="form.objet"
          class="border border-gray-300 rounded-md w-full p-2"
          placeholder="Entrez l'objet de votre suggestion"
        />
        <span v-if="form.errors.objet" class="text-red-500 text-sm">{{ form.errors.objet }}</span>
      </div>

      <!-- Champ Message -->
      <div class="mb-4">
        <label for="message" class="block text-gray-700 font-bold mb-2">Message</label>
        <textarea
          id="message"
          name="message"
          v-model="form.message"
          class="border border-gray-300 rounded-md w-full p-2"
          rows="5"
          placeholder="Entrez votre message"
        ></textarea>
        <span v-if="form.errors.message" class="text-red-500 text-sm">{{ form.errors.message }}</span>
      </div>

      <!-- Bouton Envoyer -->
      <div>
        <button
          type="submit"
          class="bg-blue text-white px-4 py-2 rounded-md hover:bg-blue-600"
          :disabled="form.processing"
        >
          {{ form.processing ? "Envoi..." : "Envoyer" }}
        </button>
      </div>
    </form>

    <!-- Message de succès -->
    <p v-if="successMessage" class="text-green-500 mt-5">{{ successMessage }}</p>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import NavBarComponent from "../../components/NavBarComponent.vue";

const form = useForm({
  objet: "",
  message: "",
});

const successMessage = ref(null);

const submit = () => {
  form.post(route("suggestions.send"), {
    onSuccess: () => {
      successMessage.value = "Votre suggestion a été envoyée avec succès.";
      form.reset();
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
};
</script>

<style scoped>
.container {
  max-width: 600px;
}
</style>
