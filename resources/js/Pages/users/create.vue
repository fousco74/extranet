<template>
  <Dashboard>
    <div class="border shadow-md w-full md:w-[80%] p-6 md:p-10 bg-white">
      <div class="flex justify-center mb-6 md:mb-10">
        <h1 class="text-blue text-2xl md:text-3xl font-semibold">Ajouter un Utilisateur</h1>
      </div>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
            placeholder="votre poste"
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
            name="profile_link"
            type="file"
            :errors="$page.props.errors.profile_link"
            placeholder="Lien de Profil"
            @input="fileChange"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
          <InputComponent
            name="linkedin_link"
            type="url"
            :errors="$page.props.errors.linkedin_link"
            placeholder="Lien LinkedIn"
            v-model="form.linkedin_link"
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
            placeholder="Confirmer le Mot de passe"
            v-model="form.password_confirmation"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />

          <InputComponent
            name="ordre_team"
            type="number"
            :errors="$page.props.errors.ordre_team"
            placeholder="L'ordre d'affichage"
            v-model="form.ordre_team"
            divClass="mb-4 w-full"
            inputClass="w-full"
          />
        </div>

        <ButtonComponent
          content="Créer l'Utilisateur"
          customClass="bg-blue text-white px-4 py-2 mt-4"
        />
      </form>
    </div>
  </Dashboard>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import InputComponent from '../Components/InputComponent.vue';
import ButtonComponent from '../Components/ButtonComponent.vue';
import Dashboard from '../dashboard/dashboard.vue';

const form = useForm({
  first_name: null,
  last_name: null,
  email: null,
  poste: null,
  team: "",
  phone_number: null,
  profile_link: null,
  linkedin_link: null,
  password: null,
  password_confirmation: null,
  ordre_team: null
});

const fileChange = (file) => {
  form.profile_link = file.target.files[0]
}

const submit = () => {
  console.log(form)
  form.post(route('users.store'));
};
</script>

