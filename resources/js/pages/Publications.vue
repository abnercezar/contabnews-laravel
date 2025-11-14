<script>
import StandardLayout from "../components/StandardLayout.vue";
import { usePostsStore } from "../stores/posts";
import PostList from "../components/PostList.vue";

export default {
    name: "Publications",
    components: { StandardLayout, PostList },
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
            post: {
                id: null,
                title: "",
                url: "#",
                author: "",
            },
            // comentários removidos da lista de publicações
        };
    },
    mounted() {
        // usa o store do Pinia para gerenciar posts
        try {
            this.postsStore = usePostsStore();
            // busca do backend (atualiza store.posts)
            this.postsStore.fetchPosts().then(() => {
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
            });
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
    <StandardLayout>
        <div class="mt-8 text-left">
            <!-- Lista de publicações usando PostList -->
            <div class="mb-4">
                <PostList
                    :posts="postsList"
                    filter="Publicações"
                    @open="openById"
                />
            </div>
        </div>
    </StandardLayout>
</template>

<style scoped>
/* Estilo local para cards e hover */
</style>
