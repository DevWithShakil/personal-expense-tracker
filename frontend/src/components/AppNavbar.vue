<template>
  <nav class="bg-white shadow-sm border-b sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo & Menu Links -->
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-2">
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

          <!-- Navigation Links -->
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

        <!-- Right Side User Info -->
        <div class="flex items-center gap-4">
          <!-- Page Specific Actions Slot -->
          <slot name="actions"></slot>

          <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>

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
</template>

<script setup>
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = () => {
  authStore.logout();
};
</script>
