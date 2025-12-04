<template>
  <div
    class="min-h-screen bg-[#F3F4F6] dark:bg-[#0B1120] transition-colors duration-500 relative font-sans"
  >
    <!-- Premium Animated Background (Aurora Effect) -->
    <div
      class="fixed inset-0 w-full h-full overflow-hidden pointer-events-none"
    >
      <div
        class="absolute top-[-10%] left-[-10%] w-[20rem] md:w-[40rem] h-[20rem] md:h-[40rem] bg-purple-500/30 dark:bg-purple-900/20 rounded-full mix-blend-multiply filter blur-[60px] md:blur-[100px] animate-blob"
      ></div>
      <div
        class="absolute top-[-10%] right-[-10%] w-[20rem] md:w-[40rem] h-[20rem] md:h-[40rem] bg-cyan-500/30 dark:bg-cyan-900/20 rounded-full mix-blend-multiply filter blur-[60px] md:blur-[100px] animate-blob animation-delay-2000"
      ></div>
      <div
        class="absolute bottom-[-20%] left-[20%] w-[20rem] md:w-[40rem] h-[20rem] md:h-[40rem] bg-pink-500/30 dark:bg-pink-900/20 rounded-full mix-blend-multiply filter blur-[60px] md:blur-[100px] animate-blob animation-delay-4000"
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
            <span class="hidden sm:inline">New Transaction</span>
            <span class="sm:hidden">Add</span>
          </div>
        </button>
      </template>
    </AppNavbar>

    <main
      class="relative z-20 max-w-7xl mx-auto py-6 md:py-8 px-4 sm:px-6 lg:px-8"
    >
      <!-- HEADER SECTION -->
      <div
        class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-8 md:mb-10 gap-4 md:gap-6 animate-fade-in-down"
      >
        <div class="w-full lg:w-auto">
          <h2
            class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-purple-800 to-gray-900 dark:from-white dark:via-purple-200 dark:to-white tracking-tight"
          >
            Dashboard
          </h2>
          <div
            v-if="dashboardStats"
            class="flex items-center gap-2 mt-2 text-sm md:text-base"
          >
            <span class="text-gray-600 dark:text-gray-400 font-medium"
              >Overview:</span
            >
            <span
              class="text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-md border border-indigo-100 dark:border-indigo-800 text-xs md:text-sm"
            >
              {{ dashboardStats.selected_month.name }}
            </span>
          </div>
        </div>

        <div
          class="w-full lg:w-auto flex flex-wrap items-center gap-3 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl p-2 rounded-2xl border border-white/50 dark:border-gray-700/50 shadow-sm"
        >
          <!-- Download PDF -->
          <button
            @click="downloadReport"
            :disabled="downloading"
            class="flex-1 sm:flex-none justify-center flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
            :class="
              downloading
                ? 'bg-gray-100 text-gray-400'
                : 'bg-white/50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200'
            "
          >
            <div
              v-if="downloading"
              class="animate-spin rounded-full h-4 w-4 border-2 border-gray-500 border-t-transparent"
            ></div>
            <svg
              v-else
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
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
              />
            </svg>
            <span>PDF</span>
          </button>

          <div
            class="h-6 w-px bg-gray-300 dark:bg-gray-600 hidden sm:block"
          ></div>

          <!-- Month Picker -->
          <div class="flex-1 sm:flex-none relative group min-w-[140px]">
            <input
              type="month"
              v-model="filterDate"
              @change="handleFilterChange"
              class="w-full pl-3 pr-2 py-2 bg-transparent text-sm font-bold text-gray-700 dark:text-gray-200 outline-none cursor-pointer dark:[color-scheme:dark] hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors text-center sm:text-left"
            />
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div
        v-if="loadingStats"
        class="flex justify-center items-center h-64 md:h-96"
      >
        <div class="relative">
          <div
            class="w-16 h-16 md:w-20 md:h-20 border-purple-200 dark:border-purple-900 border-2 rounded-full"
          ></div>
          <div
            class="w-16 h-16 md:w-20 md:h-20 border-purple-600 dark:border-purple-400 border-t-2 animate-spin rounded-full absolute left-0 top-0"
          ></div>
        </div>
      </div>

      <!-- Dashboard Content -->
      <div v-else-if="dashboardStats" class="space-y-6 md:space-y-8">
        <!-- HERO BENTO GRID -->
        <div
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6"
        >
          <!-- Total Balance (Main Hero Card) -->
          <div
            class="relative overflow-hidden rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-8 text-white shadow-2xl shadow-purple-500/20 transition-all duration-300 hover:scale-[1.01] lg:col-span-1 md:col-span-2 animate-fade-in-up bg-[#1A1F37] border border-gray-800"
          >
            <div
              class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-gradient-to-br from-pink-500 to-orange-400 rounded-full blur-3xl opacity-30 animate-pulse"
            ></div>
            <div
              class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-gradient-to-tr from-blue-500 to-purple-500 rounded-full blur-3xl opacity-30"
            ></div>

            <div class="relative z-10 h-full flex flex-col justify-between">
              <div>
                <div class="flex items-center gap-2 mb-2">
                  <div
                    class="p-1.5 md:p-2 bg-white/10 rounded-lg backdrop-blur-sm"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-4 w-4 md:h-5 md:w-5 text-purple-300"
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
                  <p
                    class="text-gray-300 text-xs md:text-sm font-bold tracking-wide uppercase"
                  >
                    Total Balance
                  </p>
                </div>

                <h3
                  class="text-3xl md:text-4xl lg:text-5xl font-black tracking-tight mt-4 break-words leading-tight"
                >
                  <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-300"
                  >
                    {{ currencySymbol }}{{ dashboardStats.balance.total }}
                  </span>
                </h3>
              </div>

              <div class="mt-6 md:mt-8">
                <div
                  class="inline-flex items-center gap-2 px-3 py-1.5 md:px-4 md:py-2 bg-white/10 backdrop-blur-md rounded-xl border border-white/10"
                >
                  <div
                    class="w-2 h-2 rounded-full"
                    :class="
                      dashboardStats.balance.status === 'positive'
                        ? 'bg-green-400 shadow-[0_0_10px_rgba(74,222,128,0.5)]'
                        : 'bg-red-400 shadow-[0_0_10px_rgba(248,113,113,0.5)]'
                    "
                  ></div>
                  <span class="text-xs md:text-sm font-bold text-gray-100">
                    {{
                      dashboardStats.balance.status === "positive"
                        ? "Excellent Condition"
                        : "Needs Attention"
                    }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Income Card -->
          <div
            class="relative group overflow-hidden rounded-[1.5rem] md:rounded-[2rem] p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xl shadow-gray-200/50 dark:shadow-none transition-all duration-300 hover:-translate-y-1 animate-fade-in-up"
            style="animation-delay: 100ms"
          >
            <div
              class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-bl-[4rem] -mr-4 -mt-4 transition-transform group-hover:scale-110 duration-500"
            ></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between mb-4 md:mb-6">
                <div
                  class="p-2 md:p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-800"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 md:h-8 md:w-8 text-emerald-500"
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
                  class="px-2 py-1 md:px-3 md:py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] md:text-xs font-bold uppercase tracking-wider rounded-full border border-emerald-100 dark:border-emerald-800"
                  >Income</span
                >
              </div>
              <h3
                class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white break-words"
              >
                {{ currencySymbol }}{{ dashboardStats.selected_month.income }}
              </h3>
              <p
                class="text-gray-500 dark:text-gray-400 text-xs md:text-sm font-medium mt-2 flex items-center gap-1"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-3 w-3 md:h-4 md:w-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                This Month
              </p>
            </div>
          </div>

          <!-- Expense Card -->
          <div
            class="relative group overflow-hidden rounded-[1.5rem] md:rounded-[2rem] p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-xl shadow-gray-200/50 dark:shadow-none transition-all duration-300 hover:-translate-y-1 animate-fade-in-up"
            style="animation-delay: 200ms"
          >
            <div
              class="absolute top-0 right-0 w-32 h-32 bg-rose-500/10 rounded-bl-[4rem] -mr-4 -mt-4 transition-transform group-hover:scale-110 duration-500"
            ></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between mb-4 md:mb-6">
                <div
                  class="p-2 md:p-3 bg-rose-50 dark:bg-rose-900/20 rounded-2xl border border-rose-100 dark:border-rose-800"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 md:h-8 md:w-8 text-rose-500"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"
                    />
                  </svg>
                </div>
                <span
                  class="px-2 py-1 md:px-3 md:py-1 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-[10px] md:text-xs font-bold uppercase tracking-wider rounded-full border border-rose-100 dark:border-rose-800"
                  >Expense</span
                >
              </div>
              <h3
                class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white break-words"
              >
                {{ currencySymbol }}{{ dashboardStats.selected_month.expense }}
              </h3>
              <p
                class="text-gray-500 dark:text-gray-400 text-xs md:text-sm font-medium mt-2 flex items-center gap-1"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-3 w-3 md:h-4 md:w-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
                This Month
              </p>
            </div>
          </div>
        </div>

        <!-- CHARTS GRID -->
        <div
          class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 animate-fade-in-up"
          style="animation-delay: 300ms"
        >
          <!-- Income Chart -->
          <div
            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-100 dark:border-gray-700 rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-8 shadow-lg"
          >
            <div class="flex items-center gap-3 mb-6 md:mb-8">
              <div
                class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center"
              >
                <span class="text-lg md:text-xl">💰</span>
              </div>
              <h3
                class="text-lg md:text-xl font-black text-gray-800 dark:text-white"
              >
                Income Sources
              </h3>
            </div>
            <IncomeChart
              :raw-api-data="dashboardStats.income_chart_data || []"
            />
          </div>

          <!-- Expense Chart -->
          <div
            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-100 dark:border-gray-700 rounded-[1.5rem] md:rounded-[2rem] p-6 md:p-8 shadow-lg"
          >
            <div class="flex items-center gap-3 mb-6 md:mb-8">
              <div
                class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center"
              >
                <span class="text-lg md:text-xl">💸</span>
              </div>
              <h3
                class="text-lg md:text-xl font-black text-gray-800 dark:text-white"
              >
                Expense Structure
              </h3>
            </div>
            <ExpenseChart
              :raw-api-data="dashboardStats.expense_chart_data || []"
            />
          </div>
        </div>

        <!-- TRANSACTIONS LIST -->
        <div
          class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-gray-100 dark:border-gray-700 rounded-[1.5rem] md:rounded-[2.5rem] shadow-xl overflow-hidden animate-fade-in-up"
          style="animation-delay: 400ms"
        >
          <!-- Filter Toolbar -->
          <div
            class="p-5 md:p-8 border-b border-gray-100 dark:border-gray-700 flex flex-col lg:flex-row lg:items-center justify-between gap-4 md:gap-6 bg-white/50 dark:bg-gray-800/50"
          >
            <div
              class="flex items-center justify-between lg:justify-start gap-4"
            >
              <div class="flex items-center gap-3">
                <div
                  class="p-2 md:p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl text-blue-600 dark:text-blue-400"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 md:h-6 md:w-6"
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
                </div>
                <div>
                  <h3
                    class="text-lg md:text-xl font-black text-gray-900 dark:text-white"
                  >
                    Activity
                  </h3>
                  <p
                    class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400"
                  >
                    {{ pagination.total }} found
                  </p>
                </div>
              </div>

              <!-- Mobile Toggle (Filters) -->
              <button
                @click="showFilters = !showFilters"
                class="lg:hidden p-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-300"
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
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                  />
                </svg>
              </button>
            </div>

            <div
              class="flex flex-col sm:flex-row gap-4"
              :class="{ 'hidden lg:flex': !showFilters, flex: showFilters }"
            >
              <!-- Custom Tab Switcher -->
              <div
                class="bg-gray-100 dark:bg-gray-900 p-1.5 rounded-xl md:rounded-2xl flex shadow-inner w-full sm:w-auto overflow-x-auto"
              >
                <button
                  v-for="type in ['all', 'income', 'expense']"
                  :key="type"
                  @click="handleTypeFilter(type)"
                  class="flex-1 sm:flex-none px-4 md:px-5 py-2 md:py-2.5 text-xs md:text-sm font-bold rounded-lg md:rounded-xl capitalize transition-all duration-300 relative overflow-hidden whitespace-nowrap"
                  :class="
                    filterType === type
                      ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm ring-1 ring-black/5'
                      : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'
                  "
                >
                  {{ type }}
                </button>
              </div>

              <!-- Search Field -->
              <div class="relative group flex-1 sm:flex-none">
                <input
                  type="text"
                  v-model="searchQuery"
                  @input="handleSearch"
                  placeholder="Search..."
                  class="w-full sm:w-64 pl-10 md:pl-11 pr-4 py-2.5 md:py-3 text-sm bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 rounded-xl md:rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 dark:focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                />
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 text-gray-400 absolute left-3 md:left-4 top-1/2 -translate-y-1/2 group-focus-within:text-blue-500 transition-colors"
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

          <!-- Loading -->
          <div
            v-if="loadingTransactions"
            class="p-12 md:p-20 flex justify-center"
          >
            <div
              class="animate-spin rounded-full h-8 w-8 md:h-10 md:w-10 border-4 border-gray-200 border-t-blue-600"
            ></div>
          </div>

          <div v-else>
            <!-- DESKTOP TABLE View (Hidden on Mobile) -->
            <div class="hidden md:block overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead
                  class="bg-gray-50 dark:bg-gray-700/30 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                >
                  <tr>
                    <th class="px-8 py-5">Description</th>
                    <th class="px-8 py-5">Category</th>
                    <th class="px-8 py-5 text-right">Amount</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                  <tr
                    v-for="transaction in transactions"
                    :key="transaction.id"
                    class="group hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors duration-200"
                  >
                    <!-- Same Desktop Columns as before -->
                    <td class="px-8 py-5">
                      <div class="flex flex-col">
                        <span
                          class="text-base font-bold text-gray-800 dark:text-white mb-1"
                          >{{
                            transaction.description || "No description"
                          }}</span
                        >
                        <div
                          class="flex items-center gap-1.5 text-xs font-medium text-gray-500 dark:text-gray-400"
                        >
                          {{ transaction.date_human }}
                        </div>
                      </div>
                    </td>
                    <td class="px-8 py-5">
                      <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm"
                      >
                        <div
                          class="w-2.5 h-2.5 rounded-full"
                          :style="{
                            backgroundColor:
                              transaction.category?.color || '#ccc',
                          }"
                        ></div>
                        <span
                          class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                        >
                          {{ transaction.category?.name || "General" }}
                        </span>
                      </div>
                    </td>
                    <td class="px-8 py-5 text-right">
                      <span
                        class="text-base font-black px-4 py-1.5 rounded-full inline-block min-w-[100px] text-center"
                        :class="
                          transaction.category?.type === 'income'
                            ? 'bg-emerald-100/50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                            : 'bg-rose-100/50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400'
                        "
                      >
                        {{
                          transaction.category?.type === "income" ? "+" : "-"
                        }}
                        {{ currencySymbol }}{{ transaction.amount }}
                      </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                      <div
                        class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-200"
                      >
                        <button
                          @click="openModal(transaction)"
                          class="p-2.5 rounded-xl text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition"
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
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                            />
                          </svg>
                        </button>
                        <button
                          @click="deleteTransaction(transaction.id)"
                          class="p-2.5 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition"
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
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- MOBILE LIST View (Card Style) -->
            <div class="md:hidden space-y-3 p-4">
              <div
                v-for="transaction in transactions"
                :key="transaction.id"
                class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden"
              >
                <div
                  class="absolute top-0 left-0 w-1 h-full"
                  :class="
                    transaction.category?.type === 'income'
                      ? 'bg-emerald-500'
                      : 'bg-rose-500'
                  "
                ></div>

                <div class="flex justify-between items-start mb-3 pl-3">
                  <div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-lg">
                      {{ transaction.description || "No description" }}
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                      {{ transaction.date_human }}
                    </p>
                  </div>
                  <span
                    class="font-black text-lg"
                    :class="
                      transaction.category?.type === 'income'
                        ? 'text-emerald-600 dark:text-emerald-400'
                        : 'text-rose-600 dark:text-rose-400'
                    "
                  >
                    {{ transaction.category?.type === "income" ? "+" : "-" }}
                    {{ currencySymbol }}{{ transaction.amount }}
                  </span>
                </div>

                <div class="flex justify-between items-center pl-3">
                  <div
                    class="inline-flex items-center gap-2 px-2 py-1 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700"
                  >
                    <div
                      class="w-2 h-2 rounded-full"
                      :style="{
                        backgroundColor: transaction.category?.color || '#ccc',
                      }"
                    ></div>
                    <span
                      class="text-xs font-semibold text-gray-600 dark:text-gray-300"
                      >{{ transaction.category?.name || "General" }}</span
                    >
                  </div>

                  <div class="flex gap-1">
                    <button
                      @click="openModal(transaction)"
                      class="p-2 text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:text-blue-600"
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
                      class="p-2 text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:text-rose-600"
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
              </div>
            </div>

            <!-- Empty State -->
            <div v-if="transactions.length === 0" class="p-12 text-center">
              <div class="flex flex-col items-center">
                <div
                  class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                  </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400">
                  No transactions found
                </p>
              </div>
            </div>

            <!-- Pagination -->
            <div
              v-if="pagination.last_page > 1"
              class="px-6 py-4 md:px-8 md:py-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50"
            >
              <button
                @click="changePage(pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="flex items-center gap-1 px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg disabled:opacity-50"
              >
                Previous
              </button>
              <span
                class="text-xs md:text-sm text-gray-600 dark:text-gray-400 font-medium"
              >
                Page {{ pagination.current_page }} / {{ pagination.last_page }}
              </span>
              <button
                @click="changePage(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="flex items-center gap-1 px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg disabled:opacity-50"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- MODAL (Same as before but with responsive tweaks) -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <div
          class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
          @click="closeModal"
        ></div>

        <div
          class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/20 dark:border-gray-700 relative"
        >
          <div
            class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-blue-500/20 to-purple-500/20 dark:from-blue-900/40 dark:to-purple-900/40 pointer-events-none"
          ></div>

          <div class="px-6 md:px-8 pt-8 md:pt-10 pb-6 md:pb-8 relative z-10">
            <h3
              class="text-xl md:text-2xl font-black text-gray-900 dark:text-white mb-1"
            >
              {{ isEditing ? "Edit Transaction" : "New Transaction" }}
            </h3>
            <p
              class="text-gray-500 dark:text-gray-400 text-xs md:text-sm mb-6 md:mb-8"
            >
              Fill in the details below.
            </p>

            <form
              @submit.prevent="submitTransaction"
              class="space-y-4 md:space-y-6"
            >
              <!-- Input Fields (Same Logic) -->
              <div>
                <label
                  class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                  >Amount</label
                >
                <div class="relative rounded-2xl shadow-sm group">
                  <div
                    class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none"
                  >
                    <span
                      class="text-gray-400 dark:text-gray-500 font-bold text-xl group-focus-within:text-blue-500 transition-colors"
                      >{{ currencySymbol }}</span
                    >
                  </div>
                  <input
                    type="number"
                    v-model="form.amount"
                    step="0.01"
                    required
                    class="block w-full pl-12 pr-5 py-3 md:py-4 text-xl md:text-2xl font-black border-2 border-gray-100 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-300 focus:ring-0 focus:border-blue-500 transition-all"
                    placeholder="0.00"
                  />
                </div>
              </div>

              <!-- Other inputs same as desktop, just ensure touch targets are good -->
              <div class="grid grid-cols-2 gap-4 md:gap-5">
                <div>
                  <label
                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                    >Date</label
                  >
                  <input
                    type="date"
                    v-model="form.transaction_date"
                    required
                    class="w-full border-2 border-gray-100 dark:border-gray-700 rounded-2xl px-3 md:px-4 py-2.5 md:py-3 focus:ring-0 focus:border-blue-500 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-semibold dark:[color-scheme:dark] text-sm md:text-base"
                  />
                </div>
                <div>
                  <label
                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                    >Category</label
                  >
                  <select
                    v-model="form.category_id"
                    required
                    class="w-full border-2 border-gray-100 dark:border-gray-700 rounded-2xl px-3 md:px-4 py-2.5 md:py-3 focus:ring-0 focus:border-blue-500 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-semibold appearance-none text-sm md:text-base"
                  >
                    <option value="" disabled>Select...</option>
                    <option
                      v-for="cat in categories"
                      :key="cat.id"
                      :value="cat.id"
                    >
                      {{ cat.name }} ({{ cat.type }})
                    </option>
                  </select>
                </div>
              </div>

              <div>
                <label
                  class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                  >Description</label
                >
                <textarea
                  v-model="form.description"
                  rows="3"
                  class="w-full border-2 border-gray-100 dark:border-gray-700 rounded-2xl px-4 md:px-5 py-3 md:py-4 focus:ring-0 focus:border-blue-500 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-medium placeholder-gray-400 text-sm md:text-base resize-none"
                  placeholder="e.g. Grocery shopping"
                ></textarea>
              </div>

              <div class="pt-4 flex justify-end gap-3">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-5 md:px-6 py-3 md:py-3.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 font-bold border border-gray-200 dark:border-gray-700 text-sm md:text-base"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="submitting"
                  class="flex-1 sm:flex-none px-6 md:px-8 py-3 md:py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-bold disabled:opacity-70 text-sm md:text-base"
                >
                  {{ submitting ? "Saving..." : isEditing ? "Update" : "Save" }}
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
// Script logic remains exactly same as before
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
const showFilters = ref(false); // Added for mobile filter toggle

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
