<template>
  <NavBarComponent />

  <div class="max-w-6xl mx-auto px-4 sm:px-8 lg:px-10 py-8">
    <!-- Header -->
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Paramètres du compte</h1>
        <p class="text-gray-500 mt-1">Gère ton profil, tes infos personnelles et ta sécurité.</p>
      </div>
      <span
        v-if="$page.props.flash.message"
        class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1.5 text-sm text-amber-800 border border-amber-200"
      >
        {{ $page.props.flash.message }}
      </span>
    </div>

    <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Sidebar -->
      <aside class="lg:col-span-4">
        <!-- Avatar card -->
        <div class="bg-white rounded-xl border shadow-sm p-6">
          <h2 class="text-sm font-semibold text-gray-800 mb-4">Photo de profil</h2>

          <div
            class="relative w-32 h-32 mx-auto"
            @dragover.prevent
            @drop.prevent="handleDrop"
          >
            <div
              class="group relative w-32 h-32 rounded-full overflow-hidden ring-1 ring-gray-200 shadow-sm bg-gray-50 flex items-center justify-center"
            >
              <img
                v-if="imagePreview"
                :src="imagePreview"
                alt="Profile preview"
                class="w-full h-full object-cover"
              />
              <div v-else class="text-gray-400 text-xs text-center px-4">
                Dépose une image ici
                <br />ou clique pour choisir
              </div>

              <!-- Overlay actions -->
              <button
                type="button"
                @click="triggerFile"
                class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-sm"
                aria-label="Changer la photo"
              >
                <Camera class="w-5 h-5 mr-2" />
                Changer
              </button>
            </div>

            <input
              ref="fileInput"
              id="profile_link"
              type="file"
              accept="image/*"
              class="hidden"
              @change="handleFile"
            />
          </div>

          <div class="mt-4 flex items-center justify-center gap-3">
            <button
              type="button"
              @click="triggerFile"
              class="px-3 py-1.5 rounded-md text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 border"
            >
              Importer
            </button>
            <button
              v-if="imagePreview"
              type="button"
              @click="removeImage"
              class="px-3 py-1.5 rounded-md text-sm text-red-700 bg-red-50 hover:bg-red-100 border border-red-200"
            >
              Supprimer
            </button>
          </div>

          <p class="mt-3 text-xs text-gray-500 text-center">
            PNG/JPG jusqu’à 2&nbsp;Mo. Carré recommandé (1:1).
          </p>

          <p v-if="$page.props.errors.profile_link" class="text-red-600 text-sm mt-3 text-center">
            {{ $page.props.errors.profile_link }}
          </p>
        </div>

        <!-- Quick tips -->
        <div class="bg-white rounded-xl border shadow-sm p-6 mt-6">
          <h3 class="text-sm font-semibold text-gray-800 mb-2">Conseils</h3>
          <ul class="text-sm text-gray-600 space-y-2 list-disc pl-5">
            <li>Utilise ton nom réel pour faciliter les signatures de contrats.</li>
            <li>Renseigne ton numéro pour la notification SMS.</li>
            <li>Garde ton profil à jour pour les documents RH.</li>
          </ul>
        </div>
      </aside>

      <!-- Main content -->
      <section class="lg:col-span-8 space-y-6">
        <!-- Tabs (pure client) -->
        <div class="bg-white rounded-xl border shadow-sm">
          <div class="border-b px-4 sm:px-6">
            <nav class="-mb-px flex gap-6" aria-label="Tabs">
              <button
                type="button"
                :class="tabClass('profile')"
                @click="activeTab = 'profile'"
              >
                Profil
              </button>
              <button
                type="button"
                :class="tabClass('personal')"
                @click="activeTab = 'personal'"
              >
                Informations personnelles
              </button>
              <button
                type="button"
                :class="tabClass('security')"
                @click="activeTab = 'security'"
              >
                Sécurité
              </button>
            </nav>
          </div>

          <!-- Tab panels -->
          <div class="p-4 sm:p-6">
            <!-- PROFILE TAB -->
            <div v-show="activeTab === 'profile'" class="space-y-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <InputComponent
                  name="first_name"
                  label="Prénom"
                  type="text"
                  autocomplete="given-name"
                  :errors="$page.props.errors.first_name"
                  v-model="form.first_name"
                />
                <InputComponent
                  name="last_name"
                  label="Nom"
                  type="text"
                  autocomplete="family-name"
                  :errors="$page.props.errors.last_name"
                  v-model="form.last_name"
                />

                <InputComponent
                  name="poste"
                  label="Poste"
                  type="text"
                  :errors="$page.props.errors.poste"
                  v-model="form.poste"
                />
                <InputComponent
                  name="team"
                  label="Équipe"
                  type="text"
                  :errors="$page.props.errors.team"
                  v-model="form.team"
                />

                <InputComponent
                  name="email"
                  label="Email"
                  type="email"
                  autocomplete="email"
                  :errors="$page.props.errors.email"
                  v-model="form.email"
                />
                <InputComponent
                  name="phone_number"
                  label="Téléphone"
                  type="text"
                  autocomplete="tel"
                  :errors="$page.props.errors.phone_number"
                  v-model="form.phone_number"
                />
              </div>
            </div>

            <!-- PERSONAL TAB -->
            <div v-show="activeTab === 'personal'" class="space-y-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <InputComponent
                  name="birth_place"
                  label="Lieu de naissance"
                  type="text"
                  :errors="$page.props.errors.birth_place"
                  v-model="form.birth_place"
                />
                <InputComponent
                  name="birth_date"
                  label="Date de naissance"
                  type="date"
                  :errors="$page.props.errors.birth_date"
                  v-model="form.birth_date"
                />
                <InputComponent
                  name="nationality"
                  label="Nationalité"
                  type="text"
                  :errors="$page.props.errors.nationality"
                  v-model="form.nationality"
                />

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Situation matrimoniale</label>
                 <!-- Option B: required seulement si l’onglet "personal" est ouvert -->
                <select
                name="marital_status"
                v-model="form.marital_status"
                :required="activeTab === 'personal'"
                class="w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                >
                    <option value="">Sélectionner...</option>
                    <option value="Célibataire sans enfant">Célibataire sans enfant</option>
                    <option value="Célibataire avec enfant">Célibataire avec enfant</option>
                    <option value="Marié(e) sans enfant">Marié(e) sans enfant</option>
                    <option value="Marié(e) avec enfant">Marié(e) avec enfant</option>
                    <option value="Divorcé(e) sans enfant">Divorcé(e) sans enfant</option>
                    <option value="Divorcé(e) avec enfant">Divorcé(e) avec enfant</option>
                    <option value="Veuf(ve) sans enfant">Veuf(ve) sans enfant</option>
                    <option value="Veuf(ve) avec enfant">Veuf(ve) avec enfant</option>
                  </select>
                  <p v-if="$page.props.errors.marital_status" class="text-red-600 text-sm mt-1">
                    {{ $page.props.errors.marital_status }}
                  </p>
                </div>

                <InputComponent
                  name="address"
                  label="Adresse"
                  type="text"
                  :errors="$page.props.errors.address"
                  v-model="form.address"
                  class="sm:col-span-2"
                />
              </div>
            </div>

            <!-- SECURITY TAB -->
            <div v-show="activeTab === 'security'" class="space-y-6">
              <div class="rounded-lg border p-4 sm:p-5">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Changer le mot de passe</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <InputComponent
                    name="password"
                    label="Nouveau mot de passe"
                    type="password"
                    :errors="$page.props.errors.password"
                    v-model="form.password"
                    autocomplete="new-password"
                  />
                  <InputComponent
                    name="password_confirmation"
                    label="Confirmer le mot de passe"
                    type="password"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                  />
                </div>
                <p class="text-xs text-gray-500 mt-2">
                  Laisse vide si tu ne souhaites pas le modifier.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sticky footer actions -->
        <div class="sticky bottom-0 bg-white/80 backdrop-blur border rounded-xl p-4 flex items-center justify-end gap-3">
          <p v-if="dirty && !isSubmitting" class="text-sm text-gray-500 mr-auto">Des modifications non enregistrées.</p>
          <button
            type="button"
            class="px-4 py-2 rounded-md border text-gray-700 bg-white hover:bg-gray-50"
            @click="resetForm"
            :disabled="isSubmitting"
          >
            Annuler
          </button>
          <button
            type="submit"
            class="inline-flex items-center px-4 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60"
            :disabled="isSubmitting || !dirty"
          >
            <Loader2 v-if="isSubmitting" class="w-4 h-4 mr-2 animate-spin" />
            Enregistrer les modifications
          </button>
        </div>
      </section>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onBeforeUnmount } from "vue";
import { router } from "@inertiajs/vue3";
import InputComponent from "../../components/InputComponent.vue";
import NavBarComponent from "../../components/NavBarComponent.vue";
import { Camera, Loader2 } from "lucide-vue-next";

const props = defineProps({
  user: { type: Object, required: true },
});

// --- State
const activeTab = ref("profile");
const isSubmitting = ref(false);
const uploadProgress = ref(null);
const dirty = ref(false);

const initial = reactive({
  id: props.user.id || "",
  first_name: props.user.first_name || "",
  last_name: props.user.last_name || "",
  poste: props.user.poste || "",
  team: props.user.team || "",
  email: props.user.email || "",
  phone_number: props.user.phone_number || "",
  profile_link: null,
  linkedin_link: props.user.linkedin_link || "",
  birth_place: props.user.birth_place || "",
  birth_date: props.user.birth_date || null,
  nationality: props.user.nationality || "",
  marital_status: props.user.marital_status || "",
  address: props.user.address || "",
  password: "",
  password_confirmation: "",
});

const form = ref({ ...initial });

const imagePreview = ref(
  props.user.profile_link ? `/storage/${props.user.profile_link}` : null
);

// --- Avatar helpers
const fileInput = ref(null);

console.log( imagePreview.value)

const triggerFile = () => fileInput.value?.click();

const handleFile = (e) => {
  const file = e.target.files?.[0];
  if (!file) return;
  if (file.size > 2 * 1024 * 1024) {
    alert("Fichier trop volumineux (max 2 Mo).");
    return;
  }
  imagePreview.value = URL.createObjectURL(file);
  form.value.profile_link = file;
  dirty.value = true;
};

const handleDrop = (e) => {
  const file = e.dataTransfer.files?.[0];
  if (!file) return;
  if (!file.type.startsWith("image/")) return;
  if (file.size > 2 * 1024 * 1024) {
    alert("Fichier trop volumineux (max 2 Mo).");
    return;
  }
  imagePreview.value = URL.createObjectURL(file);
  form.value.profile_link = file;
  dirty.value = true;
};

const removeImage = () => {
  imagePreview.value = null;
  form.value.profile_link = null;
  dirty.value = true;
};

// --- Dirty tracking
watch(
  form,
  (val) => {
    // Compare à "initial" (simple check)
    dirty.value = JSON.stringify({ ...val, profile_link: !!val.profile_link }) !==
      JSON.stringify({ ...initial, profile_link: !!initial.profile_link });
  },
  { deep: true }
);

// Warn on unload if dirty
const beforeUnload = (e) => {
  if (!dirty.value) return;
  e.preventDefault();
  e.returnValue = "";
};
window.addEventListener("beforeunload", beforeUnload);
onBeforeUnmount(() => window.removeEventListener("beforeunload", beforeUnload));

// --- Tabs UI helper
const tabClass = (tab) =>
  [
    "inline-flex items-center  border-b-2 px-1 py-3 text-sm font-medium",
    activeTab.value === tab
      ? "border-blue-600 text-blue-700"
      : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300",
  ].join(" ");

// --- Reset
const resetForm = () => {
  form.value = { ...initial };
  imagePreview.value = props.user.profile_link ? `/storage/${props.user.profile_link}` : null;
  dirty.value = false;
};

const submit = () => {
  const fd = new FormData()

  Object.entries(form.value).forEach(([k, v]) => {
    // Normalise la date invalide (sera convertie en null par le middleware Laravel)
    if (k === 'birth_date' && (v === '0000-00-00' || v === null)) v = ''

    // N’envoie le fichier que s’il existe
    if (k === 'profile_link') {
      if (v instanceof File) {
        fd.append(k, v, v.name)
      }
      return
    }

    // Important: on n’ignore pas les champs vides (la validation backend en a besoin)
    fd.append(k, v ?? '')
  })

  // Méthode override pour PUT
  fd.append('_method', 'put')

  // Utilise ta route nommée si Ziggy est dispo, sinon fallback origin
  const url = typeof route === 'function'
    ? route('profile.update', props.user.id)
    : `${window.location.origin}/profile/${props.user.id}`

  router.post(url, fd, {
    forceFormData: true,
    preserveScroll: true,
    onStart: () => {
      isSubmitting.value = true
      uploadProgress.value = null
    },
    onProgress: (e) => {
      // e.percentage (0..100)
      uploadProgress.value = e?.percentage ?? null
    },
    onSuccess: (page) => {
      // Reset champs sensibles & état "dirty"
      form.value.password = ''
      form.value.password_confirmation = ''
      dirty.value = false

      // Si tu reçois le user à jour dans les props, tu peux aussi re-synchroniser:
      // if (page?.props?.user) {
      //   Object.assign(initial, page.props.user)
      //   form.value = { ...initial, profile_link: null, password: '', password_confirmation: '' }
      //   imagePreview.value = page.props.user.profile_link ? `/storage/${page.props.user.profile_link}` : null
      // }
      alert('Utilisateur mis à jour avec succès !')
    },
    onError: (errors) => {
      // Oriente l’onglet vers le 1er groupe qui a des erreurs
      if (errors.birth_place || errors.birth_date || errors.nationality || errors.marital_status || errors.address) {
        activeTab.value = 'personal'
      } else if (errors.password || errors.password_confirmation) {
        activeTab.value = 'security'
      } else {
        activeTab.value = 'profile'
      }
      console.error('Update validation errors', errors)
    },
    onFinish: () => {
      isSubmitting.value = false
      uploadProgress.value = null
    },
  })
}


</script>

<style scoped>
/* légère amélioration du focus */
:focus-visible {
  outline: none;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, .35);
  border-radius: .5rem;
}
</style>
