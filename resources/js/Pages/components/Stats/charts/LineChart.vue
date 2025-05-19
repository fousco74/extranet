<script setup>
import { onMounted } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
  labels: Array,
  datasets: Array
});

onMounted(() => {
  const ctx = document.getElementById('lineChart');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: props.labels,
      datasets: props.datasets
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'top' }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
});
</script>

<template>
  <div class="chart-container">
    <canvas id="lineChart"></canvas>
  </div>
</template>
