import axios from "axios";

const baseURL = import.meta.env.VITE_API_BASE_URL || "https://personal-expense-tracker-uu5h.onrender.com/api";

axios.defaults.baseURL = baseURL;

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default axios;