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
                this.posts = Array.isArray(res.data) ? res.data : [];
            } catch (e) {
                this.posts = [];
            } finally {
                this.loading = false;
            }
        },
        addPost(post) {
            if (!post) return;
            const exists = this.posts.find((p) => p.id === post.id);
            if (!exists) this.posts.unshift(post);
            else
                this.posts = this.posts.map((p) =>
                    p.id === post.id ? post : p
                );
        },
        replacePosts(posts) {
            this.posts = Array.isArray(posts) ? posts : [];
        },
        clear() {
            this.posts = [];
        },
    },
});

export default usePostsStore;
