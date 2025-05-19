<script setup lang="ts">
import ButtonComponent from './components/ButtonComponent.vue';
import InputComponent from './components/InputComponent.vue';
import logoUrl from '/public/icons/line-md_logout.svg';
import logoUrlOrganigramme from '/public/icons/organigramme.svg';
import logoUrlKnowledge from '/public/icons/folder.svg';
import logoUrlReglement from '/public/icons/reglement.svg';
import logoUrlTeam from '/public/icons/team.svg';
import logoUrluseFullBox from '/public/icons/useFullBox.svg';
import logoUrlOnedrive from '/public/icons/oneDrive.svg';
import WeatherComponent from './components/WeatherComponent.vue';

import pdfFile from "../../../public/pdf/REGLEMENT _NTERIEUR_AMOAMAN.pdf";


  // URL pour afficher le PDF dans l'iframe
  const pdfUrl = `${pdfFile}#toolbar=0&navpanes=0&scrollbar=0`;

import CardComponent from './components/CardComponent.vue';
import CalandarComponent from './components/CalandarComponent.vue';
import { Link } from '@inertiajs/vue3';
import NavBarComponent from './components/NavBarComponent.vue';
import FrontendContent from './dashboard/frontendContent.vue';
</script>

<template>
  <FrontendContent>
    <div class="w-full flex justify-center">
  <div class="space-x-4 flex flex-wrap lg:flex-nowrap">
    <!-- Colonne de gauche -->
    <div class="flex  flex-col  w-full lg:w-[350px] space-y-4  sm:items-center sm:justify-center">
      <!-- Première ligne -->
      <div class="flex w-full justify-center lg:justify-between gap-4">
        <Link :href="route('organigramme')" method="get">
          <CardComponent
            imgWiddth="lg:w-[100px]"
            content="Organigramme"
            customClass="h-[150px] w-[180px] bg-white max-sm:h-[150px] max-sm:w-[180px] max-lg:w-[380px] max-lg:h-[350px] border"
            :logoUrl="logoUrlOrganigramme"
          >
          </CardComponent>
        </Link>
        <Link :href="route('knowledges')" method="get">
          <div class="size-[150px] max-sm:size-[150px] relative  max-lg:w-[350px] max-lg:h-[350px] know-ledge cursor-pointer">
                <h1 class="text-[14px] text-white absolute top-12 left-2">Base de connaissances</h1>
          </div>
        </Link>
      </div>

      <!-- Calendrier -->
      <div class="w-full  bg-white text-center max-lg:w-[90%] sm:w-[720px] lg:w-full   max-lg:mx-4 border">
        <h1 class="text-blue_white font-semibold py-1">Reservation de la salle</h1>
        <CalandarComponent></CalandarComponent>
      </div>
    </div>

    <!-- Colonne de droite -->
    <div class="flex flex-col max-md:justify-center  gap-4 w-full lg:w-auto">
      <!-- Cartes en ligne -->
      <div class="flex w-full flex-wrap sm:justify-center lg:flex-nowrap gap-4 px-0 max-lg:pr-4 mt-4">
        <!-- Tronbinoscope & Règlement intérieur -->
        <div class="flex max-sm:flex-col sm:justify-center lg:flex-col gap-4 w-full">
          <Link :href="route('membersList')" method="get">
            <CardComponent
              content="Tronbinoscope"
              customClass="lg:size-[150px]  bg-white sm:size-[350px] w-full border"
              :logoUrl="logoUrlTeam"
            >
            </CardComponent>
          </Link>
          <a target="_blank" :href="route('reglement')">
            <CardComponent
              imgWiddth="w-[100px]"
              content="Réglement interieur"
              customClass="lg:size-[150px]  bg-white sm:size-[350px] w-full border"
              :logoUrl="logoUrlReglement"
            >
            </CardComponent>
          </a>
        </div>

        <!-- Composant Météo -->
        <div class="lg:h-full sm:h-[650px] flex justify-center w-full ">
          <WeatherComponent
            :city="$page.props.city"
            :weatherData="$page.props[1]"
            :weatherTime="$page.props.weatherTime"
          >
          </WeatherComponent>
        </div>

        <div class="w-full  relative">
            <!-- Carte Suggestion -->
          <Link :href="route('suggestion')" method="get" class="abosolute sm:justify-center sm:flex lg:block">
            <CardComponent
              imgWiddth="w-[100px] "
              customClass="lg:h-[320px]  bg-white sm:h-[250px] sm:w-[720px] lg:w-[150px] border w-[100%] border"
              content="Suggestion"
              :logoUrl="logoUrluseFullBox"
            >
            </CardComponent>
          </Link>
        </div>

      </div>

      <!-- OneDrive et Application -->
      <div class="w-full flex max-lg:flex-col gap-6 lg:flex-wrap lg:gap-2 items-center justify-center lg:justify-start">
        <Link  :href="route('oneDriveLinks')" method="get">
          <CardComponent
            imgWiddth="w-[100%]"
            customClass="lg:h-[100px] sm:h-[330px] sm:w-[720px] lg:w-[360px] max-sm:w-[320px] max-sm:h-[150px] bg-gradient-to-buttom border"
            :logoUrl="logoUrlOnedrive"
          >
          </CardComponent>
        </Link>


        <Link  :href="route('our.app')" method="get" class="max-lg:mb-10">

          <div class="app rounded-lg">
            <CardComponent
              customClass="lg:h-[100px] sm:h-[330px] sm:w-[720px] lg:w-[200px] max-sm:w-[320px] max-sm:h-[150px] border">
            </CardComponent>
          </div>
        </Link>
      </div>
    </div>
  </div>
</div>

</FrontendContent>

</template>

<style>
.know-ledge{
    background-image: url('/public/icons/folder.svg');
    background-repeat: no-repeat;
    background-size: cover;
}

.app{
    background-image: url('/public/icons/app.webp');
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
