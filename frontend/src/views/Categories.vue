<template>
  <div
    class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300"
  >
    <!-- Navbar Component -->
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
          Add Category
        </button>
      </template>
    </AppNavbar>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
        ></div>
      </div>

      <!-- Categories Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="category in categories"
          :key="category.id"
          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex justify-between items-center hover:shadow-md transition"
        >
          <div class="flex items-center gap-4">
            <!-- Color Box -->
            <div
              class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-sm"
              :style="{ backgroundColor: category.color }"
            >
              {{ category.name.charAt(0) }}
            </div>
            <div>
              <h3 class="font-bold text-gray-800 dark:text-white">
                {{ category.name }}
              </h3>
              <span
                class="text-xs px-2 py-1 rounded-full capitalize"
                :class="
                  category.type === 'income'
                    ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                    : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'
                "
              >
                {{ category.type }}
              </span>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-2">
            <button
              @click="openModal(category)"
              class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 p-2 bg-gray-50 dark:bg-gray-700 rounded-lg"
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
              @click="deleteCategory(category.id)"
              class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 p-2 bg-gray-50 dark:bg-gray-700 rounded-lg"
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

        <!-- Empty State -->
        <div
          v-if="categories.length === 0"
          class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400"
        >
          No categories found. Create your first one!
        </div>
      </div>
    </main>

    <!-- Modal Form -->
    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4">
        <div
          class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity"
          @click="closeModal"
        ></div>

        <div
          class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full relative z-10 border dark:border-gray-700"
        >
          <div class="px-6 py-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
              {{ isEditing ? "Edit Category" : "New Category" }}
            </h3>
          </div>

          <form @submit.prevent="submitCategory" class="p-6">
            <div class="space-y-4">
              <!-- Name -->
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Category Name</label
                >
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                />
              </div>

              <!-- Type -->
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Type</label
                >
                <div class="flex gap-4">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      v-model="form.type"
                      type="radio"
                      value="income"
                      class="text-blue-600 focus:ring-blue-500"
                    />
                    <span class="text-sm text-gray-700 dark:text-gray-300"
                      >Income</span
                    >
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      v-model="form.type"
                      type="radio"
                      value="expense"
                      class="text-red-600 focus:ring-red-500"
                    />
                    <span class="text-sm text-gray-700 dark:text-gray-300"
                      >Expense</span
                    >
                  </label>
                </div>
              </div>

              <!-- Color Picker -->
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Color Code</label
                >
                <div class="flex items-center gap-3">
                  <input
                    v-model="form.color_code"
                    type="color"
                    class="h-10 w-20 rounded p-1 border border-gray-300 dark:border-gray-600 cursor-pointer bg-white dark:bg-gray-700"
                  />
                  <span
                    class="text-sm text-gray-500 dark:text-gray-400 uppercase"
                    >{{ form.color_code }}</span
                  >
                </div>
              </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
              >
                {{ submitting ? "Saving..." : "Save Category" }}
              </button>
            </div>
          </form>
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
