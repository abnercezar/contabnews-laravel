import { defineStore } from "pinia";
import axios from "axios";

export const useClassifiedsStore = defineStore("classifieds", {
    state: () => ({
        classifieds: [],
        loading: false,
    }),
    getters: {
        all: (state) => state.classifieds,
    },
    actions: {
        async fetchClassifieds() {
            this.loading = true;
            try {
                const res = await axios.get("/api/classifieds");
                let list = Array.isArray(res.data) ? res.data : [];
                // normalize created_at/time field
                list = list.map((c) => ({
                    ...c,
                    created_at: c?.created_at || c?.time || null,
                }));
                // sort by created_at desc
                list.sort((a, b) => {
                    const aTime = new Date(a.created_at || 0).getTime() || 0;
                    const bTime = new Date(b.created_at || 0).getTime() || 0;
                    return bTime - aTime;
                });
                this.classifieds = list;
            } catch (e) {
                this.classifieds = [];
            } finally {
                this.loading = false;
            }
        },
        async addClassified(payload) {
            const token = localStorage.getItem("token");
            const headers = token ? { Authorization: `Bearer ${token}` } : {};
            const res = await axios.post("/api/classifieds", payload, {
                headers,
            });
            if (res && res.data) {
                // add to local state
                this.classifieds.unshift(res.data);
            }
            return res;
        },
        async updateClassified(id, payload) {
            const token = localStorage.getItem("token");
            const headers = token ? { Authorization: `Bearer ${token}` } : {};
            const res = await axios.put(`/api/classifieds/${id}`, payload, {
                headers,
            });
            if (res && res.data) {
                const idx = this.classifieds.findIndex(
                    (c) => c.id === res.data.id
                );
                if (idx !== -1) this.classifieds.splice(idx, 1, res.data);
            }
            return res;
        },
        async deleteClassified(id) {
            const token = localStorage.getItem("token");
            const headers = token ? { Authorization: `Bearer ${token}` } : {};
            const res = await axios.delete(`/api/classifieds/${id}`, {
                headers,
            });
            if (res && res.status === 204) {
                this.classifieds = this.classifieds.filter((c) => c.id !== id);
            }
            return res;
        },
    },
});

export default useClassifiedsStore;
