<template>
  <nav
    class="sticky top-0 z-50 w-full border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg transition-all duration-300"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <!-- LEFT: Logo & Desktop Navigation -->
        <div class="flex items-center gap-8">
          <!-- Brand Logo -->
          <router-link to="/" class="flex items-center gap-2 group">
            <div
              class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30 transition-transform group-hover:scale-105 group-hover:rotate-3"
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
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </div>
            <span
              class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 hidden sm:block"
            >
              ExpenseTracker
            </span>
          </router-link>

          <!-- Desktop Menu Links -->
          <div class="hidden md:flex space-x-1">
            <router-link
              v-for="link in navLinks"
              :key="link.name"
              :to="link.to"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
              active-class="bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 font-semibold"
              :class="
                $route.path !== link.to
                  ? 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white'
                  : ''
              "
            >
              {{ link.name }}
            </router-link>
          </div>
        </div>

        <!-- RIGHT: Actions, Theme & Profile -->
        <div class="flex items-center gap-3 sm:gap-4">
          <!-- Action Slot (New Transaction Button) -->
          <div class="hidden sm:block">
            <slot name="actions"></slot>
          </div>

          <!-- Theme Toggle -->
          <button
            @click="toggleTheme"
            class="p-2.5 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20"
            title="Toggle Theme"
          >
            <svg
              v-if="isDark"
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"
              />
            </svg>
            <svg
              v-else
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
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
              />
            </svg>
          </button>

          <!-- Desktop Profile Dropdown -->
          <div class="hidden md:block relative" ref="dropdownRef">
            <button
              @click="isOpen = !isOpen"
              class="flex items-center gap-3 focus:outline-none p-1 pr-3 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition border border-transparent hover:border-gray-200 dark:hover:border-gray-700"
            >
              <img
                :src="avatarUrl"
                class="h-8 w-8 rounded-full object-cover ring-2 ring-white dark:ring-gray-700 shadow-sm"
                alt="Profile"
              />
              <div class="text-left hidden lg:block">
                <p
                  class="text-sm font-medium text-gray-700 dark:text-gray-200 leading-none"
                >
                  {{ authStore.user?.name }}
                </p>
              </div>
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
              enter-active-class="transition ease-out duration-200"
              enter-from-class="opacity-0 translate-y-1"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition ease-in duration-150"
              leave-from-class="opacity-100 translate-y-0"
              leave-to-class="opacity-0 translate-y-1"
            >
              <div
                v-if="isOpen"
                class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 py-2 z-50 border border-gray-100 dark:border-gray-700"
              >
                <div
                  class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 mb-1"
                >
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    Signed in as
                  </p>
                  <p
                    class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                  >
                    {{ authStore.user?.email }}
                  </p>
                </div>

                <router-link
                  to="/settings"
                  @click="isOpen = false"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 mr-3"
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
                  Account Settings
                </router-link>

                <button
                  @click="handleLogout"
                  class="w-full flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 mr-3"
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
                  Sign Out
                </button>
              </div>
            </transition>
          </div>

          <!-- Mobile Menu Toggle Button -->
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="md:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition"
          >
            <svg
              v-if="!isMobileMenuOpen"
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
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
            <svg
              v-else
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
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- MOBILE MENU (Slide Down) -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div
        v-if="isMobileMenuOpen"
        class="md:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xl"
      >
        <div class="px-4 pt-4 pb-6 space-y-3">
          <!-- Mobile Links -->
          <router-link
            v-for="link in navLinks"
            :key="link.name"
            :to="link.to"
            @click="isMobileMenuOpen = false"
            class="block px-4 py-3 rounded-lg text-base font-medium transition-all"
            active-class="bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400"
            :class="
              $route.path !== link.to
                ? 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'
                : ''
            "
          >
            {{ link.name }}
          </router-link>

          <!-- Mobile Action Button (Shows in menu on mobile) -->
          <div class="pt-2">
            <slot name="actions"></slot>
          </div>

          <!-- Mobile Profile Section -->
          <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
            <div class="flex items-center px-2">
              <img
                :src="avatarUrl"
                class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                alt=""
              />
              <div class="ml-3">
                <div
                  class="text-base font-medium text-gray-800 dark:text-white"
                >
                  {{ authStore.user?.name }}
                </div>
                <div
                  class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                  {{ authStore.user?.email }}
                </div>
              </div>
            </div>
            <div class="mt-4 space-y-2">
              <router-link
                to="/settings"
                @click="isMobileMenuOpen = false"
                class="block px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg"
              >
                Settings
              </router-link>
              <button
                @click="handleLogout"
                class="w-full text-left block px-4 py-2 text-base font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"
              >
                Sign out
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();
const isOpen = ref(false);
const isMobileMenuOpen = ref(false);
const dropdownRef = ref(null);
const isDark = ref(false);

const navLinks = [
  { name: "Dashboard", to: "/" },
  { name: "Categories", to: "/categories" },
];

const toggleDropdown = () => (isOpen.value = !isOpen.value);

const toggleTheme = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add("dark");
    localStorage.setItem("theme", "dark");
  } else {
    document.documentElement.classList.remove("dark");
    localStorage.setItem("theme", "light");
  }
};

onMounted(() => {
  document.addEventListener("click", closeDropdown);
  if (
    localStorage.getItem("theme") === "dark" ||
    (!("theme" in localStorage) &&
      window.matchMedia("(prefers-color-scheme: dark)").matches)
  ) {
    isDark.value = true;
    document.documentElement.classList.add("dark");
  }
});

const closeDropdown = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isOpen.value = false;
  }
};

onUnmounted(() => {
  document.removeEventListener("click", closeDropdown);
});

const avatarUrl = computed(() => {
  if (authStore.user?.avatar) {
    if (authStore.user.avatar.startsWith("http")) return authStore.user.avatar;
    return `http://127.0.0.1:8000/storage/${authStore.user.avatar}`;
  }
  return `https://ui-avatars.com/api/?name=${
    authStore.user?.name || "User"
  }&background=0D8ABC&color=fff`;
});

const handleLogout = () => {
  isOpen.value = false;
  isMobileMenuOpen.value = false;
  authStore.logout();
};
</script>
