<script setup>
import { onMounted } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
  labels: Array,
  data: Array,
  colors: {
    type: Array,
    default: () => ['#6366F1', '#EC4899', '#F59E0B', '#10B981', '#9CA3AF']
  }
});

onMounted(() => {
  const ctx = document.getElementById('pieChart');
  new Chart(ctx, {
    type: 'pie', // <-- ici c'est le changement principal
    data: {
      labels: props.labels,
      datasets: [{
        data: props.data,
        backgroundColor: props.colors,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'right' }
      }
    }
  });
});
</script>

<template>
  <div class="chart-container">
    <canvas id="pieChart"></canvas>
  </div>
</template>
