<template>
    <div class="space-x-6 mt-5">
        <Link v-for="link in paginator.links" :key="link.label" v-html="link.label" :href="link.url" :class="{'text-slate-300' : !link.url, 'text-blue_white' : link.active}">
        </Link>
      </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps(['paginator']);
</script>