<script>
import PostForm from "./PostForm.vue";
import PostItem from "./PostItem.vue";

export default {
    name: "PostList",
    components: { PostForm, PostItem },
    props: {
        posts: {
            type: Array,
            default: () => [],
        },
        filter: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            fetchedPosts: [],
            showForm: false,
            editPost: null,
            currentUser: null, // Preencha com o usuário logado
        };
    },
    created() {
    // buscar apenas se o componente pai não passou posts
        if (!this.posts || this.posts.length === 0) {
            this.fetchPosts();
        }
        this.fetchCurrentUser();
    },
    methods: {
        async fetchPosts() {
            const response = await fetch("/api/posts");
            this.fetchedPosts = await response.json();
        },
        async fetchCurrentUser() {
        // Implemente a busca do usuário logado (exemplo: via /api/user)
            // this.currentUser = ...
        },
        onSaved(post) {
            this.showForm = false;
            this.editPost = null;
            this.fetchPosts();
        },
        editPostHandler(post) {
            this.editPost = post;
            this.showForm = true;
        },
        cancelEdit() {
            this.showForm = false;
            this.editPost = null;
        },
        onDeleted() {
            this.fetchPosts();
        },
    },
    computed: {
        displayedPosts() {
            // origem: prefere a prop posts passada pelo pai, caso contrário fetchedPosts
            const source =
                this.posts && this.posts.length
                    ? this.posts
                    : this.fetchedPosts;
            if (!this.filter || this.filter === "Todos") return source;

            // Se os itens tiverem o campo 'type', use-o. Caso contrário, aplica heurística simples.
            const map = {
                Publicações: (p) => !p.type || p.type === "post",
                Comentários: (p) => p.type === "comment" || !!p.comments,
                Classificados: (p) =>
                    p.type === "classified" || p.category === "classified",
            };
            const fn = map[this.filter];
            if (!fn) return source;
            return source.filter(fn);
        },
    },
};
</script>

<template>
    <section class="post-list pt-2 px-2 sm:px-0">
        <PostForm
            v-if="showForm"
            :post="editPost"
            @saved="onSaved"
            @cancel="cancelEdit"
        />
        <ul>
            <PostItem
                v-for="(post, index) in displayedPosts"
                :key="post.id || index"
                :post="post"
                :index="index"
                :currentUser="currentUser"
                @edit="editPostHandler"
                @deleted="onDeleted"
            />
        </ul>
    </section>
</template>
