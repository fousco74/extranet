<script setup lang="ts">
import ButtonComponent from './components/ButtonComponent.vue';
import InputComponent from './components/InputComponent.vue';
import logoUrl from '/public/icons/lineLogoutBlack.png';
import logoUrlOrganigramme from '/public/icons/organigramme.png';
import logoUrlKnowledge from '/public/icons/folder.png';
import logoUrlReglement from '/public/icons/reglement.png';
import logoUrlTeam from '/public/icons/team.png';
import logoUrluseFullBox from '/public/icons/useFullBox.png';
import logoUrlOnedrive from '/public/icons/oneDrive.png';
import WeatherComponent from './components/WeatherComponent.vue';

import pdfFile from "../../../public/pdf/REGLEMENT _NTERIEUR_AMOAMAN.pdf";


  // URL pour afficher le PDF dans l'iframe
  const pdfUrl = `${pdfFile}#toolbar=0&navpanes=0&scrollbar=0`;

import CardComponent from './components/CardComponent.vue';
import CalandarComponent from './components/CalandarComponent.vue';
import { Link } from '@inertiajs/vue3';
import NavBarComponent from './components/NavBarComponent.vue';
</script>

<template>
    <NavBarComponent></NavBarComponent>
    <div class="w-full flex justify-center">
  <div class="space-x-4 flex mt-8 flex-wrap lg:flex-nowrap">
    <!-- Colonne de gauche -->
    <div class="flex flex-col w-full lg:w-[350px] space-y-4">
      <!-- Première ligne -->
      <div class="flex w-full justify-center lg:justify-between gap-4">
        <Link :href="route('organigramme')" method="get">
          <CardComponent 
            imgWiddth="lg:w-[100px] max-sm-[200px]" 
            content="Organigramme" 
            customClass="h-[150px] w-[180px] border" 
            :logoUrl="logoUrlOrganigramme"
          >
          </CardComponent>
        </Link>
        <Link :href="route('knowledges')" method="get">
          <div class="size-[150px] know-ledge cursor-pointer"></div>
        </Link>
      </div>

      <!-- Calendrier -->
      <div class="w-full text-center max-sm:w-[90%] max-sm:mx-4 border">
        <h1 class="text-blue_white font-semibold py-1">Reservation de la salle</h1>
        <CalandarComponent></CalandarComponent>
      </div>
    </div>

    <!-- Colonne de droite -->
    <div class="flex flex-col max-sm:justify-center gap-4 w-full lg:w-auto">
      <!-- Cartes en ligne -->
      <div class="flex w-full flex-wrap lg:flex-nowrap gap-4 px-0">
        <!-- Tronbinoscope & Règlement intérieur -->
        <div class="flex flex-col gap-4 w-full">
          <Link :href="route('membersList')" method="get">
            <CardComponent 
              content="Tronbinoscope" 
              customClass="sm:size-[150px] w-full border" 
              :logoUrl="logoUrlTeam"
            >
            </CardComponent>
          </Link>
          <a download :href="pdfUrl">
            <CardComponent 
              imgWiddth="w-[100px] max-sm-[200px]" 
              content="Réglement interieur" 
              customClass="sm:size-[150px] w-full border" 
              :logoUrl="logoUrlReglement"
            >
            </CardComponent>
          </a>
        </div>

        <!-- Composant Météo -->
        <div class="sm:h-full flex justify-center w-full">
          <WeatherComponent 
            :city="$page.props.city" 
            :weatherData="$page.props[1]" 
            :weatherTime="$page.props.weatherTime"
          >
          </WeatherComponent>
        </div>

        <!-- Carte Suggestion -->
        <Link :href="route('suggestion')" method="get">
          <CardComponent 
            imgWiddth="w-[100px] max-sm-[200px]" 
            customClass="sm:h-[320px] sm:w-[150px] border w-[250%] border" 
            content="Suggestion" 
            :logoUrl="logoUrluseFullBox"
          >
          </CardComponent>
        </Link>
      </div>

      <!-- OneDrive et Application -->
      <div class="w-full flex max-sm:flex-col gap-6 flex-wrap lg:gap-2 items-center justify-center lg:justify-start">
        <Link :href="route('oneDriveLinks')" method="get">
          <CardComponent
            imgWiddth="w-[100%] max-sm-[200px]" 
            customClass="h-[100px] w-[320px] lg:w-[360px] bg-gradient-to-buttom border" 
            :logoUrl="logoUrlOnedrive"
          >
          </CardComponent>
        </Link>
        <Link :href="route('our.app')" method="get" class="max-md:mb-10">
          <div class="app rounded-lg">
            <CardComponent 
              customClass="h-[100px] w-[320px] lg:w-[200px] border"
            >
            </CardComponent>
          </div>
        </Link>
      </div>
    </div>
  </div>
</div>

</template>

<style>
.know-ledge{
    background-image: url('/public/icons/folder.png');
    background-repeat: no-repeat;
    background-size: cover;
}

.app{
    background-image: url('/public/icons/app.png');
    background-repeat: no-repeat;
    background-size: cover;
}
</style>