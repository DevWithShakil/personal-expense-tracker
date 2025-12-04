<template>
  <nav class="bg-white shadow-sm border-b sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo & Menu Links -->
        <div class="flex items-center gap-8">
          <router-link to="/" class="flex items-center gap-2">
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
          </router-link>

          <!-- Navigation Links (Middle) -->
          <div class="hidden md:flex space-x-4">
            <router-link
              to="/"
              class="px-3 py-2 rounded-md text-sm font-medium transition"
              :class="
                $route.path === '/'
                  ? 'bg-blue-50 text-blue-700'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
              "
            >
              Dashboard
            </router-link>
            <router-link
              to="/categories"
              class="px-3 py-2 rounded-md text-sm font-medium transition"
              :class="
                $route.path === '/categories'
                  ? 'bg-blue-50 text-blue-700'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
              "
            >
              Categories
            </router-link>
          </div>
        </div>

        <!-- Right Side: Action Slot & Profile Dropdown -->
        <div class="flex items-center gap-4">
          <!-- Page Specific Actions (New Transaction Button comes here) -->
          <slot name="actions"></slot>

          <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>

          <!-- Profile Dropdown -->
          <div class="relative" ref="dropdownRef">
            <button
              @click="toggleDropdown"
              class="flex items-center gap-2 focus:outline-none transition-opacity hover:opacity-80 p-1 rounded-full border border-transparent hover:border-gray-200"
            >
              <div class="text-right hidden sm:block mr-1">
                <p class="text-xs text-gray-500">Logged in as</p>
                <p class="font-medium text-gray-800 text-sm leading-tight">
                  {{ authStore.user?.name }}
                </p>
              </div>

              <!-- Avatar Image -->
              <img
                :src="avatarUrl"
                class="h-9 w-9 rounded-full object-cover border border-gray-200 shadow-sm bg-gray-50"
                alt="Profile"
              />

              <!-- Chevron Icon -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 text-gray-400 transition-transform duration-200"
                :class="isOpen ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="isOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50 origin-top-right"
              >
                <!-- Mobile User Info (Only visible on small screens) -->
                <div class="px-4 py-3 border-b border-gray-50 sm:hidden">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ authStore.user?.name }}
                  </p>
                  <p class="text-xs text-gray-500 truncate">
                    {{ authStore.user?.email }}
                  </p>
                </div>

                <!-- Settings Link -->
                <router-link
                  to="/settings"
                  @click="isOpen = false"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition flex items-center gap-2"
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
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                  </svg>
                  Settings
                </router-link>

                <!-- Logout Button -->
                <button
                  @click="handleLogout"
                  class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2"
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
            </transition>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();
const isOpen = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

// Close dropdown when clicking outside
const closeDropdown = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", closeDropdown);
});

onUnmounted(() => {
  document.removeEventListener("click", closeDropdown);
});

// Computed Avatar URL to handle both local and remote images
const avatarUrl = computed(() => {
  if (authStore.user?.avatar) {
    if (authStore.user.avatar.startsWith("http")) {
      return authStore.user.avatar;
    } else {
      // Ensure this matches your Laravel storage URL
      return `http://127.0.0.1:8000/storage/${authStore.user.avatar}`;
    }
  }
  // Default placeholder with initials
  return `https://ui-avatars.com/api/?name=${
    authStore.user?.name || "User"
  }&background=0D8ABC&color=fff`;
});

const handleLogout = () => {
  isOpen.value = false;
  authStore.logout();
};
</script>
