<template>
  <div class="relative h-64 w-full">
    <Doughnut
      v-if="chartData.labels.length > 0"
      :data="chartData"
      :options="options"
    />

    <!-- Empty State -->
    <div
      v-else
      class="h-full flex flex-col items-center justify-center text-gray-400"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-12 w-12 mb-2 opacity-50"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      <span class="text-sm">No income this month</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { Doughnut } from "vue-chartjs";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
  rawApiData: {
    type: Array,
    default: () => [],
  },
});

const chartData = computed(() => {
  return {
    labels: props.rawApiData.map((item) => item.category_name),
    datasets: [
      {
        backgroundColor: props.rawApiData.map((item) => item.color),
        data: props.rawApiData.map((item) => item.amount),
        borderWidth: 0,
        hoverOffset: 4,
      },
    ],
  };
});

const options = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: "bottom",
      labels: { usePointStyle: true, padding: 20, font: { size: 11 } },
    },
  },
};
</script>
