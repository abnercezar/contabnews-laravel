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
            comments: [],
            newCommentText: "",
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
                    this.comments = this.newPost.comments || [];
                    return;
                }

                // preenche lista local (a lista reativa principal deve vir do store)
                this.posts = this.postsStore.posts || [];
                if (this.posts.length > 0) {
                    this.post = this.posts[0];
                    this.comments = this.post.comments || [];
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
            const postId =
                comment?.post_id ?? comment?.postId ?? this.post?.id ?? null;
            const url = postId ? `/content/${postId}` : comment?.url ?? "#";
            try {
                if (this.$inertia && typeof this.$inertia.visit === "function")
                    this.$inertia.visit(url);
                else window.location.href = url;
            } catch (e) {
                window.location.href = url;
            }
        },
        addComment() {
            if (!this.newCommentText.trim()) return;
            const newComment = {
                id: Date.now(),
                text: this.newCommentText.trim(),
                tabcoins: 0,
                replies: 0,
                username: "Você",
                time: "Agora",
                url: "#",
            };
            this.comments.push(newComment);
            this.newCommentText = "";
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
