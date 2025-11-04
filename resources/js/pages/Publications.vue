<script>
import Header from "../components/Header.vue";
import ConTabNewsIcon from "../components/ConTabNewsIcon.vue";
import Footer from "../components/Footer.vue";

export default {
    name: "Publications",
    components: { Header, ConTabNewsIcon, Footer },
    data() {
        return {
            isLoggedIn: false,
            posts: [],
            loading: true,
        };
    },
    mounted() {
        this.isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
        this.fetchPosts();
    },
    methods: {
        async fetchPosts() {
            try {
                this.loading = true;
                const res = await fetch("/api/posts");
                if (!res.ok) {
                    this.posts = [];
                    return;
                }
                const data = await res.json();
                this.posts = Array.isArray(data) ? data : [];
                // if newPost passed via Inertia, add to top
                try {
                    const newPost =
                        this.$page &&
                        this.$page.props &&
                        this.$page.props.newPost
                            ? this.$page.props.newPost
                            : null;
                    if (newPost) {
                        const exists = this.posts.some(
                            (p) =>
                                (p.id && newPost.id && p.id === newPost.id) ||
                                (p.title &&
                                    newPost.title &&
                                    p.title === newPost.title)
                        );
                        if (!exists) this.posts.unshift(newPost);
                    }
                } catch (e) {}
            } catch (e) {
                this.posts = [];
            } finally {
                this.loading = false;
            }
        },
        goToCreateContent() {
            if (this.$inertia && typeof this.$inertia.visit === "function") {
                this.$inertia.visit("/content/create");
            } else {
                window.location.href = "/content/create";
            }
        },
    },
};
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col">
        <Header />

        <main class="flex-1">
            <div class="w-full max-w-4xl mx-auto px-4 py-8">
                <div class="flex flex-col">
                    <template v-if="loading">
                        <div class="text-gray-500">
                            Carregando publicações...
                        </div>
                    </template>

                    <template v-else>
                        <template v-if="posts.length === 0">
                            <div class="text-center py-8 text-gray-500">
                                Nenhuma publicação encontrada
                            </div>
                            <div class="text-center">
                                <button
                                    @click="goToCreateContent"
                                    class="bg-[#daa520] text-white px-4 py-2 rounded font-bold hover:bg-[#d3ad71] transition"
                                >
                                    Publicar conteúdo
                                </button>
                            </div>
                        </template>

                        <template v-else>
                            <!-- Featured / first post in green -->
                            <div v-if="posts[0]" class="mb-6">
                                <a
                                    :href="`/content/${posts[0].id}`"
                                    class="text-green-600 font-semibold text-lg hover:underline"
                                    >{{ posts[0].title }}</a
                                >
                                <div class="text-sm text-gray-500 mt-1">
                                    Contribuindo com
                                    {{ posts[0].author || "Anônimo" }}
                                </div>
                            </div>

                            <!-- Numbered list -->
                            <ol class="list-decimal pl-6 space-y-6">
                                <li
                                    v-for="(post, idx) in posts.slice(1)"
                                    :key="post.id"
                                >
                                    <a
                                        :href="`/content/${post.id}`"
                                        class="text-lg font-semibold text-gray-900 hover:underline"
                                        >{{ post.title }}</a
                                    >
                                    <div class="text-sm text-gray-500 mt-1">
                                        1 tabcoin · 0 comentário ·
                                        {{ post.author || "Anônimo" }} ·
                                        {{
                                            post.created_at
                                                ? new Date(
                                                      post.created_at
                                                  ).toLocaleString()
                                                : ""
                                        }}
                                    </div>
                                </li>
                            </ol>
                        </template>
                    </template>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
