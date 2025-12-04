<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center gap-2">
            <!-- Icon -->
            <div class="bg-blue-600 p-2 rounded-lg text-white">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
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
            </div>
            <h1 class="text-xl font-bold text-gray-800">Expense Tracker</h1>
          </div>
          <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
              <p class="text-sm text-gray-500">Logged in as</p>
              <p class="font-medium text-gray-800">
                {{ authStore.user?.name }}
              </p>
            </div>
            <button
              @click="handleLogout"
              class="bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition duration-300 font-medium text-sm flex items-center gap-2"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
              </svg>
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
        ></div>
      </div>

      <!-- Data View -->
      <div v-else-if="dashboardData">
        <!-- Top Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Total Balance -->
          <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Balance</p>
            <h3 class="text-3xl font-bold text-gray-800">
              {{ dashboardData.balance.formatted }}
            </h3>
            <div class="mt-4 flex items-center text-sm">
              <span
                class="px-2 py-1 rounded-full text-xs font-medium"
                :class="
                  dashboardData.balance.status === 'positive'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'
                "
              >
                {{
                  dashboardData.balance.status === "positive"
                    ? "Healthy"
                    : "Overdraft"
                }}
              </span>
            </div>
          </div>

          <!-- Income (This Month) -->
          <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-500 mb-1">
              Income (This Month)
            </p>
            <h3 class="text-3xl font-bold text-green-600">
              {{ dashboardData.this_month.income_formatted }}
            </h3>
          </div>

          <!-- Expense (This Month) -->
          <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-500 mb-1">
              Expense (This Month)
            </p>
            <h3 class="text-3xl font-bold text-red-600">
              {{ dashboardData.this_month.expense_formatted }}
            </h3>
          </div>
        </div>

        <!-- Recent Transactions List -->
        <div
          class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
        >
          <div
            class="px-6 py-4 border-b border-gray-100 flex justify-between items-center"
          >
            <h3 class="text-lg font-bold text-gray-800">Recent Transactions</h3>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded"
              >Last 5 entries</span
            >
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                  <th class="px-6 py-3">Date</th>
                  <th class="px-6 py-3">Description</th>
                  <th class="px-6 py-3">Category</th>
                  <th class="px-6 py-3 text-right">Amount</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr
                  v-for="transaction in dashboardData.recent_transactions"
                  :key="transaction.id"
                  class="hover:bg-gray-50 transition"
                >
                  <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                    {{ transaction.date_human }}
                  </td>
                  <td class="px-6 py-4 text-sm font-medium text-gray-800">
                    {{ transaction.description || "No description" }}
                  </td>
                  <td class="px-6 py-4">
                    <span
                      class="px-3 py-1 rounded-full text-xs font-medium flex items-center w-fit gap-1"
                      :style="{
                        backgroundColor: transaction.category?.color + '20',
                        color: transaction.category?.color,
                      }"
                    >
                      {{ transaction.category?.name }}
                    </span>
                  </td>
                  <td
                    class="px-6 py-4 text-sm font-bold text-right whitespace-nowrap"
                    :class="
                      transaction.category?.type === 'income'
                        ? 'text-green-600'
                        : 'text-red-600'
                    "
                  >
                    {{ transaction.category?.type === "income" ? "+" : "-" }}
                    {{ transaction.amount_formatted }}
                  </td>
                </tr>

                <tr v-if="dashboardData.recent_transactions.length === 0">
                  <td
                    colspan="4"
                    class="px-6 py-8 text-center text-gray-400 text-sm"
                  >
                    No transactions found.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import axios from "../axios";

const authStore = useAuthStore();
const dashboardData = ref(null);
const loading = ref(true);

// data fetching function for dashboard
const fetchDashboardData = async () => {
  try {
    const response = await axios.get("/dashboard");
    dashboardData.value = response.data;
  } catch (error) {
    console.error("Error fetching dashboard data:", error);

    //  if token is expired, logout
    if (error.response && error.response.status === 401) {
      authStore.logout();
    }
  } finally {
    loading.value = false;
  }
};

const handleLogout = () => {
  authStore.logout();
};

// fetch data instantly on page load
onMounted(() => {
  fetchDashboardData();
});
</script>
