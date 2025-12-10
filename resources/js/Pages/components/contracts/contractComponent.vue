<template>
    <div class="bg-white p-4 rounded-lg border border-gray-200">
      <div class="space-y-6 text-sm">

        <!-- Logo -->
        <div class="flex justify-center md:justify-start items-start px-4 md:px-6">
          <img :src="logoAmoaman" alt="Logo AMOAMAN" class="h-16 md:h-20 object-cover" />
        </div>

        <!-- Type de contrat -->
        <div class="flex justify-center items-center text-center">
          <h1 class="font-bold text-xl md:text-2xl opacity-90 w-full md:w-[40rem]">
            {{ contract?.contract_type }}
          </h1>
        </div>

        <!-- Présentation de l'employeur -->
        <div class="space-y-2">
          <p class="opacity-90 text-center md:text-left">Entre les soussignés,</p>
          <div class="ml-0 md:ml-4 mt-2 space-y-2 opacity-90 text-justify">
            <p>
              La société <span class="font-semibold">AMOAMAN & ASSOCIES</span>,
              au capital de <span class="font-semibold">1 000 000 FCFA</span>,
              dont le siège social est à Cocody Riviera 3,
              immatriculée au RCS d'Abidjan sous le n° Cl-ABJ-03-2018-B12-33468.
              Le représentant légal est Monsieur
              <span class="font-semibold">AMOAKON EL HADJI DIHYE YORO</span>,
              en qualité de fondateur-directeur.
            </p>
          </div>
        </div>

        <!-- Informations du collaborateur -->
        <div class="space-y-2 flex flex-col opacity-90">
          <span class="self-end font-semibold px-4 md:px-10">D'une part</span>
          <div class="ml-0 md:ml-4 mt-2 space-y-2">
            <table class="w-full text-xs md:text-sm">
              <tbody>
                <tr>
                <td class="pr-2">Nom</td>
                <td>: {{ contract?.assigned_user.first_name }}</td>
              </tr>
              <tr>
                <td class="pr-2">Prénoms</td>
                <td>: {{ contract?.assigned_user.last_name }}</td>
              </tr>
              <tr>
                <td class="pr-2">Date et lieu de naissance</td>
                <td>: {{ contract?.assigned_user.birth_date }} à {{ contract?.assigned_user.birth_place }}</td>
              </tr>
              <tr>
                <td class="pr-2">Nationalité</td>
                <td>: {{ contract?.assigned_user.nationality }}</td>
              </tr>
              <tr>
                <td class="pr-2">Situation matrimoniale</td>
                <td>: {{ contract?.assigned_user.marital_status }}</td>
              </tr>
              <tr>
                <td class="pr-2">Lieu de résidence</td>
                <td>: {{ contract?.assigned_user.address }}</td>
              </tr>
              <tr>
                <td class="pr-2">Contact téléphonique</td>
                <td>: {{ contract?.assigned_user.phone_number }}</td>
              </tr>
              <tr>
                <td class="pr-2">Fonction</td>
                <td>: {{ contract?.assigned_user.poste }}</td>
              </tr>
              </tbody>
            </table>
          </div>
          <span class="self-end font-semibold px-4 md:px-10">D'autre part</span>
        </div>

        <!-- Clause introductive -->
        <div class="space-y-2 flex justify-center items-center">
          <h4 class="font-semibold opacity-90 underline text-center">IL A ÉTÉ CONVENU CE QUI SUIT :</h4>
        </div>

        <!-- Articles du contrat -->
        <div class="space-y-4">
          <div
            v-for="(article, idx) in contract?.articles"
            :key="idx"
            class="ml-0 md:ml-4 mt-2 space-y-2 opacity-90"
          >
            <p class="font-semibold underline py-2 text-center md:text-left">
              Article {{ idx + 1 }} : {{ article.title }}
            </p>
            <p class="whitespace-pre-line" v-html="article.contents"></p>
          </div>
        </div>

        <!-- Date et lieu -->
        <div class="flex flex-col items-center md:items-end p-4 space-y-2">
          <p class="text-center md:text-right">
            Fait en deux exemplaires, à Abidjan le
            {{ contract?.date_signature || today }}
          </p>
          <p class="text-center md:text-right">
            (Signatures des parties précédées de la mention « lu et approuvé »)
          </p>
        </div>

        <!-- Signatures en pied -->
        <div class="flex flex-col md:flex-row justify-between gap-4 md:gap-8">
          <div class="flex-1 space-y-4 text-center md:text-left">
            <p class="text-sm text-gray-600">
              {{ contract?.contract_type.toLowerCase().includes('stage') ? 'Stagiaire' : 'Collaborateur' }}
            </p>
            <span class="font-semibold text-xs">
              {{ contract?.assigned_user.first_name }} {{ contract?.assigned_user.last_name }}
            </span>
            <img :src="contract?.signature" class="h-16 w-auto border rounded-lg mx-auto md:mx-0" />
          </div>
          <div class="flex-1 flex flex-col justify-center md:justify-end items-center md:items-end">
            <div class="flex flex-col text-center md:text-right">
              <p class="text-sm text-gray-600 mb-2">AMOAMAN & ASSOCIES</p>
              <span class="font-semibold text-xs">Natacha KAKOU</span>
              <span class="font-semibold text-xs">Responsable Ressources Humaines</span>
            </div>
            <div class="px-4 mt-4">
              <img :src="tamponSvg" alt="Tampon" class="h-20 object-cover mx-auto md:mx-0" />
            </div>
          </div>
        </div>

      </div>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';


  // Imports des assets
  import logoAmoaman from '/public/logos/LogoAMOAMANnew.webp';
  import tamponSvg from '/public/icons/tampom.svg';


  // Props passées depuis Blade/Laravel
  const props = defineProps({
    contract: {
      type: Object,
      required: true,
      default: () => ({ articles: [] }),
    },
  });


  console.log(props.contract)

  const today = new Date().toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
  </script>

  <style scoped>
  /* Pas de /public/... ici, tout est géré par webpack/vite */
  </style>
