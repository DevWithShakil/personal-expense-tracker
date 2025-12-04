<template>
  <div
    class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300"
  >
    <AppNavbar>
      <template #actions>
        <button
          @click="openModal()"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2 text-sm font-medium"
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
              d="M12 4v16m8-8H4"
            />
          </svg>
          New Transaction
        </button>
      </template>
    </AppNavbar>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <!-- HEADER -->
      <div
        class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4"
      >
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Dashboard
          </h2>
          <p
            v-if="dashboardStats"
            class="text-sm text-gray-500 dark:text-gray-400 mt-1"
          >
            Overview for
            <span class="font-semibold text-blue-600 dark:text-blue-400">{{
              dashboardStats.selected_month.name
            }}</span>
          </p>
        </div>

        <div class="flex items-center gap-3">
          <!-- Download PDF -->
          <button
            @click="downloadReport"
            :disabled="downloading"
            class="flex items-center gap-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium disabled:opacity-50 shadow-sm"
          >
            <svg
              v-if="!downloading"
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 text-gray-500 dark:text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
              />
            </svg>
            <div
              v-else
              class="animate-spin rounded-full h-4 w-4 border-2 border-gray-500 border-t-transparent"
            ></div>
            {{ downloading ? "Generating..." : "Download Report" }}
          </button>

          <!-- Month Picker -->
          <div
            class="flex items-center gap-2 bg-white dark:bg-gray-800 p-2 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600"
          >
            <label
              class="text-sm text-gray-500 dark:text-gray-400 font-medium pl-2"
              >Period:</label
            >
            <input
              type="month"
              v-model="filterDate"
              @change="handleFilterChange"
              class="border-none focus:ring-0 text-sm font-medium text-gray-700 dark:text-gray-200 bg-transparent cursor-pointer outline-none dark:[color-scheme:dark]"
            />
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loadingStats" class="flex justify-center items-center h-64">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
        ></div>
      </div>

      <div v-else-if="dashboardStats">
        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Total Balance -->
          <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 relative overflow-hidden md:col-span-1"
          >
            <div class="relative z-10">
              <p
                class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1"
              >
                Total Balance (Overall)
              </p>
              <h3 class="text-3xl font-bold text-gray-800 dark:text-white">
                {{ currencySymbol }} {{ dashboardStats.balance.total }}
              </h3>
              <div class="mt-4 flex items-center text-sm">
                <span
                  class="px-2 py-1 rounded-full text-xs font-medium"
                  :class="
                    dashboardStats.balance.status === 'positive'
                      ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                      : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'
                  "
                >
                  {{
                    dashboardStats.balance.status === "positive"
                      ? "Healthy"
                      : "Overdraft"
                  }}
                </span>
              </div>
            </div>
            <div
              class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full z-0 opacity-50"
            ></div>
          </div>

          <!-- Income & Expense Summary -->
          <div class="md:col-span-2 grid grid-cols-2 gap-4">
            <div
              class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 flex flex-col justify-center"
            >
              <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                  Income
                </p>
                <div
                  class="p-1.5 bg-green-50 dark:bg-green-900/30 rounded text-green-600 dark:text-green-400"
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
                      d="M7 11l5-5m0 0l5 5m-5-5v12"
                    />
                  </svg>
                </div>
              </div>
              <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">
                {{ currencySymbol }} {{ dashboardStats.selected_month.income }}
              </h3>
            </div>

            <div
              class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 flex flex-col justify-center"
            >
              <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                  Expense
                </p>
                <div
                  class="p-1.5 bg-red-50 dark:bg-red-900/30 rounded text-red-600 dark:text-red-400"
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
                      d="M17 13l-5 5m0 0l-5-5m5 5V6"
                    />
                  </svg>
                </div>
              </div>
              <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">
                {{ currencySymbol }} {{ dashboardStats.selected_month.expense }}
              </h3>
            </div>
          </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
          <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 h-fit"
          >
            <h3
              class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2"
            >
              <span class="w-2 h-6 bg-green-500 rounded-full"></span> Income
              Breakdown
            </h3>
            <IncomeChart
              :raw-api-data="dashboardStats.income_chart_data || []"
            />
          </div>

          <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 h-fit"
          >
            <h3
              class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2"
            >
              <span class="w-2 h-6 bg-red-500 rounded-full"></span> Expense
              Breakdown
            </h3>
            <ExpenseChart
              :raw-api-data="dashboardStats.expense_chart_data || []"
            />
          </div>
        </div>

        <!-- TRANSACTIONS LIST -->
        <div
          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-fit mb-8"
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-700/30"
          >
            <div class="flex items-center gap-2">
              <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                Transactions
              </h3>
              <span
                class="text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-600 border dark:border-gray-500 px-2 py-1 rounded shadow-sm"
              >
                Total: {{ pagination.total }}
              </span>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
              <div
                class="flex bg-white dark:bg-gray-700 rounded-lg p-1 border dark:border-gray-600 shadow-sm"
              >
                <button
                  v-for="type in ['all', 'income', 'expense']"
                  :key="type"
                  @click="handleTypeFilter(type)"
                  class="px-3 py-1.5 text-xs font-medium rounded-md capitalize transition-colors"
                  :class="
                    filterType === type
                      ? 'bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm font-bold'
                      : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
                  "
                >
                  {{ type }}
                </button>
              </div>

              <div class="relative">
                <input
                  type="text"
                  v-model="searchQuery"
                  @input="handleSearch"
                  placeholder="Search description..."
                  class="pl-9 pr-4 py-1.5 text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64 border shadow-sm dark:placeholder-gray-400"
                />
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    />
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <div v-if="loadingTransactions" class="p-12 flex justify-center">
            <div
              class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-400"
            ></div>
          </div>

          <div v-else>
            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead
                  class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs uppercase font-semibold tracking-wider"
                >
                  <tr>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Description</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3 text-right">Amount</th>
                    <th class="px-6 py-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                  <tr
                    v-for="transaction in transactions"
                    :key="transaction.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition group"
                  >
                    <td
                      class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap font-medium"
                    >
                      {{ transaction.date_human }}
                    </td>
                    <td
                      class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200"
                    >
                      {{ transaction.description || "No description" }}
                    </td>
                    <td class="px-6 py-4">
                      <span
                        class="px-3 py-1 rounded-full text-xs font-medium flex items-center w-fit gap-1 border border-transparent"
                        :style="{
                          backgroundColor:
                            (transaction.category?.color || '#ccc') + '15',
                          color: transaction.category?.color || '#666',
                        }"
                      >
                        {{ transaction.category?.name || "Uncategorized" }}
                      </span>
                    </td>
                    <td
                      class="px-6 py-4 text-sm font-bold text-right whitespace-nowrap"
                      :class="
                        transaction.category?.type === 'income'
                          ? 'text-green-600 dark:text-green-400'
                          : 'text-red-600 dark:text-red-400'
                      "
                    >
                      {{ transaction.category?.type === "income" ? "+" : "-" }}
                      {{ currencySymbol }} {{ transaction.amount }}
                    </td>
                    <td class="px-6 py-4 text-center">
                      <div
                        class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity"
                      >
                        <button
                          @click="openModal(transaction)"
                          class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition p-1 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20"
                          title="Edit"
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
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                            />
                          </svg>
                        </button>
                        <button
                          @click="deleteTransaction(transaction.id)"
                          class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20"
                          title="Delete"
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
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="transactions.length === 0">
                    <td
                      colspan="5"
                      class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 text-sm"
                    >
                      <div class="flex flex-col items-center">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-10 w-10 text-gray-300 dark:text-gray-600 mb-2"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                          />
                        </svg>
                        <p>No transactions found.</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div
              v-if="pagination.last_page > 1"
              class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-700/50"
            >
              <button
                @click="changePage(pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="px-3 py-1 border dark:border-gray-600 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 disabled:opacity-50 bg-white dark:bg-gray-800"
              >
                Previous
              </button>
              <span class="text-xs text-gray-500 dark:text-gray-400 font-medium"
                >Page {{ pagination.current_page }} of
                {{ pagination.last_page }}</span
              >
              <button
                @click="changePage(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="px-3 py-1 border dark:border-gray-600 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 disabled:opacity-50 bg-white dark:bg-gray-800"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- MODAL -->
    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          @click="closeModal"
        ></div>
        <div
          class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border dark:border-gray-700"
        >
          <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3
              class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4"
            >
              {{ isEditing ? "Edit Transaction" : "Add New Transaction" }}
            </h3>
            <form @submit.prevent="submitTransaction">
              <div class="mb-4">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Amount</label
                >
                <div class="relative rounded-md shadow-sm">
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                  >
                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{
                      currencySymbol
                    }}</span>
                  </div>
                  <input
                    type="number"
                    v-model="form.amount"
                    step="0.01"
                    required
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 rounded-md py-2 border bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                  />
                </div>
              </div>
              <div class="mb-4">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Date</label
                >
                <input
                  type="date"
                  v-model="form.transaction_date"
                  required
                  class="w-full border-gray-300 dark:border-gray-600 rounded-md border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white dark:[color-scheme:dark]"
                />
              </div>
              <div class="mb-4">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Category</label
                >
                <select
                  v-model="form.category_id"
                  required
                  class="w-full border-gray-300 dark:border-gray-600 rounded-md border px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="" disabled>Select a category</option>
                  <option
                    v-for="cat in categories"
                    :key="cat.id"
                    :value="cat.id"
                  >
                    {{ cat.name }} ({{ cat.type }})
                  </option>
                </select>
              </div>
              <div class="mb-4">
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Description</label
                >
                <textarea
                  v-model="form.description"
                  rows="2"
                  class="w-full border-gray-300 dark:border-gray-600 rounded-md border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                ></textarea>
              </div>
              <div
                class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense"
              >
                <button
                  type="submit"
                  :disabled="submitting"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:col-start-2 sm:text-sm disabled:opacity-50"
                >
                  {{ submitting ? "Saving..." : isEditing ? "Update" : "Save" }}
                </button>
                <button
                  type="button"
                  @click="closeModal"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:col-start-1 sm:text-sm"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from "vue";
import { useAuthStore } from "../stores/auth";
import axios from "../axios";
import AppNavbar from "../components/AppNavbar.vue";
import ExpenseChart from "../components/charts/ExpenseChart.vue";
import IncomeChart from "../components/charts/IncomeChart.vue";

const authStore = useAuthStore();
const dashboardStats = ref(null);
const transactions = ref([]);
const pagination = ref({ current_page: 1, last_page: 1, total: 0 });
const loadingStats = ref(true);
const loadingTransactions = ref(true);
const categories = ref([]);
const showModal = ref(false);
const submitting = ref(false);
const downloading = ref(false);

const isEditing = ref(false);
const editId = ref(null);

const filterDate = ref(new Date().toISOString().slice(0, 7));
const searchQuery = ref("");
const filterType = ref("all");
let searchTimeout = null;

const form = reactive({
  amount: "",
  transaction_date: new Date().toISOString().substr(0, 10),
  category_id: "",
  description: "",
});

const currencySymbol = computed(() => {
  const currency = authStore.user?.currency || "BDT";
  switch (currency) {
    case "USD":
      return "$";
    case "EUR":
      return "€";
    case "BDT":
      return "৳";
    default:
      return "৳";
  }
});

const getFilterParams = () => {
  const [year, month] = filterDate.value.split("-");
  return { year, month };
};

const fetchStats = async () => {
  loadingStats.value = true;
  try {
    const params = getFilterParams();
    const response = await axios.get("/dashboard", { params });
    dashboardStats.value = response.data;
  } catch (error) {
    console.error("Stats Error:", error);
  } finally {
    loadingStats.value = false;
  }
};

const fetchTransactions = async (page = 1) => {
  loadingTransactions.value = true;
  try {
    const { year, month } = getFilterParams();
    const params = {
      page,
      year,
      month,
      type: filterType.value,
      search: searchQuery.value,
    };

    const response = await axios.get(`/transactions`, { params });
    transactions.value = response.data.data || [];

    if (response.data.meta) {
      pagination.value = {
        current_page: response.data.meta.current_page,
        last_page: response.data.meta.last_page,
        total: response.data.meta.total,
      };
    } else {
      pagination.value = {
        current_page: 1,
        last_page: 1,
        total: transactions.value.length,
      };
    }
  } catch (error) {
    console.error("Transactions Error:", error);
  } finally {
    loadingTransactions.value = false;
  }
};

const downloadReport = async () => {
  downloading.value = true;
  try {
    const { year, month } = getFilterParams();
    const response = await axios.get("/export-pdf", {
      params: { year, month },
      responseType: "blob",
    });
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    const filename = `Report-${filterDate.value}.pdf`;
    link.setAttribute("download", filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    alert("Failed to download PDF.");
    console.error(error);
  } finally {
    downloading.value = false;
  }
};

const handleSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchTransactions(1);
  }, 500);
};

const handleTypeFilter = (type) => {
  filterType.value = type;
  fetchTransactions(1);
};

const handleFilterChange = () => {
  fetchStats();
  fetchTransactions(1);
};

const changePage = (page) => {
  if (page > 0 && page <= pagination.value.last_page) {
    fetchTransactions(page);
  }
};

const fetchCategories = async () => {
  try {
    const response = await axios.get("/categories");
    categories.value = response.data.data;
  } catch (error) {
    console.error("Categories Error:", error);
  }
};

const openModal = (transaction = null) => {
  fetchCategories();

  if (transaction) {
    isEditing.value = true;
    editId.value = transaction.id;
    form.amount = transaction.amount;
    form.transaction_date = transaction.date;
    form.category_id = transaction.category ? transaction.category.id : "";
    form.description = transaction.description;
  } else {
    isEditing.value = false;
    editId.value = null;
    resetForm();
  }

  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  resetForm();
};

const resetForm = () => {
  form.amount = "";
  form.description = "";
  form.category_id = "";
  form.transaction_date = new Date().toISOString().substr(0, 10);
};

const submitTransaction = async () => {
  submitting.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/transactions/${editId.value}`, form);
    } else {
      await axios.post("/transactions", form);
    }
    closeModal();
    fetchStats();
    fetchTransactions(isEditing.value ? pagination.value.current_page : 1);
  } catch (error) {
    alert("Error saving transaction.");
  } finally {
    submitting.value = false;
  }
};

const deleteTransaction = async (id) => {
  if (!confirm("Are you sure?")) return;
  try {
    await axios.delete(`/transactions/${id}`);
    fetchStats();
    fetchTransactions(pagination.value.current_page);
  } catch (error) {
    alert("Failed to delete.");
  }
};

onMounted(() => {
  fetchStats();
  fetchTransactions();
});
</script>
