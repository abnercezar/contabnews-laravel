<script>
import ConTabNewsIcon from "../components/ConTabNewsIcon.vue";
import PostList from "../components/PostList.vue";

export default {
    name: "Publications",
    components: { ConTabNewsIcon, PostList },
    data() {
        return {
            isLoggedIn: false,
            posts: [],
            loading: true,
            page: 1,
            perPage: 20,
            hasMore: false,
            loadingMore: false,
        };
    },
    mounted() {
        // token pode ter outra chave dependendo do auth; ajuste se necessário
        this.isLoggedIn =
            !!localStorage.getItem("token") ||
            localStorage.getItem("isLoggedIn") === "true";
        this.fetchPosts();
        // se estiver usando Inertia e veio newPost via props de servidor
        this.tryAddNewPostFromPage();
    },
    methods: {
        navigateTo(url) {
            try {
                if (
                    this.$inertia &&
                    typeof this.$inertia.visit === "function"
                ) {
                    this.$inertia.visit(url);
                } else {
                    window.location.href = url;
                }
            } catch (e) {
                window.location.href = url;
            }
        },

        async fetchPosts() {
            this.loading = true;
            this.page = 1;
            this.hasMore = false;
            try {
                const res = await fetch(
                    `/api/posts?page=${this.page}&per_page=${this.perPage}`,
                    {
                        headers: { Accept: "application/json" },
                    }
                );

                if (!res.ok) {
                    this.posts = [];
                    return;
                }

                const json = await res.json();
                // aceita formato Laravel Resource Collection { data: [...], meta: {...} }
                const dataArray = Array.isArray(json) ? json : json.data ?? [];
                this.posts = Array.isArray(dataArray) ? dataArray : [];

                // tenta inferir paginação/hasMore a partir de meta ou total
                if (json.meta) {
                    // estruturas comuns: meta.current_page, meta.last_page OR meta.has_more
                    if (typeof json.meta.has_more !== "undefined") {
                        this.hasMore = !!json.meta.has_more;
                    } else if (
                        typeof json.meta.current_page !== "undefined" &&
                        typeof json.meta.last_page !== "undefined"
                    ) {
                        this.hasMore =
                            json.meta.current_page < json.meta.last_page;
                    } else {
                        // fallback: se itens retornados == perPage, presume que pode haver mais
                        this.hasMore = this.posts.length === this.perPage;
                    }
                } else {
                    this.hasMore = this.posts.length === this.perPage;
                }

                // se newPost foi passado via Inertia, adiciona no topo (tenta com segurança)
                this.tryAddNewPostFromPage();
            } catch (e) {
                this.posts = [];
                // opcional: console.error(e)
            } finally {
                this.loading = false;
            }
        },

        tryAddNewPostFromPage() {
            try {
                const newPost =
                    this.$page && this.$page.props && this.$page.props.newPost
                        ? this.$page.props.newPost
                        : null;
                if (!newPost) return;

                const exists = this.posts.some(
                    (p) =>
                        (p.id && newPost.id && p.id === newPost.id) ||
                        (p.title && newPost.title && p.title === newPost.title)
                );
                if (!exists) this.posts.unshift(newPost);
            } catch (e) {
                // swallow
            }
        },

        goToCreateContent() {
            const isLogged =
                (typeof window !== "undefined" &&
                    !!localStorage.getItem("token")) ||
                localStorage.getItem("isLoggedIn") === "true";

            if (!isLogged) {
                try {
                    localStorage.setItem("intendedPath", "/content/create");
                } catch (e) {
                    // ignore localStorage errors
                }
                this.navigateTo("/login");
                return;
            }

            this.navigateTo("/content/create");
        },

        openPost(id) {
            const url = `/content/${id}`;
            this.navigateTo(url);
        },

        async loadMore() {
            if (this.loadingMore) return;
            this.loadingMore = true;
            try {
                const nextPage = this.page + 1;
                const res = await fetch(
                    `/api/posts?page=${nextPage}&per_page=${this.perPage}`,
                    {
                        headers: { Accept: "application/json" },
                    }
                );
                if (!res.ok) return;

                const json = await res.json();
                const dataArray = Array.isArray(json) ? json : json.data ?? [];
                const newPosts = Array.isArray(dataArray) ? dataArray : [];

                // acrescenta sem duplicar (por segurança)
                newPosts.forEach((p) => {
                    const exists = this.posts.some(
                        (x) => x.id && p.id && x.id === p.id
                    );
                    if (!exists) this.posts.push(p);
                });

                // atualizar page e hasMore
                this.page = nextPage;
                if (json.meta) {
                    if (typeof json.meta.has_more !== "undefined") {
                        this.hasMore = !!json.meta.has_more;
                    } else if (
                        typeof json.meta.current_page !== "undefined" &&
                        typeof json.meta.last_page !== "undefined"
                    ) {
                        this.hasMore =
                            json.meta.current_page < json.meta.last_page;
                    } else {
                        this.hasMore = newPosts.length === this.perPage;
                    }
                } else {
                    this.hasMore = newPosts.length === this.perPage;
                }
            } catch (e) {
                // ignore
            } finally {
                this.loadingMore = false;
            }
        },
    },
};
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col">
        <main class="flex-1">
            <div class="w-full max-w-4xl mx-auto px-4 py-8">
                <div class="flex flex-col">
                    <!-- Loading -->
                    <template v-if="loading && posts.length === 0">
                        <div
                            class="flex items-center justify-center py-8 text-gray-500"
                        >
                            <svg
                                class="animate-spin h-6 w-6 mr-2"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v8z"
                                ></path>
                            </svg>
                            Carregando publicações...
                        </div>
                    </template>

                    <!-- Empty state -->
                    <template v-else-if="!loading && posts.length === 0">
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

                    <!-- Posts list -->
                    <template v-else>
                        <!-- Featured / first post (se existir) -->
                        <div v-if="posts[0]" class="mb-6">
                            <a
                                href="#"
                                @click.prevent="openPost(posts[0].id)"
                                class="text-green-600 font-extrabold text-2xl hover:underline"
                            >
                                {{ posts[0].title }}
                            </a>
                            <div class="text-sm text-gray-500 mt-1">
                                Contribuindo com
                                {{ posts[0].author || "Anônimo" }}
                            </div>
                        </div>

                        <!-- Lista de posts usando componente reutilizável (começa no índice 2) -->
                        <PostList
                            :posts="posts.slice(1)"
                            :start="2"
                            :show-actions="false"
                            @open="openPost"
                        />

                        <!-- Load more -->
                        <div
                            class="text-center mt-6"
                            v-if="hasMore || loadingMore"
                        >
                            <button
                                @click="loadMore"
                                :disabled="loadingMore"
                                class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="loadingMore">Carregando...</span>
                                <span v-else>Ver mais</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Você pode ajustar estilos locais aqui, se desejar */
</style>
