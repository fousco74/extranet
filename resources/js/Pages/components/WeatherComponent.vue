<template>
     <div class="bg-gradient-to-weather space-y-4  border w-[230px] flex flex-col items-center  h-full  py-3 gap-2    rounded-lg cursor-pointer">
            <div class="flex items-center text-[10px]">
                <img src="/public/icons/localisation.png" alt="localisation">
                <form @submit="applyCity" class="text-white">
                    <select name="city" id="city" class="bg-inherit z-30 text-center w-[125px] outline-none" @change="applyCity" v-model="form.city">
                        <option value="abidjan" :selected="form.city =='abidjan'" class="text-black">Abidjan, Cote d'Ivoire</option>
                        <option value="dakar" :selected="form.city =='dakar'" class="text-black">Dakar, Sénégal</option>
                    </select>
                </form>
            </div>
            <div class="flex items-center justify-center">
                <div class="w-[120px]">
                    <img :src="`/icons/${weatherTime}.png`" alt="temps-icon" class="size-full object-cover">
                </div>
            </div>
            <div class="flex flex-col  items-center justify-center">
                <div class="flex  justify-between items-center">
                    <div class="w-auto">
                        <img src="/public/icons/temp.png" alt=""><span class="size-full object-cover"></span>
                    </div>
                    <span class="text-3xl text-white">{{ weatherData.main.temp }}</span><span class="text-2xl text-white">°</span>
                </div>
                <span class="text-[12px] text-white">{{ weatherData.weather[0]['description'] }}</span>
            </div>
            <div class="flex justify-around gap-5 w-full">
                <div v-if="weatherData.weather[0].main == 'Rain'" class="flex flex-col justify-center items-center">
                    <div class="w-5">
                        <img src="/public/icons/precipitation.png" alt="precipitation" class="size-full object-cover">
                    </div>
                    <span class="text-white text-[10px]">{{weatherData.rain["1h"]}}mm</span>
                    <span class="text-white text-[10px] text-opacity-80">Précipitations</span>
                </div>
                <div v-else class="flex flex-col justify-center items-center">
                    <div class="w-5">
                        <img src="/public/icons/humidity.png" alt="precipitation" class="size-full object-cover">
                    </div>
                    <span class="text-white text-[10px]">{{ weatherData.main.humidity }} %</span>
                    <span class="text-white text-[10px] text-opacity-80">Humidité</span>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <div class="w-5">
                        <img src="/public/icons/ressenti.png" alt="resenti" class="size-full object-cover">
                    </div>
                    <span class="text-white text-[10px]">{{ weatherData.main.feels_like }}°</span>
                    <span class="text-white text-[10px]  text-opacity-80">Ressenti</span>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <div class="w-5">
                        <img src="/public/icons/vitesse_vent.png" alt="vitess du vent" class="size-full object-cover">
                    </div>
                    <span class="text-white text-[10px]">{{ weatherData.wind.speed }}km/h</span>
                    <span class="text-white text-[8px]  text-opacity-80">Vitesse du vent</span>
                </div>
            </div>
      </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';



const props = defineProps(["city", "weatherData", "weatherTime"]);



const form = useForm({
    city: "abidjan", // Par défaut, Abidjan est sélectionnée
});

onMounted(() => {
    form.city = props.city; // Définit Abidjan par défaut lors du montage
});


const applyCity = () => {
    form.get(route("home"), { preserveState: true });
};












</script>