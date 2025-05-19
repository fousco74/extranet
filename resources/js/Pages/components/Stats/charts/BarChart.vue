<script setup>
import { onMounted } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
  labels: Array,
  data: Array,
  colors: {
    type: Array,
    default: () => ['#10B981', '#F59E0B', '#3B82F6', '#EF4444']
  }
});

onMounted(() => {
  const ctx = document.getElementById('barChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: props.labels,
      datasets: [{
        label: 'Nombre de tâches',
        data: props.data,
        backgroundColor: props.colors,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false }
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
    <canvas id="barChart"></canvas>
  </div>
</template>
