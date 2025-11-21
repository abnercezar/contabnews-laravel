<script>
// usando AppLayout como layout padrão (definido em app.js)
import { usePostsStore } from "../stores/posts";
import { useAuthStore } from "../stores/auth";
import PostList from "../components/PostList.vue";

export default {
    name: "Publications",
    components: { PostList },
    props: {
        newPost: {
            type: [Object, null],
            default: null,
        },
    },
    data() {
        return {
            // lista de posts carregados da API
            posts: [],
            postsStore: null,
            isMine: false,
            auth: null,
            isAuthenticated: false,
            post: {
                id: null,
                title: "",
                url: "#",
                author: "",
            },
            // comentários removidos da lista de publicações
        };
    },
    async mounted() {
        // usa o store do Pinia para gerenciar posts
        try {
            this.postsStore = usePostsStore();
            try {
                this.auth = useAuthStore();
                this.isAuthenticated = !!(this.auth && this.auth.token);
            } catch (e) {
                this.auth = null;
                this.isAuthenticated = false;
            }

            // decide se deve buscar somente os posts do usuário (query ?mine=1)
            let mine = false;
            try {
                const params = new URLSearchParams(window.location.search);
                mine = params.get("mine") === "1";
            } catch (e) {
                mine = false;
            }

            // armazena o estado para uso no template
            this.isMine = mine;

            // se for para mostrar apenas "meus" posts, garanta que o backend receba Authorization
            if (mine && this.auth && this.auth.token) {
                try {
                    await this.auth.fetchUser();
                    this.isAuthenticated = !!this.auth.user;
                } catch (e) {}
            }

            // busca do backend (atualiza store.posts)
            try {
                await this.postsStore.fetchPosts({ mine });

                // se controller/inertia passou newPost, prioriza
                if (this.newPost && typeof this.newPost === "object") {
                    this.postsStore.addPost(this.newPost);
                    this.post = Object.assign({}, this.newPost);
                    return;
                }

                // preenche lista local (a lista reativa principal deve vir do store)
                this.posts = this.postsStore.posts || [];
                if (this.posts.length > 0) {
                    this.post = this.posts[0];
                }
            } catch (e) {
                this.posts = [];
            }
        } catch (e) {
            // se pinia não estiver disponível, fallback simples
        }
    },
    computed: {
        postsList() {
            try {
                return (
                    (this.postsStore && this.postsStore.posts) ||
                    this.posts ||
                    []
                );
            } catch (e) {
                return this.posts || [];
            }
        },
        emptyMessage() {
            // mensagem apropriada quando não há posts
            if (this.isMine) {
                if (!this.isAuthenticated)
                    return "Faça login para ver suas publicações.";
                return "Você ainda não possui publicações.";
            }
            return "Nenhuma publicação encontrada.";
        },
    },
    methods: {
        openPostItem(item) {
            const id = item?.id ?? item?.post_id ?? null;
            const url = id ? `/content/${id}` : item?.url ?? "#";
            try {
                if (this.$inertia && typeof this.$inertia.visit === "function")
                    this.$inertia.visit(url);
                else window.location.href = url;
            } catch (e) {
                window.location.href = url;
            }
        },
        // handler para o PostList emitir um open com id
        openById(id) {
            if (!id) return;
            const url = `/content/${id}`;
            try {
                if (this.$inertia && typeof this.$inertia.visit === "function")
                    this.$inertia.visit(url);
                else window.location.href = url;
            } catch (e) {
                window.location.href = url;
            }
        },
        openComment(comment) {
            // função removida do fluxo de Publicações (comentários não exibidos aqui)
            return;
        },
        addComment() {
            // comentários removidos da lista de publicações
            return;
        },
    },
};
</script>

<template>
    <div class="mt-8">
        <!-- Lista de publicações usando PostList -->
        <div class="mb-4">
            <template v-if="isMine && !isAuthenticated">
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="text-gray-700">{{ emptyMessage }}</p>
                    <div class="mt-2">
                        <a href="/login" class="text-blue-600 underline"
                            >Fazer login</a
                        >
                    </div>
                </div>
            </template>
            <template v-else>
                <PostList
                    :posts="postsList"
                    filter="Publicações"
                    @open="openById"
                />
                <div
                    v-if="(postsList || []).length === 0"
                    class="mt-4 text-gray-600"
                >
                    {{ emptyMessage }}
                </div>
            </template>
        </div>
    </div>
</template>

<style scoped>
/* Estilo local para cards e hover */
</style>
