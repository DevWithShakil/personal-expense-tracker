<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-10">
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
            <h1 class="text-xl font-bold text-gray-800 hidden sm:block">
              Expense Tracker
            </h1>
          </div>
          <div class="flex items-center gap-4">
            <!-- New Transaction Button -->
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

            <div class="h-6 w-px bg-gray-300"></div>

            <div class="text-right hidden sm:block">
              <p class="text-xs text-gray-500">Logged in as</p>
              <p class="font-medium text-gray-800 text-sm">
                {{ authStore.user?.name }}
              </p>
            </div>
            <button
              @click="handleLogout"
              class="text-gray-500 hover:text-red-600 transition p-2"
              title="Logout"
            >
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
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
              </svg>
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
          <div
            class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden"
          >
            <div class="relative z-10">
              <p class="text-sm font-medium text-gray-500 mb-1">
                Total Balance
              </p>
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
            <!-- Decorative circle -->
            <div
              class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full z-0 opacity-50"
            ></div>
          </div>

          <!-- Income (This Month) -->
          <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-1">
              <p class="text-sm font-medium text-gray-500">
                Income (This Month)
              </p>
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
            <h3 class="text-3xl font-bold text-gray-800">
              {{ dashboardData.this_month.income_formatted }}
            </h3>
          </div>

          <!-- Expense (This Month) -->
          <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-1">
              <p class="text-sm font-medium text-gray-500">
                Expense (This Month)
              </p>
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
            <h3 class="text-3xl font-bold text-gray-800">
              {{ dashboardData.this_month.expense_formatted }}
            </h3>
          </div>
        </div>

        <!-- Recent Transactions List -->
        <div
          class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
        >
          <div
            class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50"
          >
            <h3 class="text-lg font-bold text-gray-800">Recent Transactions</h3>
            <span
              class="text-xs text-gray-500 bg-white border px-2 py-1 rounded shadow-sm"
              >Last 5 entries</span
            >
          </div>

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
                  v-for="transaction in dashboardData.recent_transactions"
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
                        backgroundColor: transaction.category?.color + '15',
                        color: transaction.category?.color,
                        borderColor: transaction.category?.color + '30',
                      }"
                    >
                      <i :class="`las la-${transaction.category?.icon}`"></i>
                      <!-- Assuming Line Awesome or similar, optional -->
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
                  <td class="px-6 py-4 text-center">
                    <button
                      @click="deleteTransaction(transaction.id)"
                      class="text-gray-400 hover:text-red-500 transition p-1 rounded-md hover:bg-red-50"
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
                  </td>
                </tr>

                <tr v-if="dashboardData.recent_transactions.length === 0">
                  <td
                    colspan="5"
                    class="px-6 py-12 text-center text-gray-400 text-sm flex flex-col items-center"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-10 w-10 mb-2 text-gray-300"
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
                    No transactions found yet. Start by adding one!
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <!-- ADD TRANSACTION MODAL -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <!-- Backdrop -->
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          @click="closeModal"
          aria-hidden="true"
        ></div>

        <span
          class="hidden sm:inline-block sm:align-middle sm:h-screen"
          aria-hidden="true"
          >&#8203;</span
        >

        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3
              class="text-lg leading-6 font-medium text-gray-900 mb-4"
              id="modal-title"
            >
              Add New Transaction
            </h3>

            <form @submit.prevent="submitTransaction">
              <!-- Amount -->
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
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md py-2 border"
                    placeholder="0.00"
                  />
                  <div
                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                  >
                    <span class="text-gray-500 sm:text-sm">BDT</span>
                  </div>
                </div>
              </div>

              <!-- Date -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Date</label
                >
                <input
                  type="date"
                  v-model="form.transaction_date"
                  required
                  class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 border px-3"
                />
              </div>

              <!-- Category -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Category</label
                >
                <select
                  v-model="form.category_id"
                  required
                  class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 border px-3 bg-white"
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
                <p
                  v-if="categories.length === 0"
                  class="text-xs text-red-500 mt-1"
                >
                  No categories found. Please create one in backend/postman
                  first.
                </p>
              </div>

              <!-- Description -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                  >Description</label
                >
                <textarea
                  v-model="form.description"
                  rows="2"
                  class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 border px-3"
                  placeholder="e.g. Weekly Groceries"
                ></textarea>
              </div>

              <!-- Buttons -->
              <div
                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse -mx-6 -mb-4 mt-6"
              >
                <button
                  type="submit"
                  :disabled="submitting"
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                >
                  {{ submitting ? "Saving..." : "Save Transaction" }}
                </button>
                <button
                  type="button"
                  @click="closeModal"
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
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

const authStore = useAuthStore();
const dashboardData = ref(null);
const loading = ref(true);
const categories = ref([]);
const showModal = ref(false);
const submitting = ref(false);

const form = reactive({
  amount: "",
  transaction_date: new Date().toISOString().substr(0, 10),
  category_id: "",
  description: "",
});

const fetchDashboardData = async () => {
  loading.value = true;
  try {
    const response = await axios.get("/dashboard");
    dashboardData.value = response.data;
  } catch (error) {
    console.error("Error fetching dashboard data:", error);
    if (error.response && error.response.status === 401) {
      authStore.logout();
    }
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const response = await axios.get("/categories");
    categories.value = response.data.data;
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
};

const submitTransaction = async () => {
  submitting.value = true;
  try {
    await axios.post("/transactions", form);

    closeModal();
    resetForm();
    await fetchDashboardData();
  } catch (error) {
    alert(
      "Error adding transaction: " +
        (error.response?.data?.message || error.message)
    );
  } finally {
    submitting.value = false;
  }
};

const deleteTransaction = async (id) => {
  if (!confirm("Are you sure you want to delete this transaction?")) return;

  try {
    await axios.delete(`/transactions/${id}`);
    if (dashboardData.value) {
      dashboardData.value.recent_transactions =
        dashboardData.value.recent_transactions.filter((t) => t.id !== id);
      fetchDashboardData();
    }
  } catch (error) {
    alert("Failed to delete transaction.");
  }
};

const openModal = () => {
  fetchCategories();
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const resetForm = () => {
  form.amount = "";
  form.description = "";
  form.category_id = "";
  form.transaction_date = new Date().toISOString().substr(0, 10);
};

const handleLogout = () => {
  authStore.logout();
};

onMounted(() => {
  fetchDashboardData();
});
</script>
