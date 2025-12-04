<template>
  <div
    class="min-h-screen bg-[#F3F4F6] dark:bg-[#0B1120] transition-colors duration-500 relative font-sans"
  >
    <!-- Premium Animated Background (Aurora Effect) -->
    <div
      class="fixed inset-0 w-full h-full overflow-hidden pointer-events-none"
    >
      <div
        class="absolute top-[10%] right-[10%] w-[30rem] h-[30rem] bg-purple-500/30 dark:bg-purple-900/20 rounded-full mix-blend-multiply filter blur-[80px] animate-blob"
      ></div>
      <div
        class="absolute bottom-[10%] left-[10%] w-[30rem] h-[30rem] bg-cyan-500/30 dark:bg-cyan-900/20 rounded-full mix-blend-multiply filter blur-[80px] animate-blob animation-delay-2000"
      ></div>
      <div
        class="absolute top-[40%] left-[40%] w-[30rem] h-[30rem] bg-pink-500/30 dark:bg-pink-900/20 rounded-full mix-blend-multiply filter blur-[80px] animate-blob animation-delay-4000"
      ></div>
    </div>

    <AppNavbar class="relative z-30">
      <template #actions>
        <button
          @click="openModal()"
          class="group relative inline-flex items-center justify-center px-4 md:px-6 py-2 md:py-2.5 text-sm font-bold text-white transition-all duration-200 bg-gray-900 font-pj rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 shadow-lg hover:shadow-xl"
        >
          <div
            class="absolute -inset-3 rounded-full bg-gradient-to-r from-pink-600 via-purple-600 to-blue-600 opacity-0 group-hover:opacity-100 transition duration-200 blur-lg"
          ></div>
          <div class="relative flex items-center gap-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2.5"
                d="M12 4v16m8-8H4"
              />
            </svg>
            <span class="hidden sm:inline">Add Category</span>
            <span class="sm:hidden">Add</span>
          </div>
        </button>
      </template>
    </AppNavbar>

    <main class="relative z-20 max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <!-- HEADER -->
      <div
        class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4 animate-fade-in-down"
      >
        <div>
          <h2
            class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-purple-800 to-gray-900 dark:from-white dark:via-purple-200 dark:to-white tracking-tight"
          >
            Categories
          </h2>
          <p class="text-gray-600 dark:text-gray-400 mt-2 font-medium">
            Manage your income and expense sources
          </p>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="relative">
          <div
            class="w-16 h-16 border-purple-200 dark:border-purple-900 border-2 rounded-full"
          ></div>
          <div
            class="w-16 h-16 border-purple-600 dark:border-purple-400 border-t-2 animate-spin rounded-full absolute left-0 top-0"
          ></div>
        </div>
      </div>

      <!-- Categories Grid -->
      <div
        v-else
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
      >
        <div
          v-for="(category, index) in categories"
          :key="category.id"
          class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/20 dark:border-gray-700 rounded-[2rem] p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl animate-fade-in-up"
          :style="{ animationDelay: `${index * 50}ms` }"
        >
          <div class="flex items-start justify-between mb-4">
            <!-- Icon/Color Box -->
            <div
              class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg transition-transform group-hover:scale-110 group-hover:rotate-3"
              :style="{
                backgroundColor: category.color,
                boxShadow: `0 10px 25px -5px ${category.color}80`,
              }"
            >
              {{ category.name.charAt(0) }}
            </div>

            <!-- Actions Dropdown/Buttons -->
            <div
              class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
            >
              <button
                @click.stop="openModal(category)"
                class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
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
                @click.stop="deleteCategory(category.id)"
                class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition-colors"
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
          </div>

          <div>
            <h3
              class="text-xl font-bold text-gray-900 dark:text-white mb-1 truncate"
            >
              {{ category.name }}
            </h3>
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide"
              :class="
                category.type === 'income'
                  ? 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20'
                  : 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20'
              "
            >
              {{ category.type }}
            </span>
          </div>
        </div>

        <!-- Add New Card (Empty State / Quick Add) -->
        <button
          @click="openModal()"
          class="group relative flex flex-col items-center justify-center h-full min-h-[180px] rounded-[2rem] border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-all duration-300 animate-fade-in-up"
          :style="{ animationDelay: `${categories.length * 50}ms` }"
        >
          <div
            class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6 text-gray-400 group-hover:text-blue-500 transition-colors"
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
          </div>
          <span
            class="font-bold text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400"
            >Create New</span
          >
        </button>

        <!-- Empty State (if no categories) -->
        <div
          v-if="categories.length === 0 && !loading"
          class="col-span-full py-20 text-center"
        >
          <div class="flex flex-col items-center justify-center">
            <div
              class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6 animate-bounce"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-10 w-10 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
              No Categories Yet
            </h3>
            <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-sm">
              Create categories to organize your income and expenses
              efficiently.
            </p>
            <button
              @click="openModal()"
              class="mt-6 text-blue-600 hover:underline font-bold"
            >
              Create your first category
            </button>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Form (Premium Glass Style) -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <div
        class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
      >
        <div
          class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
          @click="closeModal"
        ></div>

        <div
          class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/20 dark:border-gray-700 relative"
        >
          <!-- Modal Header Bg -->
          <div
            class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-blue-500/20 to-purple-500/20 dark:from-blue-900/40 dark:to-purple-900/40 pointer-events-none"
          ></div>

          <div class="px-6 md:px-8 pt-8 md:pt-10 pb-6 md:pb-8 relative z-10">
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-1">
              {{ isEditing ? "Edit Category" : "New Category" }}
            </h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
              Organize your finances.
            </p>

            <form @submit.prevent="submitCategory" class="space-y-6">
              <div class="space-y-4">
                <!-- Name -->
                <div>
                  <label
                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                    >Category Name</label
                  >
                  <input
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="e.g. Groceries"
                    class="w-full pl-4 pr-4 py-3 text-lg font-bold border-2 border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-300 focus:ring-0 focus:border-blue-500 transition-all"
                  />
                </div>

                <!-- Type -->
                <div>
                  <label
                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                    >Type</label
                  >
                  <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                      <input
                        type="radio"
                        v-model="form.type"
                        value="income"
                        class="peer sr-only"
                      />
                      <div
                        class="rounded-xl border-2 border-gray-100 dark:border-gray-700 p-3 text-center hover:bg-gray-50 dark:hover:bg-gray-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 peer-checked:text-emerald-600 transition-all"
                      >
                        <span class="font-bold">Income 💰</span>
                      </div>
                    </label>
                    <label class="cursor-pointer">
                      <input
                        type="radio"
                        v-model="form.type"
                        value="expense"
                        class="peer sr-only"
                      />
                      <div
                        class="rounded-xl border-2 border-gray-100 dark:border-gray-700 p-3 text-center hover:bg-gray-50 dark:hover:bg-gray-800 peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 peer-checked:text-rose-600 transition-all"
                      >
                        <span class="font-bold">Expense 💸</span>
                      </div>
                    </label>
                  </div>
                </div>

                <!-- Color Picker -->
                <div>
                  <label
                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                    >Color Tag</label
                  >
                  <div
                    class="flex items-center gap-4 p-3 border-2 border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-900"
                  >
                    <input
                      v-model="form.color_code"
                      type="color"
                      class="h-10 w-10 rounded-lg border-none cursor-pointer bg-transparent"
                    />
                    <div class="flex-1">
                      <p
                        class="text-sm font-bold text-gray-900 dark:text-white"
                      >
                        {{ form.color_code }}
                      </p>
                      <p class="text-xs text-gray-500">
                        Click color box to change
                      </p>
                    </div>
                    <div
                      class="w-10 h-10 rounded-lg shadow-sm"
                      :style="{ backgroundColor: form.color_code }"
                    ></div>
                  </div>
                </div>
              </div>

              <div class="pt-4 flex justify-end gap-3">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-6 py-3.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 font-bold transition-colors border border-gray-200 dark:border-gray-700"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="submitting"
                  class="flex-1 sm:flex-none px-8 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl hover:shadow-lg hover:shadow-gray-900/20 font-bold transition-all transform hover:-translate-y-0.5 disabled:opacity-70 disabled:transform-none"
                >
                  {{ submitting ? "Saving..." : "Save Category" }}
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
import axios from "../axios";
import AppNavbar from "../components/AppNavbar.vue";

const categories = ref([]);
const loading = ref(true);
const showModal = ref(false);
const submitting = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = reactive({
  name: "",
  type: "expense",
  color_code: "#3b82f6",
  icon: "tag",
});

const fetchCategories = async () => {
  loading.value = true;
  try {
    const response = await axios.get("/categories");
    categories.value = response.data.data;
  } catch (error) {
    console.error("Error fetching categories:", error);
  } finally {
    loading.value = false;
  }
};

const openModal = (category = null) => {
  if (category) {
    isEditing.value = true;
    editingId.value = category.id;
    form.name = category.name;
    form.type = category.type;
    form.color_code = category.color;
  } else {
    isEditing.value = false;
    editingId.value = null;
    resetForm();
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  resetForm();
};

const resetForm = () => {
  form.name = "";
  form.type = "expense";
  form.color_code = "#3b82f6";
};

const submitCategory = async () => {
  submitting.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/categories/${editingId.value}`, form);
    } else {
      await axios.post("/categories", form);
    }
    await fetchCategories();
    closeModal();
  } catch (error) {
    alert(
      "Error saving category: " +
        (error.response?.data?.message || error.message)
    );
  } finally {
    submitting.value = false;
  }
};

const deleteCategory = async (id) => {
  if (
    !confirm(
      "Are you sure? All transactions under this category might be affected!"
    )
  )
    return;

  try {
    await axios.delete(`/categories/${id}`);
    categories.value = categories.value.filter((c) => c.id !== id);
  } catch (error) {
    alert("Failed to delete category.");
  }
};

onMounted(() => {
  fetchCategories();
});
</script>

<style>
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
.animation-delay-4000 {
  animation-delay: 4s;
}
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out forwards;
  opacity: 0;
}
@keyframes fade-in-down {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-in-down {
  animation: fade-in-down 0.6s ease-out forwards;
}
</style>
