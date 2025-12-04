import { defineStore } from "pinia";
import axios from "../axios";
import router from "../router";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: JSON.parse(localStorage.getItem("user")) || null,
        token: localStorage.getItem("token") || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        async fetchUser() {
            if (!this.token) return;
            try {
                const response = await axios.get("/user");
                this.user = response.data;
                localStorage.setItem("user", JSON.stringify(this.user));
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    this.logout();
                }
            }
        },

        async login(formData) {
            try {
                const response = await axios.post("/login", formData);
                this.setUserData(response.data);
                router.push("/");
            } catch (error) {
                throw error;
            }
        },
        async register(formData) {
            try {
                const response = await axios.post("/register", formData);
                this.setUserData(response.data);
                router.push("/");
            } catch (error) {
                throw error;
            }
        },

        async logout() {
            try {
                if (this.token) await axios.post("/logout");
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                delete axios.defaults.headers.common['Authorization'];
                router.push("/login");
            }
        },

        // Helper to set data
        setUserData(data) {
            this.token = data.access_token;
            this.user = data.user;
            localStorage.setItem("token", this.token);
            localStorage.setItem("user", JSON.stringify(this.user));
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        }
    },
});