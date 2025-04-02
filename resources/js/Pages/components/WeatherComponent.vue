<template>
     <div class="bg-gradient-to-weather space-y-4 sm:space-y-28 lg:space-y-4   border w-[230px] sm:w-[550px] lg:w-[230px] flex flex-col items-center  h-full sm:sm:h-[650px] lg:h-full  py-3 gap-2    rounded-lg cursor-pointer">
            <div class="flex items-center text-[10px]">
                <img src="/public/icons/localisation.svg" alt="localisation">
                <form @submit="applyCity" class="text-white">
                    <select name="city" id="city" class="bg-inherit z-30 text-center w-[125px] outline-none" @change="applyCity" v-model="form.city">
                        <option value="abidjan" :selected="form.city =='abidjan'" class="text-black">Abidjan, Cote d'Ivoire</option>
                        <option value="dakar" :selected="form.city =='dakar'" class="text-black">Dakar, Sénégal</option>
                    </select>
                </form>
            </div>
            <div class="flex items-center justify-center">
                <div class="w-[120px]">
                    <img :src="weather" alt="temps-icon" class="size-full object-cover">
                </div>
            </div>
            <div class="flex flex-col  items-center justify-center">
                <div class="flex  justify-between items-center">
                    <div class="w-auto">
                        <img src="/public/icons/temp.svg" alt=""><span class="size-full object-cover"></span>
                    </div>
                    <span class="text-3xl text-white">{{ weatherData.main.temp }}</span><span class="text-2xl text-white">°</span>
                </div>
                <span class="text-[12px] text-white">{{ weatherData.weather[0]['description'] }}</span>
            </div>
            <div class="flex justify-around gap-5 w-full">
                <div v-if="weatherData.weather[0].main == 'Rain'" class="flex flex-col justify-center items-center">
                    <div class="w-5">
                        <img src="/public/icons/precipitation.svg" alt="precipitation" class="size-full object-cover">
                    </div>
                    <span class="text-white text-[10px]">{{weatherData.rain["1h"]}}mm</span>
                    <span class="text-white text-[10px] text-opacity-80">Précipitations</span>
                </div>
                <div v-else class="flex flex-col justify-center items-center">
                    <div class="w-5">
                        <img src="/public/icons/humidity.svg" alt="precipitation" class="size-full object-cover">
                    </div>
                    <span class="text-white text-[10px]">{{ weatherData.main.humidity }} %</span>
                    <span class="text-white text-[10px] text-opacity-80">Humidité</span>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <div class="w-5">
                        <img src="/public/icons/ressenti.svg" alt="resenti" class="size-full object-cover">
                    </div>
                    <span class="text-white text-[10px]">{{ weatherData.main.feels_like }}°</span>
                    <span class="text-white text-[10px]  text-opacity-80">Ressenti</span>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <div class="w-5">
                        <img src="/public/icons/vitesse_vent.svg" alt="vitess du vent" class="size-full object-cover">
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
    city: props.city || "abidjan", // Defaults to "abidjan" if no city is provided
});

const weather = `/weather/icons/${props.weatherTime}.png`;

onMounted(() => {
    console.log("Component mounted, city:", props.city);
});

const applyCity = () => {
    form.get(route("home"), { preserveState: true });
};
</script>
