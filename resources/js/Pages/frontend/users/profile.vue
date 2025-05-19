<template>
  <NavBarComponent></NavBarComponent>
  <div class="flex flex-col justify-center items-center px-4 sm:px-8 lg:px-16">
    <h1 class="text-2xl font-bold mb-5 text-start my-6">My Profile</h1>

    <form @submit.prevent="submit" class="flex justify-center">

      <div class="my-4 w-full sm:w-[80%] md:w-[70%] lg:w-[60%]">
        <div class="flex justify-center items-center mb-4">
        <span v-if="$page.props.flash.message" class="text-center bg-orange-700 bg-opacity-25">
          {{ $page.props.flash.message }}
        </span>
      </div>
        <div class="flex flex-col justify-center items-center">
          <!-- Zone d'aperçu de l'image -->
          <div
            class="relative w-32 h-32 rounded-full border-2 border-gray-300 flex justify-center items-center cursor-pointer overflow-hidden hover:border-blue-500"
          >
            <label for="profile_link" class="absolute w-full h-full flex justify-center items-center cursor-pointer">
              <template v-if="imagePreview">
                <img :src="imagePreview" alt="Profile Preview" class="w-full h-full object-cover" />
              </template>
              <template v-else>
                <span class="text-gray-500 text-sm text-center">Click to upload</span>
              </template>
            </label>
          </div>

          <!-- Input pour choisir un fichier -->
          <input
            type="file"
            @change="handleFile"
            id="profile_link"
            class="hidden"
          />

          <!-- Affichage des erreurs -->
          <span v-if="$page.props.errors.profile_link" class="text-red-500 text-sm mt-2">
            {{ $page.props.errors.profile_link }}
          </span>
        </div>

        <!-- Champs de formulaire dans une grille responsive -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
          <InputComponent
            name="first_name"
            label="First Name"
            type="text"
            :errors="$page.props.errors.first_name"
            v-model="form.first_name"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="last_name"
            label="Last Name"
            type="text"
            :errors="$page.props.errors.last_name"
            v-model="form.last_name"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="poste"
            label="Poste"
            type="text"
            :errors="$page.props.errors.poste"
            v-model="form.poste"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="team"
            label="Team"
            type="text"
            :errors="$page.props.errors.team"
            v-model="form.team"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="email"
            label="Email"
            type="email"
            :errors="$page.props.errors.email"
            v-model="form.email"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="phone_number"
            label="Phone Number"
            type="text"
            :errors="$page.props.errors.phone_number"
            v-model="form.phone_number"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="birth_place"
            type="text"
            :errors="$page.props.errors.birth_place"
            placeholder="Lieu de naissance"
            v-model="form.birth_place"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="birth_date"
            label="Date de naissance"
            type="date"
            :errors="$page.props.errors.birth_date"
            placeholder="Date de naissance"
            v-model="form.birth_date"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="nationality"
            type="text"
            label="nationalité"
            :errors="$page.props.errors.nationality"
            placeholder="nationalité"
            v-model="form.nationality"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <div class="mb-4">
                <select name="marital_status" v-model="form.marital_status" class="border rounded w-full p-2" required>
                    <option value="">Situation matrimoniale</option>
                    <option value="Célibataire sans enfant">Célibataire sans enfant</option>
                    <option value="Célibataire avec enfant">Célibataire avec enfant</option>
                    <option value="Marié(e) sans enfant">Marié(e) sans enfant</option>
                    <option value="Marié(e) avec enfant">Marié(e) avec enfant</option>
                    <option value="Divorcé(e) sans enfant">Divorcé(e) sans enfant</option>
                    <option value="Divorcé(e) avec enfant">Divorcé(e) avec enfant</option>
                    <option value="Veuf(ve) sans enfant">Veuf(ve) sans enfant</option>
                    <option value="Veuf(ve) avec enfant">Veuf(ve) avec enfant</option>
                </select>
                <div v-if="$page.props.errors.marital_status" class="text-red-500">
                    {{ $page.props.errors.marital_status }}
                </div>
            </div>

          <InputComponent
            name="address"
            type="text"
            label="addresse"
            :errors="$page.props.errors.address"
            placeholder="addresse"
            v-model="form.address"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="password"
            label="Password"
            type="password"
            :errors="$page.props.errors.password"
            v-model="form.password"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="password_confirmation"
            label="Confirm Password"
            type="password"
            v-model="form.password_confirmation"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
        </div>

        <button type="submit" class="bg-blue_black text-white px-4 py-2 rounded mt-6 w-full sm:w-auto">
          Update Profile
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import InputComponent from "../../components/InputComponent.vue";
import NavBarComponent from "../../components/NavBarComponent.vue";

const props = defineProps({
  user: Object,
});

const form = ref({
  id: props.user.id || '',
  first_name: props.user.first_name || "",
  last_name: props.user.last_name || "",
  poste: props.user.poste || "",
  team: props.user.team || "",
  email: props.user.email || "",
  phone_number: props.user.phone_number || "",
  profile_link: null,
  linkedin_link: props.user.linkedin_link || "",
  birth_place : props.user.birth_place || '',
  birth_date: props.user.birth_date || null,
  nationality : props.user.nationality || '',
  marital_status : props.user.marital_status || '',
  address: props.user.address|| '',
  password: "",
  password_confirmation: "",
});
const imagePreview = ref(`/storage/${props.user.profile_link}`);

const handleFile = (e) => {
  const file = e.target.files[0];
  if (file) {
    imagePreview.value = URL.createObjectURL(file); // Crée un aperçu local de l'image choisie
    form.value.profile_link = file; // Stocke le fichier pour l'envoi
  }
};

const submit = () => {
  const formData = new FormData();
  for (const key in form.value) {
    if (!form.value[key]) {
        continue
    }
    formData.append(key, form.value[key]);
  }

  formData.append("_method", "put");

  router.post(`/profile/${props.user.id}`, formData, {
  forceFormData: true,
  onSuccess: () => {
    alert('Utilisateur mis à jour avec succès !');
  }
});

};

</script>
