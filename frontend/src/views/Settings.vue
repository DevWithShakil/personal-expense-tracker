<template>
  <div
    class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300"
  >
    <AppNavbar />

    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">
        Account Settings
      </h1>

      <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row"
      >
        <!-- Sidebar -->
        <div
          class="w-full md:w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 p-4"
        >
          <nav class="space-y-1">
            <button
              @click="activeTab = 'profile'"
              class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition flex items-center gap-3"
              :class="
                activeTab === 'profile'
                  ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                  : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
              "
            >
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
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                />
              </svg>
              Edit Profile
            </button>
            <button
              @click="activeTab = 'password'"
              class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition flex items-center gap-3"
              :class="
                activeTab === 'password'
                  ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                  : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
              "
            >
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
                  stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                />
              </svg>
              Change Password
            </button>
          </nav>
        </div>

        <!-- Content -->
        <div class="flex-1 p-8">
          <!-- TAB 1: PROFILE -->
          <div v-if="activeTab === 'profile'">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">
              Profile Information
            </h3>

            <form @submit.prevent="updateProfile">
              <!-- Avatar Upload -->
              <div class="mb-8 flex items-center gap-6">
                <div class="relative group">
                  <img
                    :src="avatarPreview"
                    class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md bg-gray-100 dark:bg-gray-700"
                    alt="Profile Avatar"
                  />
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
                <div>
                  <p
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Profile Picture
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    JPG, GIF or PNG. Max size 2MB.
                  </p>
                </div>
              </div>

              <!-- Input Fields -->
              <div class="grid grid-cols-1 gap-6">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Full Name</label
                  >
                  <input
                    v-model="profileForm.name"
                    type="text"
                    required
                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                  />
                </div>

                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Email Address</label
                  >
                  <input
                    v-model="profileForm.email"
                    type="email"
                    required
                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                  />
                </div>

                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Currency</label
                  >
                  <select
                    v-model="profileForm.currency"
                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                  >
                    <option value="BDT">BDT (৳)</option>
                    <option value="USD">USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                  </select>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    This will update currency symbol across the app.
                  </p>
                </div>
              </div>

              <div class="mt-8 flex justify-end">
                <button
                  type="submit"
                  :disabled="loading"
                  class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium disabled:opacity-50 flex items-center gap-2"
                >
                  <span
                    v-if="loading"
                    class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"
                  ></span>
                  {{ loading ? "Saving..." : "Save Changes" }}
                </button>
              </div>
            </form>
          </div>

          <!-- TAB 2: PASSWORD -->
          <div v-if="activeTab === 'password'">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">
              Change Password
            </h3>

            <form @submit.prevent="changePassword">
              <div class="space-y-5">
                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Current Password</label
                  >
                  <input
                    v-model="passwordForm.current_password"
                    type="password"
                    required
                    placeholder="Enter current password"
                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                  />
                </div>

                <div
                  class="pt-2 border-t border-gray-100 dark:border-gray-700"
                ></div>

                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >New Password</label
                  >
                  <input
                    v-model="passwordForm.new_password"
                    type="password"
                    required
                    placeholder="Enter new password (min 8 chars)"
                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                  />
                </div>

                <div>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Confirm New Password</label
                  >
                  <input
                    v-model="passwordForm.new_password_confirmation"
                    type="password"
                    required
                    placeholder="Re-enter new password"
                    class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                  />
                </div>
              </div>

              <div class="mt-8 flex justify-end">
                <button
                  type="submit"
                  :disabled="loading"
                  class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium disabled:opacity-50 flex items-center gap-2"
                >
                  <span
                    v-if="loading"
                    class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"
                  ></span>
                  {{ loading ? "Updating..." : "Update Password" }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import axios from "../axios";
import AppNavbar from "../components/AppNavbar.vue";

const authStore = useAuthStore();
const activeTab = ref("profile");
const loading = ref(false);
const avatarFile = ref(null);
const avatarPreview = ref(
  "https://ui-avatars.com/api/?name=User&background=random"
);

const profileForm = reactive({
  name: "",
  email: "",
  currency: "BDT",
});

const passwordForm = reactive({
  current_password: "",
  new_password: "",
  new_password_confirmation: "",
});

// Initialize Data
onMounted(() => {
  if (authStore.user) {
    profileForm.name = authStore.user.name;
    profileForm.email = authStore.user.email;

    //  Load currency from store
    profileForm.currency = authStore.user.currency || "BDT";

    // Avatar Logic
    if (authStore.user.avatar) {
      if (authStore.user.avatar.startsWith("http")) {
        avatarPreview.value = authStore.user.avatar;
      } else {
        avatarPreview.value = `http://127.0.0.1:8000/storage/${authStore.user.avatar}`;
      }
    } else {
      avatarPreview.value = `https://ui-avatars.com/api/?name=${authStore.user.name}&background=0D8ABC&color=fff`;
    }
  }
});

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    avatarFile.value = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      avatarPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const updateProfile = async () => {
  loading.value = true;
  try {
    const formData = new FormData();
    formData.append("name", profileForm.name);
    formData.append("email", profileForm.email);
    formData.append("currency", profileForm.currency);

    if (avatarFile.value) {
      formData.append("avatar", avatarFile.value);
    }

    const response = await axios.post("/profile/update", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    // Update Store & LocalStorage
    authStore.user = response.data.user;
    localStorage.setItem("user", JSON.stringify(response.data.user));

    alert("Profile updated successfully!");
  } catch (error) {
    console.error(error);
    alert(
      "Failed to update profile. " +
        (error.response?.data?.message || "Server Error")
    );
  } finally {
    loading.value = false;
  }
};

const changePassword = async () => {
  if (passwordForm.new_password !== passwordForm.new_password_confirmation) {
    alert("New passwords do not match!");
    return;
  }

  loading.value = true;
  try {
    await axios.post("/profile/change-password", passwordForm);
    alert("Password changed successfully!");
    passwordForm.current_password = "";
    passwordForm.new_password = "";
    passwordForm.new_password_confirmation = "";
  } catch (error) {
    console.error(error);
    alert("Error: " + (error.response?.data?.message || "Check inputs."));
  } finally {
    loading.value = false;
  }
};
</script>
