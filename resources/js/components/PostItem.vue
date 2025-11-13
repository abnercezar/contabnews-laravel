<script>
export default {
    name: "PostItem",
    props: {
        post: { type: Object, required: true },
        index: { type: Number, required: true },
        currentUser: Object,
        showActions: { type: Boolean, default: true },
        compact: { type: Boolean, default: false },
    },
    computed: {
        isAuthor() {
            if (!this.currentUser || !this.post) return false;
            const a1 = (this.currentUser.name || "").toLowerCase();
            const a2 = (this.post.author || "").toLowerCase();
            return a1 && a1 === a2;
        },
        tabcoinsLabel() {
            const n = this.post.tabcoins ?? 0;
            return `${n} tabcoin${n === 1 ? "" : "s"}`;
        },
        commentsLabel() {
            let c = 0;
            if (Array.isArray(this.post.comments))
                c = this.post.comments.length;
            else if (typeof this.post.comments === "number")
                c = this.post.comments;
            else if (this.post.comments_count) c = this.post.comments_count;
            return `${c} comentário${c === 1 ? "" : "s"}`;
        },
        formattedDate() {
            if (!this.post || !this.post.created_at) return "";
            try {
                return new Date(this.post.created_at).toLocaleString();
            } catch {
                return this.post.created_at;
            }
        },
    },
    methods: {
        onOpen() {
            // Novo comportamento: emitir evento em vez de navegar direto
            this.$emit("open", this.post.id);
        },
        async deletePost() {
            if (!confirm("Tem certeza que deseja excluir este post?")) return;
            try {
                const res = await fetch(`/api/posts/${this.post.id}`, {
                    method: "DELETE",
                    headers: { Accept: "application/json" },
                });
                if (!res.ok) throw new Error("delete failed");
                this.$emit("deleted", this.post);
            } catch {
                alert("Erro ao excluir o post");
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
                } catch {}
                window.location.href = "/login";
                return;
            }
            if (this.$inertia && typeof this.$inertia.visit === "function")
                this.$inertia.visit(intended);
            else window.location.href = intended;
        },
        share() {
            try {
                const url = `${window.location.origin}/content/${this.post.id}`;
                if (navigator.share)
                    navigator.share({ title: this.post.title, url });
                else {
                    navigator.clipboard.writeText(url);
                    alert("Link copiado para a área de transferência");
                }
            } catch {
                try {
                    navigator.clipboard.writeText(
                        `${window.location.origin}/content/${this.post.id}`
                    );
                    alert("Link copiado!");
                } catch {}
            }
        },
    },
};
</script>
<template>
    <li
        :class="[
            'post-item border-b border-gray-200',
            compact ? 'py-3' : 'py-5',
        ]"
    >
        <div class="flex items-start gap-4">
            <!-- index -->
            <div
                :class="
                    compact
                        ? 'w-12 text-2xl font-bold text-gray-800 text-right pr-4'
                        : 'w-14 text-3xl font-extrabold text-gray-900 text-right pr-4'
                "
            >
                {{ index }}.
            </div>

            <!-- content -->
            <div class="flex-1">
                <p
                    :class="
                        compact
                            ? 'text-sm sm:text-base font-medium text-gray-900 mb-1 leading-tight'
                            : 'text-base sm:text-xl font-bold text-gray-900 mb-1 leading-tight'
                    "
                >
                    <a href="#" @click.prevent="onOpen" class="hover:underline">
                        {{ post.title }}
                    </a>
                </p>

                <div
                    :class="
                        compact
                            ? 'text-xs text-gray-600 mb-2 flex flex-wrap gap-3 items-center'
                            : 'text-xs sm:text-sm text-gray-600 mb-3 flex flex-wrap gap-3 items-center'
                    "
                >
                    <span class="tabcoins text-[#006400] font-medium">
                        {{ tabcoinsLabel }}
                    </span>
                    <span>·</span>
                    <span class="comments">{{ commentsLabel }}</span>
                    <span>·</span>
                    <span class="author">{{ post.author || "Anônimo" }}</span>
                    <span>·</span>
                    <span class="time">{{ formattedDate }}</span>
                </div>

                <div class="actions flex items-center gap-3">
                    <button
                        v-if="showActions"
                        @click="reply"
                        class="text-sm text-[#0066cc] hover:underline"
                    >
                        Responder
                    </button>

                    <button
                        v-if="showActions"
                        @click="share"
                        class="text-sm px-2 py-1 border rounded bg-white hover:bg-gray-50 flex items-center justify-center"
                        aria-label="Compartilhar"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-gray-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 8a3 3 0 10-2.83-4H7a2 2 0 00-2 2v10a2 2 0 002 2h6.17A3 3 0 1015 16"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 8v4m0 0l3-3m-3 3l-3-3"
                            />
                        </svg>
                    </button>

                    <div v-if="isAuthor" class="ml-auto flex gap-2 text-sm">
                        <button
                            @click="$emit('edit', post)"
                            class="text-gray-600 hover:underline"
                        >
                            Editar
                        </button>
                        <button
                            @click="deletePost"
                            class="text-red-600 hover:underline"
                        >
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </li>
</template>

<style scoped>
.post-item a {
    transition: color 0.2s ease;
}
.post-item a:hover {
    color: #006400;
}
</style>
