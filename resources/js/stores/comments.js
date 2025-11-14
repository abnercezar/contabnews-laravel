import { defineStore } from "pinia";
import axios from "axios";

export const useCommentsStore = defineStore("comments", {
    state: () => ({
        comments: [],
        loading: false,
    }),
    getters: {
        all: (state) => state.comments,
        count: (state) => state.comments.length,
        byPost: (state) => (postId) =>
            state.comments.filter((c) => c.post_id === postId),
    },
    actions: {
        async fetchFromPosts(perPage = 50) {
            this.loading = true;
            try {
                const res = await axios.get(`/api/posts?per_page=${perPage}`);
                const posts = Array.isArray(res.data) ? res.data : [];
                const out = [];
                for (const p of posts) {
                    if (Array.isArray(p.comments)) {
                        for (const c of p.comments) {
                            out.push(this._mapCommentFromPost(c, p));
                        }
                    }
                }
                out.sort((a, b) => {
                    if (a.created_at && b.created_at)
                        return new Date(b.created_at) - new Date(a.created_at);
                    return 0;
                });
                this.comments = out;
            } catch (e) {
                this.comments = [];
            } finally {
                this.loading = false;
            }
        },

        // helper to normalize comment shape
        _mapCommentFromPost(c, post) {
            return {
                id: c.id,
                body: c.body || c.text || "",
                post_id: post.id,
                tabcoins: c.tabcoins || 0,
                replies: Array.isArray(c.replies)
                    ? c.replies.length
                    : c.replies_count || 0,
                username: (c.user && c.user.name) || c.author || "Anônimo",
                created_at: c.created_at || c.time || null,
                raw: c,
            };
        },

        // add a comment to the top of the list
        addComment(c) {
            if (!c || !c.id) return;
            const mapped = {
                id: c.id,
                body: c.body || c.text || "",
                post_id: c.post_id || c.postId || null,
                tabcoins: c.tabcoins || 0,
                replies: Array.isArray(c.replies)
                    ? c.replies.length
                    : c.replies_count || 0,
                username: (c.user && c.user.name) || c.author || "Anônimo",
                created_at: c.created_at || c.time || null,
                raw: c,
            };
            const exists = this.comments.findIndex((x) => x.id === mapped.id);
            if (exists !== -1) this.comments.splice(exists, 1, mapped);
            else this.comments.unshift(mapped);
        },

        updateComment(updated) {
            if (!updated || !updated.id) return;
            const idx = this.comments.findIndex((c) => c.id === updated.id);
            if (idx === -1) return;
            this.comments.splice(
                idx,
                1,
                Object.assign({}, this.comments[idx], {
                    body: updated.body || this.comments[idx].body,
                    tabcoins: updated.tabcoins ?? this.comments[idx].tabcoins,
                    raw: updated,
                })
            );
        },

        removeComment(id) {
            if (!id) return;
            this.comments = this.comments.filter((c) => c.id !== id);
        },

        replace(comments) {
            if (!Array.isArray(comments)) return;
            this.comments = comments;
        },

        clear() {
            this.comments = [];
        },
    },
});

export default useCommentsStore;
