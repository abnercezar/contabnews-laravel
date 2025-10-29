<script>
import Header from "../components/Header.vue";
import ConTabNewsIcon from "../components/ConTabNewsIcon.vue";
export default {
    name: "Publications",
    components: { Header, ConTabNewsIcon },
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
                // Se houver um novo post passado como prop via Inertia (vindo do CreateContent), adicioná-lo ao topo
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
                        if (!exists) {
                            this.posts.unshift(newPost);
                        }
                    }
                } catch (e) {
                    // ignore
                }
            } catch (e) {
                this.posts = [];
            } finally {
                this.loading = false;
            }
        },
        goToCreateContent() {
            this.$inertia.visit("/content/create");
        },
    },
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <Header />
        <div class="flex-1 flex flex-col items-center mt-8">
            <div
                class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8 mb-8"
            >
                <h1 class="text-2xl font-bold mb-2 text-[#00244a] text-center">
                    ACA
                </h1>
                <nav class="flex justify-center mb-6">
                    <template v-if="isLoggedIn">
                        <a
                            href="/profile"
                            class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                            >Perfil</a
                        >
                    </template>
                    <a
                        href="/publications"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] bg-gray-100 rounded-t font-bold"
                        >Publicações</a
                    >
                    <a
                        href="/comments"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                        >Comentários</a
                    >
                    <a
                        href="/classifieds"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                        >Classificados</a
                    >
                </nav>
                <div class="flex flex-col items-center py-8">
                    <template v-if="loading">
                        <div class="text-gray-500">
                            Carregando publicações...
                        </div>
                    </template>
                    <template v-else>
                        <template v-if="posts.length === 0">
                            <svg
                                class="mb-4 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                width="40"
                                height="40"
                                fill="currentColor"
                                viewBox="0 0 448 512"
                            >
                                <path
                                    d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"
                                />
                            </svg>
                            <h2
                                class="text-lg font-semibold text-gray-700 mb-2"
                            >
                                Nenhuma publicação encontrada
                            </h2>
                            <span class="text-gray-500 mb-4"
                                >Você ainda não fez nenhuma publicação.</span
                            >
                            <button
                                @click="goToCreateContent"
                                class="bg-[#00244a] text-white px-4 py-2 rounded font-bold hover:bg-[#001a33] transition flex items-center gap-2"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    fill="currentColor"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M7.75 2a.75.75 0 0 1 .75.75V7h4.25a.75.75 0 0 1 0 1.5H8.5v4.25a.75.75 0 0 1-1.5 0V8.5H2.75a.75.75 0 0 1 0-1.5H7V2.75A.75.75 0 0 1 7.75 2Z"
                                    />
                                </svg>
                                Publicar conteúdo
                            </button>
                        </template>
                        <template v-else>
                            <div class="w-full">
                                <ul class="space-y-4">
                                    <li
                                        v-for="post in posts"
                                        :key="post.id"
                                        class="border-b py-4"
                                    >
                                        <a
                                            :href="`/content/${post.id}`"
                                            class="block"
                                        >
                                            <h3
                                                class="text-lg font-semibold text-[#00244a]"
                                            >
                                                {{ post.title }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                Por
                                                {{ post.author || "Anônimo" }} •
                                                <span
                                                    class="text-xs text-gray-400"
                                                    >{{
                                                        post.created_at
                                                            ? new Date(
                                                                  post.created_at
                                                              ).toLocaleString()
                                                            : ""
                                                    }}</span
                                                >
                                            </p>
                                            <p class="mt-2 text-gray-700">
                                                {{
                                                    (
                                                        post.content ||
                                                        post.body ||
                                                        ""
                                                    ).slice(0, 240)
                                                }}
                                                <span
                                                    v-if="
                                                        (
                                                            post.content ||
                                                            post.body ||
                                                            ''
                                                        ).length > 240
                                                    "
                                                    >...</span
                                                >
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
            <footer
                class="w-full max-w-2xl mx-auto text-center text-xs text-gray-500 py-4 border-t flex flex-col items-center"
            >
                <div class="flex items-center gap-2 mb-2">
                    <a
                        href="/"
                        aria-label="Página inicial"
                        class="inline-block"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="#daa520"
                            viewBox="0 0 24 24"
                            width="28"
                            height="28"
                            class="min-w-[28px] min-h-[28px] sm:min-w-[42px] sm:min-h-[42px]"
                        >
                            <path
                                d="M19 3H5a2 2 0 0 0-2 2v14a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V5a2 2 0 0 0-2-2zm0 2v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5h14zm-2 3H7v2h10V8zm0 4H7v2h10v-2zm-4 4H7v2h6v-2z"
                            ></path>
                        </svg>
                    </a>
                    © 2025 ConTabNews
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <a href="/contato" class="underline">Contato</a>
                    <a href="/faq" class="underline">FAQ</a>
                    <a href="" class="underline">GitHub</a>
                    <a href="/museu" class="underline">Museu</a>
                    <a href="/recentes/rss" class="underline">RSS</a>
                    <a href="" class="underline">Sobre</a>
                    <a href="/status" class="underline">Status</a>
                    <a href="/termos-de-uso" class="underline">Termos de Uso</a>
                </div>
            </footer>
        </div>
    </div>
</template>
