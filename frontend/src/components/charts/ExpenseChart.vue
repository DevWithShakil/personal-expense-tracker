<template>
  <div class="relative h-64 w-full">
    <Doughnut
      v-if="chartData.labels.length > 0"
      :data="chartData"
      :options="options"
    />

    <!-- Empty State for Chart -->
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
          d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"
        />
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
        />
      </svg>
      <span class="text-sm">No expenses this month</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { Doughnut } from "vue-chartjs";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from "chart.js";

// Chart.js এর মডিউল রেজিস্টার করা
ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
  rawApiData: {
    type: Array,
    default: () => [],
  },
});

// API এর ডাটাকে Chart.js এর ফরম্যাটে কনভার্ট করা
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
      labels: {
        usePointStyle: true,
        padding: 20,
        font: {
          size: 11,
        },
      },
    },
  },
};
</script>
