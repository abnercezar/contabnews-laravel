import { defineStore } from "pinia";
import axios from "axios";

export const usePostsStore = defineStore("posts", {
    state: () => ({
        posts: [],
        loading: false,
    }),
    getters: {
        all: (state) => state.posts,
    },
    actions: {
        async fetchPosts() {
            this.loading = true;
            try {
                const res = await axios.get("/api/posts");
                let list = Array.isArray(res.data) ? res.data : [];

                // normaliza cada post para garantir um flag booleano consistente
                const normalize = (p) => {
                    if (!p || typeof p !== "object") return p;
                    const sponsored = !!(
                        p?.isSponsoredContent ||
                        p?.isSponsored ||
                        p?.is_sponsored ||
                        p?.is_sponsored_content ||
                        p?.sponsored ||
                        p?.sponsored_at ||
                        p?.sponsor_id ||
                        p?.sponsored_until
                    );
                    // garanta um campo de data consistente
                    const created_at =
                        p?.created_at || p?.createdAt || p?.time || null;
                    return Object.assign({}, p, {
                        __isSponsored: sponsored,
                        created_at,
                    });
                };

                list = list.map(normalize);

                // prioriza posts patrocinados (anúncios) no topo e ordena por created_at desc
                list.sort((a, b) => {
                    const aSponsored = !!a.__isSponsored;
                    const bSponsored = !!b.__isSponsored;
                    if (aSponsored !== bSponsored) return aSponsored ? -1 : 1;
                    const aTime = new Date(a.created_at || 0).getTime() || 0;
                    const bTime = new Date(b.created_at || 0).getTime() || 0;
                    return bTime - aTime;
                });
                this.posts = list;
            } catch (e) {
                this.posts = [];
            } finally {
                this.loading = false;
            }
        },
        async deletePost(id) {
            if (!id) return false;
            try {
                const token = localStorage.getItem("token");
                const headers = token
                    ? { Authorization: `Bearer ${token}` }
                    : {};
                // use axios to leverage baseURL and interceptors
                const res = await axios.delete(`/api/posts/${id}`, {
                    headers,
                });
                // remove from local state
                this.posts = this.posts.filter((p) => p.id !== id);
                return res;
            } catch (e) {
                // rethrow so caller can handle
                throw e;
            }
        },
        addPost(post) {
            if (!post) return;
            // normalize incoming post
            const normalized = Object.assign({}, post, {
                __isSponsored: !!(
                    post?.isSponsoredContent ||
                    post?.isSponsored ||
                    post?.is_sponsored ||
                    post?.is_sponsored_content ||
                    post?.sponsored ||
                    post?.sponsored_at ||
                    post?.sponsor_id ||
                    post?.sponsored_until
                ),
                created_at:
                    post?.created_at || post?.createdAt || post?.time || null,
            });

            const existsIndex = this.posts.findIndex(
                (p) => p.id === normalized.id
            );
            if (existsIndex !== -1) {
                // substitui existente, mantendo normalização
                this.posts.splice(existsIndex, 1, normalized);
                return;
            }

            if (normalized.__isSponsored) {
                // anúncios sempre no topo
                this.posts.unshift(normalized);
                return;
            }

            // não patrocinado: inserir após o último patrocinado
            const lastSponsoredIndex = this.posts.reduce((acc, p, idx) => {
                return p && p.__isSponsored ? idx : acc;
            }, -1);
            if (lastSponsoredIndex >= 0) {
                this.posts.splice(lastSponsoredIndex + 1, 0, normalized);
            } else {
                // sem patrocinados: adiciona no topo (mais recente primeiro)
                this.posts.unshift(normalized);
            }
        },
        replacePosts(posts) {
            if (!Array.isArray(posts)) {
                this.posts = [];
                return;
            }
            // normalize incoming array
            const normalized = posts.map((p) =>
                Object.assign({}, p, {
                    __isSponsored: !!(
                        p?.isSponsoredContent ||
                        p?.isSponsored ||
                        p?.is_sponsored ||
                        p?.is_sponsored_content ||
                        p?.sponsored ||
                        p?.sponsored_at ||
                        p?.sponsor_id ||
                        p?.sponsored_until
                    ),
                    created_at:
                        p?.created_at || p?.createdAt || p?.time || null,
                })
            );
            // sort with sponsored first and recent first
            normalized.sort((a, b) => {
                const aS = !!a.__isSponsored;
                const bS = !!b.__isSponsored;
                if (aS !== bS) return aS ? -1 : 1;
                const aTime = new Date(a.created_at || 0).getTime() || 0;
                const bTime = new Date(b.created_at || 0).getTime() || 0;
                return bTime - aTime;
            });
            this.posts = normalized;
        },
        clear() {
            this.posts = [];
        },
    },
});

export default usePostsStore;
