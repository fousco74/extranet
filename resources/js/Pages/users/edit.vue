<template>
  <Dashboard>
    <div class="border shadow-md w-full md:w-[80%] h-screen p-6 bg-white">
      <div class="flex justify-center mb-5">
        <h1 class="text-blue text-3xl">Modifier un Utilisateur</h1>
      </div>
      <form @submit.prevent="submit" enctype="multipart/form-data">
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

                    {{ imagePreview }}


          <!-- Input pour choisir un fichier -->
          <input
            type="file"
            @change="fileChange"
            id="profile_link"
            class="hidden"
          />

          <!-- Affichage des erreurs -->
          <span v-if="$page.props.errors.profile_link" class="text-red-500 text-sm mt-2">
            {{ $page.props.errors.profile_link }}
          </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mt-4">
          <InputComponent
            name="first_name"
            type="text"
            :errors="$page.props.errors.first_name"
            placeholder="Prénom"
            v-model="form.first_name"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="last_name"
            type="text"
            :errors="$page.props.errors.last_name"
            placeholder="Nom"
            v-model="form.last_name"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="email"
            type="email"
            :errors="$page.props.errors.email"
            placeholder="Email"
            v-model="form.email"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="poste"
            type="text"
            :errors="$page.props.errors.poste"
            placeholder="Votre poste"
            v-model="form.poste"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <div class="mb-4">
                <select name="team" v-model="form.team" class="border rounded w-full p-2" required>
                    <option value="" >Sélectionnez une equipe</option>
                    <option value="interne">Interne</option>
                    <option value="externe">Externe</option>
                </select>
                <div v-if="$page.props.errors.team" class="text-red-500">
                  {{ $page.props.errors.team }}
                </div>
            </div>

          <InputComponent
            name="phone_number"
            type="tel"
            :errors="$page.props.errors.phone_number"
            placeholder="Numéro de téléphone"
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
            type="date"
            :errors="$page.props.errors.birth_date"
            placeholder="Date de naissance"
            label="Date de naissance"
            v-model="form.birth_date"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="nationality"
            type="text"
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
            :errors="$page.props.errors.address"
            placeholder="addresse"
            v-model="form.address"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="password"
            type="password"
            :errors="$page.props.errors.password"
            placeholder="Mot de passe"
            v-model="form.password"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="password_confirmation"
            type="password"
            :errors="$page.props.errors.password"
            placeholder="Confirmer le mot de passe"
            v-model="form.password_confirmation"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="ordre_team"
            type="number"
            :errors="$page.props.errors.ordre_team"
            placeholder="Ordre d'affichage"
            v-model="form.ordre_team"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
        </div>

        <ButtonComponent
            content="Mettre à jour"
            customClass="text-white bg-blue-600 hover:bg-blue-700 px-4 mt-6"
        />
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import InputComponent from '../components/InputComponent.vue';
import ButtonComponent from '../components/ButtonComponent.vue';
import Dashboard from '../dashboard/dashboard.vue';

const props = defineProps(["user"]);

const form = ref({
  first_name: props.user.first_name || '',
  last_name: props.user.last_name || '',
  email: props.user.email || '',
  poste: props.user.poste || '',
  team: props.user.team || '',
  phone_number: props.user.phone_number || '',
  profile_link: null,
  password: null,
  ordre_team: props.user.ordre_team || '',
  birth_place : props.user.birth_place || '',
  birth_date: props.user.birth_date || '',
  nationality : props.user.nationality || '',
  marital_status : props.user.marital_status || '',
  address: props.user.address|| ''
});

const imagePreview = ref(`public/storage/${props.user.profile_link}`)
console.log(imagePreview.value)

const fileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    imagePreview.value = URL.createObjectURL(file); // Crée un aperçu local de l'image choisie
    form.value.profile_link = file; // Stocke le fichier pour l'envoi
  }
}

const submit = () => {
  const formData = new FormData();
  for (const key in form.value) {
    if (!form.value[key]){
      continue
    }
    formData.append(key, form.value[key]);
  }

  formData.append("_method", "put");

  router.post(`/admin/users/${props.user.id}`, formData, {
    forceFormData: true,
  });
};
</script>
