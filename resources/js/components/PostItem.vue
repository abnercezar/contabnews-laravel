<script>
import Modal from "./Modal.vue";
import InlineToast from "./InlineToast.vue";

export default {
    name: "PostItem",
    props: {
        post: { type: Object, required: true },
        index: { type: Number, required: true },
        currentUser: Object,
        showActions: { type: Boolean, default: true },
        compact: { type: Boolean, default: false },
        showComments: { type: Boolean, default: true },
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
    components: { Modal, InlineToast },
    data() {
        return {
            actionError: "",
            showDeleteModal: false,
            deleteLoading: false,
            deleteError: "",
            toastMessage: "",
            showToast: false,
        };
    },
    methods: {
        onOpen() {
            // Novo comportamento: emitir evento em vez de navegar direto
            this.$emit("open", this.post.id);
        },
        confirmDelete() {
            this.deleteError = "";
            this.actionError = "";
            this.showDeleteModal = true;
        },
        async performDelete() {
            if (!this.post || !this.post.id) return;
            this.deleteLoading = true;
            try {
                const token = localStorage.getItem("token");
                const headers = { Accept: "application/json" };
                if (token) headers.Authorization = `Bearer ${token}`;
                const res = await fetch(`/api/posts/${this.post.id}`, {
                    method: "DELETE",
                    headers,
                });
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    this.deleteError =
                        data.message || "Erro ao apagar publicação";
                    return;
                }
                this.$emit("deleted", this.post);
                this.showDeleteModal = false;
            } catch (err) {
                console.error(err);
                this.deleteError = "Erro de rede ao apagar publicação.";
            } finally {
                this.deleteLoading = false;
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
                    this.toastMessage =
                        "Link copiado para a área de transferência";
                    this.showToast = true;
                    setTimeout(() => (this.showToast = false), 2500);
                }
            } catch {
                try {
                    navigator.clipboard.writeText(
                        `${window.location.origin}/content/${this.post.id}`
                    );
                    this.toastMessage = "Link copiado!";
                    this.showToast = true;
                    setTimeout(() => (this.showToast = false), 2500);
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
                    <span v-if="showComments">·</span>
                    <span v-if="showComments" class="comments">{{
                        commentsLabel
                    }}</span>
                    <span v-if="showComments">·</span>
                    <span v-if="post.author" class="author">{{
                        post.author
                    }}</span>
                    <span>·</span>
                    <span class="time">{{ formattedDate }}</span>
                </div>

                <div class="actions flex items-center gap-3">
                    <div v-if="isAuthor" class="ml-auto flex gap-2 text-sm">
                        <button
                            @click="$emit('edit', post)"
                            class="text-gray-600 hover:underline flex items-center gap-1"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-gray-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.5"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.862 3.487a1.875 1.875 0 012.651 2.651L8.454 16.197a1.875 1.875 0 01-.79.48L4 18l1.323-3.664a1.875 1.875 0 01.48-.79L16.862 3.487z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19.5 7.5l-2.5-2.5"
                                />
                            </svg>
                            <span>Editar</span>
                        </button>
                        <button
                            @click="confirmDelete"
                            class="text-red-600 hover:underline flex items-center gap-1"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-red-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.5"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 6h18"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 6v12a2 2 0 002 2h4a2 2 0 002-2V6M10 6V4a2 2 0 012-2h0a2 2 0 012 2v2"
                                />
                            </svg>
                            <span>Excluir</span>
                        </button>
                    </div>
                    <div
                        v-if="actionError"
                        class="ml-auto text-red-600 text-sm"
                    >
                        {{ actionError }}
                    </div>
                    <Modal
                        :visible="showDeleteModal"
                        title="Apagar publicação"
                        @confirm="performDelete"
                        @cancel="showDeleteModal = false"
                        :loading="deleteLoading"
                        confirmText="Apagar"
                        cancelText="Cancelar"
                    >
                        <div>Tem certeza que deseja excluir este post?</div>
                        <div
                            v-if="deleteError"
                            class="text-red-600 text-sm mt-2"
                        >
                            {{ deleteError }}
                        </div>
                    </Modal>
                    <InlineToast :message="toastMessage" :visible="showToast" />
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
