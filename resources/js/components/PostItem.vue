<template>
    <li class="post-item py-2 sm:py-3 border-b border-gray-200">
        <template v-if="index === 0">
            <a
                href="#"
                class="post-ad text-green-600 font-bold text-sm mb-2 block"
                >Anúncio: Clique aqui para saber mais!</a
            >
        </template>

        <div class="flex items-start gap-4">
            <!-- votes column -->
            <div class="w-10 flex flex-col items-center text-sm text-gray-500">
                <button
                    @click.prevent="vote('up')"
                    class="text-green-600 hover:text-green-800"
                >
                    ▲
                </button>
                <div class="font-bold">{{ post.tabcoins ?? 0 }}</div>
                <button
                    @click.prevent="vote('down')"
                    class="text-red-500 hover:text-red-700"
                >
                    ▼
                </button>
            </div>

            <div class="flex-1">
                <p
                    class="post-title text-base sm:text-lg font-bold text-gray-800 mb-2"
                >
                    <a
                        href="#"
                        @click.prevent="openPost"
                        class="hover:underline"
                    >
                        {{ index + 1 }}. {{ post.title }}
                    </a>
                </p>
                <div
                    class="post-info text-xs sm:text-sm text-gray-500 flex gap-2 sm:gap-4 flex-wrap"
                >
                    <span class="post-coin text-[#daa520]">{{
                        post.coin
                    }}</span>
                    <span class="post-comments">· {{ post.comments }}</span>
                    <span class="post-author">· {{ post.author }}</span>
                    <span class="post-time italic">· {{ post.time }}</span>
                </div>

                <div class="mt-2 flex items-center gap-3">
                    <button
                        @click="reply"
                        class="text-sm text-[#0066cc] hover:underline"
                    >
                        Responder
                    </button>

                    <div v-if="isAuthor" class="post-actions flex gap-2">
                        <button @click="$emit('edit', post)">Editar</button>
                        <button @click="deletePost">Excluir</button>
                    </div>
                </div>
            </div>
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
                            // Adicione o token de autenticação, se necessário
                        },
                    });
                    this.$emit("deleted", this.post);
                } catch (error) {
                    alert("Erro ao excluir o post");
                }
            }
        },
        reply() {
            const isLogged =
                typeof window !== "undefined" &&
                !!localStorage.getItem("token");
            const intended = `/content/create?replyTo=${this.post.id}`;
            if (!isLogged) {
                try {
                    localStorage.setItem("intendedPath", intended);
                } catch (e) {}
                window.location.href = "/login";
                return;
            }
            // se logado, navega para a página de resposta/criação
            try {
                if (
                    this.$inertia &&
                    typeof this.$inertia.visit === "function"
                ) {
                    this.$inertia.visit(intended);
                } else {
                    window.location.href = intended;
                }
            } catch (e) {
                window.location.href = intended;
            }
        },
        openPost() {
            const url = `/content/${this.post.id}`;
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
        async vote(type) {
            const token =
                typeof window !== "undefined"
                    ? localStorage.getItem("token")
                    : null;
            if (!token) {
                try {
                    localStorage.setItem(
                        "intendedPath",
                        `/content/${this.post.id}`
                    );
                } catch (e) {}
                window.location.href = "/login";
                return;
            }
            try {
                const res = await fetch(
                    `/api/posts/${this.post.id}/reactions`,
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: `Bearer ${token}`,
                            Accept: "application/json",
                        },
                        body: JSON.stringify({ type }),
                    }
                );
                if (!res.ok) {
                    console.error("vote failed", await res.text());
                    return;
                }
                const data = await res.json();
                // atualiza contadores locais
                this.$set(this.post, "tabcoins", data.tabcoins);
                this.$set(this.post, "tabcashs", data.tabcashs);
            } catch (e) {
                console.error(e);
            }
        },
    },
};
</script>
