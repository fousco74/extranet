<script setup>
defineProps({
    title: String,
    value: String,
    subvalue: String,
    icon: String,
    color: String,
    progress: Number
});
</script>

<template>
    <div class="bg-white rounded-xl  shadow-md overflow-hidden transition-all duration-300 hover:transform hover:-translate-y-1">
        <div class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-gray-500 text-sm font-medium">{{ title }}</div>
                    <div class="text-2xl font-bold mt-1">{{ value }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ subvalue }}</div>
                </div>
                <div :class="`bg-${color}-100 p-3 rounded-full text-${color}-600`">
                    <i class="fas" :class="`fa-${icon}`"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div
                      v-if="progress"
                        :class="`bg-${color}-600`"
                        class="h-2 rounded-full transition-all duration-500"
                        :style="`width: ${progress}%`"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
