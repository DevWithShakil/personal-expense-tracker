import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';
import Categories from '../views/Categories.vue';
import Settings from '../views/Settings.vue';
import { useAuthStore } from '../stores/auth';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                requiresAuth: false,
                title: 'Login - Expense Tracker'
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                requiresAuth: false,
                title: 'Create Account - Expense Tracker'
            }
        },
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            meta: {
                requiresAuth: true,
                title: 'Dashboard - Expense Tracker'
            }
        },
        {
            path: '/categories',
            name: 'categories',
            component: Categories,
            meta: {
                requiresAuth: true,
                title: 'Categories - Expense Tracker'
            }
        },
        {
            path: '/settings',
            name: 'settings',
            component: Settings,
            meta: {
                requiresAuth: true,
                title: 'Settings - Expense Tracker'
            }
        },
        {
            path: '/:pathMatch(.*)*',
            redirect: '/'
        }
    ]
});

// Navigation Guard & Title Update
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // 1. Update Document Title
    document.title = to.meta.title || 'Expense Tracker';

    // 2. Auth Checks
    if (to.meta.requiresAuth && !authStore.token) {
        next('/login');
    } else if ((to.name === 'login' || to.name === 'register') && authStore.token) {
        next('/');
    } else {
        next();
    }
});

export default router;