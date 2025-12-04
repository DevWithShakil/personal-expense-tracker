<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 transition-colors duration-300"
  >
    <div
      class="max-w-md w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700"
    >
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
          Create Account
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Start tracking your expenses today
        </p>
      </div>

      <form @submit.prevent="handleRegister">
        <!-- Avatar Upload Section -->
        <div class="mb-6 flex justify-center">
          <div class="relative group">
            <img
              :src="avatarPreview"
              class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md bg-gray-100 dark:bg-gray-700"
              alt="Profile Avatar"
            />
            <!-- Upload Icon Overlay -->
            <label
              class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700 shadow-sm transition-transform transform group-hover:scale-110"
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
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
              <input
                type="file"
                class="hidden"
                accept="image/*"
                @change="handleFileChange"
              />
            </label>
          </div>
          <p class="text-xs text-gray-400 dark:text-gray-500 absolute mt-28">
            Optional
          </p>
        </div>

        <div class="space-y-4">
          <!-- Name -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Full Name</label
            >
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              placeholder="John Doe"
            />
          </div>

          <!-- Email -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Email Address</label
            >
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              placeholder="john@example.com"
            />
          </div>

          <!-- Currency Dropdown -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Preferred Currency</label
            >
            <div class="relative">
              <select
                v-model="form.currency"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white appearance-none"
              >
                <option value="BDT">BDT (৳)</option>
                <option value="USD">USD ($)</option>
                <option value="EUR">EUR (€)</option>
              </select>
              <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 dark:text-gray-400"
              >
                <svg
                  class="h-4 w-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </div>
            </div>
          </div>

          <!-- Password -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Password</label
            >
            <input
              v-model="form.password"
              type="password"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              placeholder="••••••••"
            />
          </div>

          <!-- Confirm Password -->
          <div>
            <label
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >Confirm Password</label
            >
            <input
              v-model="form.password_confirmation"
              type="password"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              placeholder="••••••••"
            />
          </div>
        </div>

        <div class="mt-8">
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition duration-300 flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed shadow-md"
          >
            <span
              v-if="loading"
              class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"
            ></span>
            {{ loading ? "Creating Account..." : "Sign Up" }}
          </button>
        </div>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Already have an account?
          <router-link
            to="/login"
            class="text-blue-600 dark:text-blue-400 font-medium hover:underline"
            >Log in</router-link
          >
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useAuthStore } from "../stores/auth";

const authStore = useAuthStore();
const loading = ref(false);
const avatarFile = ref(null);
const avatarPreview = ref(
  "https://ui-avatars.com/api/?name=New+User&background=random&color=fff"
);

const form = reactive({
  name: "",
  email: "",
  currency: "BDT", // Default currency set here
  password: "",
  password_confirmation: "",
});

// Handle File Selection and Preview
const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    avatarFile.value = file;
    // Create local preview URL
    const reader = new FileReader();
    reader.onload = (e) => {
      avatarPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const handleRegister = async () => {
  loading.value = true;
  try {
    // Create FormData object to send file + text data
    const formData = new FormData();
    formData.append("name", form.name);
    formData.append("email", form.email);
    formData.append("currency", form.currency);
    formData.append("password", form.password);
    formData.append("password_confirmation", form.password_confirmation);

    // Append avatar only if selected
    if (avatarFile.value) {
      formData.append("avatar", avatarFile.value);
    }

    // Call store action (Store handles the API call and redirect)
    await authStore.register(formData);
  } catch (error) {
    let msg = "Registration failed.";
    // Error handling logic
    if (error.response?.data?.message) msg = error.response.data.message;
    if (error.response?.data?.errors) {
      // Collect all validation errors
      msg = Object.values(error.response.data.errors).flat().join("\n");
    }
    alert(msg);
  } finally {
    loading.value = false;
  }
};
</script>
