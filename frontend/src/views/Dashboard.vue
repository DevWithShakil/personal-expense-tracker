<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar Component -->
    <AppNavbar>
      <template #actions>
        <button
          @click="openModal"
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
      <!-- HEADER & FILTER SECTION -->
      <div
        class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4"
      >
        <div>
          <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
          <p v-if="dashboardStats" class="text-sm text-gray-500 mt-1">
            Overview for
            <span class="font-semibold text-blue-600">{{
              dashboardStats.selected_month.name
            }}</span>
          </p>
        </div>

        <!-- Month Picker Input -->
        <div
          class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm border border-gray-200"
        >
          <label class="text-sm text-gray-500 font-medium pl-2">Filter:</label>
          <input
            type="month"
            v-model="filterDate"
            @change="handleFilterChange"
            class="border-none focus:ring-0 text-sm font-medium text-gray-700 bg-transparent cursor-pointer outline-none"
          />
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loadingStats" class="flex justify-center items-center h-64">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
        ></div>
      </div>

      <!-- Dashboard Content -->
      <div v-else-if="dashboardStats">
        <!-- TOP STATS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Total Balance (Always Overall) -->
          <div
            class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden md:col-span-1"
          >
            <div class="relative z-10">
              <p class="text-sm font-medium text-gray-500 mb-1">
                Total Balance (Overall)
              </p>
              <h3 class="text-3xl font-bold text-gray-800">
                {{ dashboardStats.balance.formatted }}
              </h3>
              <div class="mt-4 flex items-center text-sm">
                <span
                  class="px-2 py-1 rounded-full text-xs font-medium"
                  :class="
                    dashboardStats.balance.status === 'positive'
                      ? 'bg-green-100 text-green-700'
                      : 'bg-red-100 text-red-700'
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
              class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full z-0 opacity-50"
            ></div>
          </div>

          <!-- Income & Expense (Selected Month) -->
          <div class="md:col-span-2 grid grid-cols-2 gap-4">
            <!-- Income Card -->
            <div
              class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex flex-col justify-center"
            >
              <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-medium text-gray-500">Income</p>
                <div class="p-1.5 bg-green-50 rounded text-green-600">
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
              <h3 class="text-2xl font-bold text-green-600">
                {{ dashboardStats.selected_month.income_formatted }}
              </h3>
            </div>

            <!-- Expense Card -->
            <div
              class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex flex-col justify-center"
            >
              <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-medium text-gray-500">Expense</p>
                <div class="p-1.5 bg-red-50 rounded text-red-600">
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
              <h3 class="text-2xl font-bold text-red-600">
                {{ dashboardStats.selected_month.expense_formatted }}
              </h3>
            </div>
          </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
          <!-- Income Chart -->
          <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit"
          >
            <h3
              class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"
            >
              <span class="w-2 h-6 bg-green-500 rounded-full"></span>
              Income Breakdown
            </h3>
            <IncomeChart
              :raw-api-data="dashboardStats.income_chart_data || []"
            />
          </div>

          <!-- Expense Chart -->
          <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit"
          >
            <h3
              class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2"
            >
              <span class="w-2 h-6 bg-red-500 rounded-full"></span>
              Expense Breakdown
            </h3>
            <ExpenseChart
              :raw-api-data="dashboardStats.expense_chart_data || []"
            />
          </div>
        </div>

        <!-- TRANSACTIONS LIST SECTION -->
        <div
          class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-fit mb-8"
        >
          <div
            class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50"
          >
            <h3 class="text-lg font-bold text-gray-800">Transactions</h3>
            <span
              class="text-xs text-gray-500 bg-white border px-2 py-1 rounded shadow-sm"
            >
              Total: {{ pagination.total }}
            </span>
          </div>

          <!-- Table Loading -->
          <div v-if="loadingTransactions" class="p-12 flex justify-center">
            <div
              class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-400"
            ></div>
          </div>

          <div v-else>
            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead
                  class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold tracking-wider"
                >
                  <tr>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Description</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3 text-right">Amount</th>
                    <th class="px-6 py-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr
                    v-for="transaction in transactions"
                    :key="transaction.id"
                    class="hover:bg-gray-50 transition group"
                  >
                    <td
                      class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap font-medium"
                    >
                      {{ transaction.date_human }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">
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
                          ? 'text-green-600'
                          : 'text-red-600'
                      "
                    >
                      {{ transaction.category?.type === "income" ? "+" : "-" }}
                      {{ transaction.amount_formatted }}
                    </td>
                    <td class="px-6 py-4 text-center">
                      <button
                        @click="deleteTransaction(transaction.id)"
                        class="text-gray-400 hover:text-red-500 transition p-1 rounded-md hover:bg-red-50 opacity-0 group-hover:opacity-100"
                        title="Delete Transaction"
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
                    </td>
                  </tr>
                  <tr v-if="transactions.length === 0">
                    <td
                      colspan="5"
                      class="px-6 py-8 text-center text-gray-400 text-sm"
                    >
                      No transactions found for this month.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div
              v-if="pagination.last_page > 1"
              class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50"
            >
              <button
                @click="changePage(pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="px-3 py-1 border rounded text-sm text-gray-600 hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed bg-white"
              >
                Previous
              </button>
              <span class="text-xs text-gray-500 font-medium">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
              </span>
              <button
                @click="changePage(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="px-3 py-1 border rounded text-sm text-gray-600 hover:bg-white disabled:opacity-50 disabled:cursor-not-allowed bg-white"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- ADD TRANSACTION MODAL -->
    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          @click="closeModal"
        ></div>
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              Add New Transaction
            </h3>
            <form @submit.prevent="submitTransaction">
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Amount</label
                >
                <div class="relative rounded-md shadow-sm">
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                  >
                    <span class="text-gray-500 sm:text-sm">৳</span>
                  </div>
                  <input
                    type="number"
                    v-model="form.amount"
                    step="0.01"
                    required
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-3 sm:text-sm border-gray-300 rounded-md py-2 border"
                  />
                </div>
              </div>
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Date</label
                >
                <input
                  type="date"
                  v-model="form.transaction_date"
                  required
                  class="w-full border-gray-300 rounded-md border px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                />
              </div>
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Category</label
                >
                <select
                  v-model="form.category_id"
                  required
                  class="w-full border-gray-300 rounded-md border px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500"
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
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Description</label
                >
                <textarea
                  v-model="form.description"
                  rows="2"
                  class="w-full border-gray-300 rounded-md border px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
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
                  {{ submitting ? "Saving..." : "Save" }}
                </button>
                <button
                  type="button"
                  @click="closeModal"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:col-start-1 sm:text-sm"
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
import { ref, onMounted, reactive } from "vue";
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

// Date Filter State (YYYY-MM format)
const filterDate = ref(new Date().toISOString().slice(0, 7));

const form = reactive({
  amount: "",
  transaction_date: new Date().toISOString().substr(0, 10),
  category_id: "",
  description: "",
});

// Helper to get year/month from filterDate string
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
    // Send month/year params to transaction list too
    const response = await axios.get(
      `/transactions?page=${page}&year=${year}&month=${month}`
    );
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

// Called when user changes the month input
const handleFilterChange = () => {
  fetchStats();
  fetchTransactions(1); // Reset to page 1 on filter change
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

const openModal = () => {
  fetchCategories();
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
    await axios.post("/transactions", form);
    closeModal();
    // Refresh stats and list
    fetchStats();
    fetchTransactions(1);
  } catch (error) {
    alert("Error adding transaction.");
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
