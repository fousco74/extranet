<template>
  <NavBarComponent></NavBarComponent>
  <div class="flex justify-center items-center pl-7">
    <div class="w-full">
      <div class="w-full h-screen overflow-y-auto">
        <!-- Filtrage par type d'équipe -->
        <div class="flex justify-center float-end mr-20 max-sm:mr-8 mt-4">
          <form @submit.prevent="applyFilter">
            <select v-model="form.equipe" @change="applyFilter" class="border rounded p-2">
              <option value="tous" :selected="form.equipe == 'tous'">Tous</option>
              <option value="interne" :selected="form.equipe == 'interne'">Internes</option>
              <option value="externe" :selected="form.equipe == 'externe'">Externes</option>
            </select>
          </form>
        </div>

        <!-- Ligne principale -->
        <div class="pr-10 flex w-full justify-center">
          <div class="w-full flex justify-center flex-wrap md:gap-10 max-md:gap-1 mt-6">
            <div v-for="(member, index) in $page.props.membersFird" :key="'fird' + index"
              class="w-[120px] max-sm:w-[90px] max-lg:w-[140px] relative group">
              <img :src="`/storage/${member.profile_link}`" alt="image" class="size-full object-cover" />
                <!-- Conteneur pour les deux divs permutables -->
                <div class="relative h-auto">
                  <!-- div 1 (visible par défaut) -->
                  <div :class="[baseHoverClass, hoverClass, index % 2 === 0 ? 'bg-white border-[#223451]' : 'bg-[#C9847C] text-white']">
                    <span class="text-nowrap font-bold text-[7px]">
                      {{ member.first_name }} {{ member.last_name }}
                    </span>
                    <br />
                    <span class="text-nowrap">{{ member.poste }}</span>
                  </div>

                  <!-- div 2 (visible au survol) -->
                  <div class="absolute h-10 inset-0 opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100 py-2 text-center text-[7px] max-sm:text-[6px] leading-[12px]  p-1 rounded-xl border-2 bg-[#223451]"
                  :class="[baseHoverClass, hoverClass, index % 2 === 0 ? 'bg-white border-[#223451]' : 'bg-[#C9847C] text-white']">

                    <span class="text-nowrap">{{ member.email }}</span>
                    <br />
                    <span class="text-nowrap">{{ member.phone_number }}</span>
                  </div>
                </div>
            </div>
          </div>
        </div>

        <!-- Second ligne -->
        <div v-if="$page.props.memberFour" class="pr-10 py-7 flex w-full justify-center">
          <div class="w-full flex justify-center flex-wrap md:gap-10 max-md:gap-1 mt-4">
            <div class="w-[120px] max-sm:w-[90px] max-lg:w-[140px] relative group">
              <img :src="getImagePath($page.props.memberFour.profile_link)" alt="image"
                class="size-full object-cover" />
                <div class="relative h-auto ">
                  <div
                    class="absolute h-10 inset-0 transition-opacity duration-300 ease-in-out group-hover:opacity-0 py-2 text-center text-[7px] max-sm:text-[5px] leading-[10px]  p-1 rounded-xl border-2 bg-[#223451] text-white">
                    <span class="text-nowrap font-bold text-[7px]">{{ $page.props.memberFour.first_name + ' ' +
                      $page.props.memberFour.last_name }}</span>
                    <br />
                    <span class="text-nowrap">{{ $page.props.memberFour.poste }}</span>
                  </div>

                  <div
                    class="absolute h-10 inset-0 opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100 py-2 text-center text-[7px] max-sm:text-[6px] leading-[12px]  p-1 rounded-xl border-2 bg-[#223451] text-white">
                    <span class="text-nowrap">{{ $page.props.memberFour.email }}</span>
                    <br />
                    <span class="text-nowrap">{{ $page.props.memberFour.phone_number }}</span>
                  </div>
                </div>
            </div>
          </div>
        </div>

        <!-- Membres externes -->
        <div v-if="$page.props.filterEquipe === 'externe'" class="pr-10 py-7 flex w-full justify-center">
          <div class="w-full flex justify-center flex-wrap md:gap-10 max-md:gap-1 mt-4">
            <div v-for="(member, index) in $page.props.membersLeads" :key="'leads' + index"
              class="w-[120px] max-sm:w-[90px] max-lg:w-[140px] relative group">
              <img :src="getImagePath(member.profile_link)" alt="image" class="size-full object-cover" />
              <a v-if="canSeeAll"  :href="route('user.stats', member.id)" title="Voir les statistiques de cet utilisateur">
                <div class="relative h-auto cursor-pointer">
                  <div :class="baseHoverClass + ' bg-[#223451] text-white'">
                    <span class="text-nowrap font-bold text-[7px]">{{ member.first_name + ' ' + member.last_name
                      }}</span>
                    <br />
                    <span class="text-nowrap">{{ member.poste }}</span>
                  </div>

                  <div :class="hoverClass + ' bg-[#223451] text-white'">
                    <span class="text-nowrap">{{ member.email }}</span>
                    <br />
                    <span class="text-nowrap">{{ member.phone_number }}</span>
                  </div>
                </div>
              </a>

              <div v-else class="relative h-auto">
                  <div :class="baseHoverClass + ' bg-[#223451] text-white'">
                    <span class="text-nowrap font-bold text-[7px]">{{ member.first_name + ' ' + member.last_name
                      }}</span>
                    <br />
                    <span class="text-nowrap">{{ member.poste }}</span>
                  </div>

                  <div :class="hoverClass + ' bg-[#223451] text-white'">
                    <span class="text-nowrap">{{ member.email }}</span>
                    <br />
                    <span class="text-nowrap">{{ member.phone_number }}</span>
                  </div>
                </div>
            </div>
          </div>
        </div>


        <div class="pr-10 py-4 flex w-full justify-center">
          <div class="w-[100%] flex justify-center flex-wrap md:gap-10 max-md:gap-1 mt-6">
            <div v-for="(member, index) in $page.props.membersRest" :key="index"
              class="w-[120px] max-sm:w-[90px] max-sm:my-6 my-4 max-lg:w-[140px] relative group">
              <!-- Image qui reste toujours visible -->
              <img :src="`/storage/${member.profile_link}`" alt="image" class="size-full object-cover" />

              <a v-if="canSeeAll" :href="route('user.stats', member.id)" title="Voir les statistiques de cet utilisateur">
                <!-- Conteneur pour les deux divs permutables -->
                <div class="relative h-auto cursor-pointer">
                  <!-- div 1 (visible par défaut) -->
                  <div :class="[
                    'absolute h-10 inset-0 transition-opacity duration-300 ease-in-out group-hover:opacity-0 py-2 text-center text-[7px] max-sm:text-[5px] leading-[10px] p-1 rounded-xl border-2 text-white',
                    getBgClass(index)
                  ]">
                    <span class="text-nowrap font-bold text-[7px]">
                      {{ member.first_name }} {{ member.last_name }}
                    </span>
                    <br />
                    <span class="text-nowrap">{{ member.poste }}</span>
                  </div>

                  <!-- div 2 (visible au survol) -->
                  <div :class="[
                    'absolute h-10 inset-0 opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100 py-2 text-center text-[7px] max-sm:text-[6px] leading-[12px] p-1 rounded-xl border-2 text-white',
                    getBgClass(index)
                  ]">
                    <span class="text-nowrap">{{ member.email }}</span>
                    <br />
                    <span class="text-nowrap">{{ member.phone_number }}</span>
                  </div>
                </div>
              </a>

               <!-- Conteneur pour les deux divs permutables -->
               <div v-else class="relative h-auto">
                  <!-- div 1 (visible par défaut) -->
                  <div :class="[
                    'absolute h-10 inset-0 transition-opacity duration-300 ease-in-out group-hover:opacity-0 py-2 text-center text-[7px] max-sm:text-[5px] leading-[10px] p-1 rounded-xl border-2 text-white',
                    getBgClass(index)
                  ]">
                    <span class="text-nowrap font-bold text-[7px]">
                      {{ member.first_name }} {{ member.last_name }}
                    </span>
                    <br />
                    <span class="text-nowrap">{{ member.poste }}</span>
                  </div>

                  <!-- div 2 (visible au survol) -->
                  <div :class="[
                    'absolute h-10 inset-0 opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100 py-2 text-center text-[7px] max-sm:text-[6px] leading-[12px] p-1 rounded-xl border-2 text-white',
                    getBgClass(index)
                  ]">
                    <span class="text-nowrap">{{ member.email }}</span>
                    <br />
                    <span class="text-nowrap">{{ member.phone_number }}</span>
                  </div>
                </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";
import NavBarComponent from "../../components/NavBarComponent.vue";

const props = defineProps(["equipe", "canSeeAll"])

const form = useForm({
  equipe: "interne"
});

onMounted(()=>{
  form.equipe = props.equipe
})


// Méthode pour déterminer la classe de fond
const getBgClass = (index) => {
  if (index < 6) {
    const colorIndex = Math.floor(index / 2) % 2; // 0 ou 1
    return colorIndex === 0 ? "bg-[#223451]" : "bg-[#C9847C]";
  }
  return index % 2 === 0 ? "bg-[#C9847C]" : "bg-[#223451]";
};

const applyFilter = () => {
  form.get(route("membersList"), { preserveState: true });
};

const getImagePath = (path) => `/storage/${path}`;

const baseHoverClass =
  "absolute h-10 inset-0 transition-opacity duration-300 ease-in-out group-hover:opacity-0 py-2 text-center text-[7px] max-sm:text-[5px] leading-[10px] border p-1 rounded-xl border-2";

const hoverClass = "group-hover:opacity-100";
const textClass = "text-nowrap font-bold text-[7px]";
</script>

<style>
/*
  body {
    background-image: url("background/FondTrombinoscopeAMOAMAN.png");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    margin: 0;
    height: 100vh;
    width: 100vw;
    overflow: hidden;
  }

  @media (max-width: 1024px) {
    body {
      background-image: url("background/fondtablete.png");
    }
  }

  @media (max-width: 768px) {
    body {
      background-image: url("background/fondtelephone.png");
    }
  }
    */
</style>
