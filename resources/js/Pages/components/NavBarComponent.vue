<script setup>
import logoUrl from '/public/icons/lineLogoutBlack.png';
import InputComponent from './InputComponent.vue';
import ButtonComponent from './ButtonComponent.vue';
import { ref, watch } from 'vue';

import { Link, router, usePage } from '@inertiajs/vue3';


const emit = defineEmits(['toggleSidebar']);

const props = defineProps(['search', 'dashboard', 'routeName', 'id', 'urlPrev']);

const searchPasse = ref(props.search)

const toggleSidebar = () => {
  emit('toggleSidebar'); // Émet l'événement vers le parent
};


watch(searchPasse, throttle(
  (q)=>{

if(props.routeName){
  
  if(props.id){
    router.get(route(props.routeName,props.id), {search: q}, {preserveState: true});
  }else{
    router.get(route(props.routeName), {search: q}, {preserveState: true});
  }

}

}, 2000
))

const urlPrev = props.urlPrev
			
const back = () => {
  window.history.back(); // Native browser back navigation
}


const menubar = ref(false);
import notificationComponent from './NotificationComponent.vue';
import { throttle } from 'lodash';
const showNotify = ref(false)
</script>

<template>
  <nav  class="w-full flex items-center  gap-4 sm:gap-10 lg:pl-10 border p-4 lg:py-3 lg:pr-3 sm:p-0">
    
    <div v-if="$page.props.routeName !='home' && !$page.props.routePath.includes('admin') " @click="back" class="flex justify-center items-center">
      <span  class="iconify absolute text-3xl cursor-pointer" data-icon="mdi-arrow-left"></span>
    </div>

  <!-- Logo -->
  <div :class="{'hidden' : dashboard}" class="w-16   sm:w-60 ml-4 sm:ml-16 flex  justify-center items-center">
    <Link :href="$page.props.routePath.includes('admin') ? route('dashboard.analytics') : route('home')">
      <img src="/public/logos/amoaman.png" alt="Logo" class="size-16 object-cover sm:w-full">
    </Link>
  </div>

   <button
      @click="toggleSidebar"
      v-if="dashboard"
      class="p-2 text-gray-600 bg-white border rounded-md shadow-lg sm:hidden"
    >
      <span class="iconify text-2xl" data-icon="mdi-menu"></span>
    </button>
  <!-- Search Bar -->
  <div class="flex-grow">
    <InputComponent 
      type="search" 
      name="search"
      v-model="searchPasse" 
      placeholder="Rechercher" 
      inputClass="w-full max-w-[510px]"
    ></InputComponent>
  </div>

  <!-- Notification & Profile -->
  <div class="flex gap-4 items-center text-gray-600 relative">
    <!-- Notifications -->
    <div class="relative  inline" @click="showNotify = !showNotify">
      <span 
        v-if="$page.props.auth.user.unreadNotifications.length > 0"
        class="bg-red-400 text-white text-[10px] sm:text-[12px] px-1 py-0 absolute left-3 z-30 rounded-full"
      >
        {{ $page.props.auth.user.unreadNotifications.length }}
      </span>
      <span class="iconify text-2xl sm:text-3xl cursor-pointer" data-icon="mdi-bell"></span>
      <notificationComponent 
        v-if="showNotify" 
        :notifications="$page.props.auth.user.notifications"
      ></notificationComponent>
    </div>

    <!-- Profile -->
    <div class="inline-flex items-center">
      <div @click="menubar = !menubar" class="rounded-full cursor-pointer w-8 sm:w-[40px]">
        <img 
          :src="$page.props.auth.user.profile_link ? `/storage/${$page.props.auth.user.profile_link}` : '/icons/profile.png'" 
          alt="profile" 
          class="size object-cover rounded-full"
        >
      </div>
      <div   class="hidden sm:flex flex-col justify-center space-x-0 ml-2">
        <span class="text-[13px] text-nowrap text-blue font-sans">
          {{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}
        </span>
        <span class="text-[10px] text-nowrap">{{ $page.props.auth.user.poste }}</span>
      </div>
      <div 
        v-if="menubar" 
        class="bg-white w-56 text-t-color top-[80px] z-30 right-4 sm:right-[200px] absolute border-2 border-white font-normal shadow-2xl"
      >
        <ul class="px-5 space-y-1 py-2 border-t border-opacity-25 border-t-color">
          <li>
            <a :href="$page.props.routePath.includes('admin') ? '/admin/profile' : '/profile'" class="flex gap-3">
              <span class="iconify size-5" data-icon="mdi-account"></span>
              <span>My profile</span>
            </a>
          </li>
        </ul>
        <div class="border-t border-t-color border-opacity-25 px-5 py-2">
          <Link href="/logout" method="post" class="flex gap-3">
            <span class="iconify size-5" data-icon="mdi-power"></span>
            <span>Logout</span>
          </Link>
        </div>
      </div>
    </div>
  </div>

  <!-- Logout Button -->
  <Link :href="route('logout')" method="post" class="hidden sm:block">
    <ButtonComponent 
      content="Déconnexion" 
      customClass="bg-white mr-2 text-black text-[12px] justify-center px-2 py-2 border"  
      :logoUrl="logoUrl" 
      alt="logout" 
    />
  </Link>
</nav>


</template>