import { defineStore } from "pinia";
import axios from "../axios";
import router from "../router";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: null,
        token: localStorage.getItem("token") || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        async login(formData) {
            try {
                const response = await axios.post("/login", formData);
                this.token = response.data.token;
                this.user = response.data.user;

                localStorage.setItem("token", this.token);
                localStorage.setItem("user", JSON.stringify(this.user));

                router.push("/");
            } catch (error) {
                console.error("Login Failed:", error.response.data);
                throw error;
            }
        },

        logout() {
            this.token = null;
            this.user = null;
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            router.push("/login");
        },
    },
});