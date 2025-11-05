import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        token:
            typeof window !== "undefined"
                ? localStorage.getItem("token")
                : null,
        user: null,
        loading: false,
    }),
    getters: {
        isAuthenticated: (state) => !!state.token && !!state.user,
    },
    actions: {
        setToken(token) {
            this.token = token;
            try {
                if (typeof window !== "undefined") {
                    localStorage.setItem("token", token);
                }
            } catch (e) {}
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        },
        clearToken() {
            this.token = null;
            this.user = null;
            try {
                if (typeof window !== "undefined") {
                    localStorage.removeItem("token");
                }
            } catch (e) {}
            delete axios.defaults.headers.common["Authorization"];
        },
        async fetchUser() {
            // tenta carregar o usuário da API usando o token atual
            if (!this.token) return null;
            this.loading = true;
            try {
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;
                const res = await axios.get("/api/user");
                this.user = res.data || null;
                return this.user;
            } catch (e) {
                // token inválido ou erro de rede
                this.clearToken();
                return null;
            } finally {
                this.loading = false;
            }
        },
        async loginWithToken(token) {
            this.setToken(token);
            return await this.fetchUser();
        },
        logout() {
            this.clearToken();
            // opcional: chamar endpoint de logout do backend, se disponível
            try {
                axios.post("/api/logout");
            } catch (e) {}
        },
    },
});

export default useAuthStore;
