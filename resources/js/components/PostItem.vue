<template>
    <li class="post-item py-2 sm:py-3 border-b border-gray-200">
        <template v-if="index === 0">
            <a
                href="#"
                class="post-ad text-green-600 font-bold text-sm mb-2 block"
                >Anúncio: Clique aqui para saber mais!</a
            >
        </template>
        <p class="post-title text-base sm:text-lg font-bold text-gray-800 mb-2">
            {{ index + 1 }}. {{ post.title }}
        </p>
        <div
            class="post-info text-xs sm:text-sm text-gray-500 flex gap-2 sm:gap-4 flex-wrap"
        >
            <span class="post-coin text-[#daa520]">{{ post.coin }}</span>
            <span class="post-comments">· {{ post.comments }}</span>
            <span class="post-author">· {{ post.author }}</span>
            <span class="post-time italic">· {{ post.time }}</span>
        </div>
        <div v-if="isAuthor" class="post-actions mt-2 flex gap-2">
            <button @click="$emit('edit', post)">Editar</button>
            <button @click="deletePost">Excluir</button>
        </div>
    </li>
</template>
<script>
export default {
    name: "PostItem",
    props: {
        post: Object,
        index: Number,
        currentUser: Object,
    },
    computed: {
        isAuthor() {
            return (
                this.currentUser && this.currentUser.name === this.post.author
            );
        },
    },
    methods: {
        async deletePost() {
            if (confirm("Tem certeza que deseja excluir este post?")) {
                try {
                    await fetch(`/api/posts/${this.post.id}`, {
                        method: "DELETE",
                        headers: {
                            Accept: "application/json",
                            // Adicione o token de autenticação se necessário
                        },
                    });
                    this.$emit("deleted", this.post);
                } catch (error) {
                    alert("Erro ao excluir o post");
                }
            }
        },
    },
};
</script>
