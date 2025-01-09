<script setup lang="ts">
import ButtonComponent from './components/ButtonComponent.vue';
import InputComponent from './components/InputComponent.vue';
import logoUrl from '/public/icons/login.png';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: null,
    password: null
})
const submit = ()=>{
    form.post(route('authenticate'))
}


</script>

<template>
    <div class="w-screen rounded-sm flex  p-0 max-lg:flex-col ">
        <div class="w-1/2 h-screen left-login  px-0 max-lg:w-full max-lg:h-[10%]">
            <div class="p-0 m-0 flex flex-col justify-end h-full">
                <div class="space-x-8 text-white">
                    <div class="w-[247px] max-md:w-[100px]  max-lg:ml-6  max-lg:mt-6 object-contain">
                        <img src="/public/logos/AMOAMAN.png" alt="logo" class="size-full">
                    </div>
                    <div class="py-4">
                        <h2 class="font-semibold text-6xl max-lg:text-2xl">One Team Spirit.</h2>
                        <p class="font-semibold text-lg max-lg:text-[12px]">Audace. Confiance. Créativité. <br> Engagement. Excellence.</p>
                        <h6 class="font-semibold text-sm mt-10 mb-3 max-lg:text-[8px]">Amoaman & Associés</h6>
                    </div>
                </div>
            </div>

        </div>
        <div class="w-1/2 h-full flex justify-center items-center max-lg:w-full max-lg:h-[70%]">
           <form @submit.prevent="submit" class="w-[90%] mt-24 flex flex-col pb-4 items-center justify-center max-lg:mt-8">
                <div class="flex flex-col text-center justify-center items-center w-[300px] space-y-2">
                    <span class=" font-semibold text-purple">Connexion</span>
                    <div class="w-[100px] rounded-full max-md:w-[50px]">
                        <img src="/public/icons/profile.png" alt="profile" class="size-full">
                    </div>
                    <h3 class="font-thin text-[14px] text-opacity-60 text-nowrap max-lg:text-[8px]">Connectez-vous pour avoir accès aux données internes de l'entreprise <br><strong> AMOAMAN & ASSOCIES. </strong> </h3>
                </div>
                <InputComponent v-model="form.email" :errors="$page.props.errors.email" type="email" name="email" divClass="mt-10 mb-7" placeholder="xxxxx@amoaman.com" class="w-[250px]"></InputComponent>
                <InputComponent v-model="form.password"  type="password" name="password" placeholder="*********" class="w-[250px]"></InputComponent>
              

                <ButtonComponent content="connexion" customClass="w-[250px] mt-6 bg-gradient-to-custom text-white" :logoUrl="logoUrl" alt="login" />

                
                
            </form>
        </div>
    </div>
</template>
<style>
.left-login{
    background-image: url('/public/background/left-login.png');
    background-repeat: no-repeat;
    background-size: cover;
}
</style>