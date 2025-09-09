<template>
    <section class="post-list pt-2 px-2 sm:px-0">
        <h2>Posts</h2>
        <PostForm
            v-if="showForm"
            :post="editPost"
            @saved="onSaved"
            @cancel="cancelEdit"
        />
        <button v-if="!showForm" @click="showForm = true">Novo Post</button>
        <ul>
            <li v-for="post in posts" :key="post.id">
                <PostItem
                    :post="post"
                    :currentUser="currentUser"
                    @edit="editPostHandler"
                    @deleted="onDeleted"
                />
            </li>
        </ul>
    </section>
</template>
<script>
import PostForm from "./PostForm.vue";
import PostItem from "./PostItem.vue";

export default {
    name: "PostList",
    components: { PostForm, PostItem },
    data() {
        return {
            posts: [],
            showForm: false,
            editPost: null,
            currentUser: null, // Preencha com o usuário logado
        };
    },
    created() {
        this.fetchPosts();
        this.fetchCurrentUser();
    },
    methods: {
        async fetchPosts() {
            const response = await fetch("/api/posts");
            this.posts = await response.json();
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
};
</script>
