<script setup>
defineProps({
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['danger', 'warning', 'info', 'success'].includes(value)
  },
  title: String,
  message: String,
  time: String
});

const colorClasses = {
  danger: { bg: 'bg-red-50', text: 'text-red-800', icon: 'exclamation-triangle' },
  warning: { bg: 'bg-yellow-50', text: 'text-yellow-800', icon: 'clock' },
  info: { bg: 'bg-blue-50', text: 'text-blue-800', icon: 'info-circle' },
  success: { bg: 'bg-green-50', text: 'text-green-800', icon: 'check-circle' }
};
</script>

<template>
  <div :class="`${colorClasses[type].bg} p-3 rounded-lg flex items-start`">
    <i class="fas" :class="`fa-${colorClasses[type].icon} mt-1 mr-3 ${colorClasses[type].text}`"></i>
    <div>
      <h3 class="text-sm font-medium" :class="colorClasses[type].text">{{ title }}</h3>
      <p class="text-sm mt-1" :class="colorClasses[type].text">{{ message }}</p>
      <div class="flex items-center mt-2 text-xs" :class="colorClasses[type].text">
        <span>{{ time }}</span>
      </div>
    </div>
  </div>
</template>
