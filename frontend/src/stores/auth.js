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
                console.error("Failed to fetch user", error);
                if (error.response && error.response.status === 401) {
                    this.logout();
                }
            }
        },

        // 2. Login Action
        async login(formData) {
            try {
                const response = await axios.post("/login", formData);

                this.token = response.data.access_token;
                this.user = response.data.user;

                localStorage.setItem("token", this.token);
                localStorage.setItem("user", JSON.stringify(this.user));

                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

                router.push("/");
            } catch (error) {
                throw error;
            }
        },

        // 3. Logout Action
        async logout() {
            try {
                if (this.token) {
                    await axios.post("/logout");
                }
            } catch (error) {
                console.error("Logout error", error);
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem("token");
                localStorage.removeItem("user");

                delete axios.defaults.headers.common['Authorization'];

                router.push("/login");
            }
        },
    },
});