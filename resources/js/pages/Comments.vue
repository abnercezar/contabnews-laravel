<script>
import Modal from "../components/Modal.vue";
import InlineToast from "../components/InlineToast.vue";
import MarkdownEditor from "../components/MarkdownEditor.vue";
import { useCommentsStore } from "../stores/comments";
import { useAuthStore } from "../stores/auth";
import { onMounted } from "vue";

export default {
    name: "Comments",
    components: {
        Modal,
        MarkdownEditor,
        InlineToast,
    },
    setup() {
        const commentsStore = useCommentsStore();
        onMounted(async () => {
            if (!commentsStore.comments.length)
                await commentsStore.fetchFromPosts(50);
        });
        return { commentsStore };
    },

    data() {
        return {
            post: {
                id: 4001,
                title: "🎤 Aprenda a cantar GRÁTIS com sua música favorita! (notefinder.com.br)",
                url: "#",
                author: "justtheryston",
            },
            // estado para edição/exclusão na aba de comentários
            showCommentEditModal: false,
            commentEdit: null,
            commentEditErrors: {},
            commentEditError: "",
            showCommentDeleteModal: false,
            commentDeleteTarget: null,
            commentDeleteLoading: false,
            commentDeleteError: "",
            toastMessage: "",
            showToast: false,
            // controle de qual comentário tem o menu aberto (id)
            showMenuFor: null,
            // (inline expansion/inline reply removed)
        };
    },
    computed: {
        comments() {
            return this.commentsStore.comments || [];
        },
    },
    mounted() {
        document.addEventListener("click", this.handleDocClick);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.handleDocClick);
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

        openComment(comment) {
            console.log("[Comments] openComment", comment);
            // Ao clicar no comentário, abre a página do post com âncora no comentário
            // Tenta derivar o postId a partir de várias formas que o objeto
            // comentário pode vir (post_id, postId, comment.post.id, raw.post_id).
            // Se nenhum for encontrado, não forçamos o fallback para `this.post.id`
            // para evitar abrir o post errado.
            const postId =
                comment?.post_id ??
                comment?.postId ??
                comment?.post?.id ??
                comment?.raw?.post_id ??
                null;

            // Se tivermos um postId válido, navegamos para o post com âncora.
            // Caso contrário, use um URL direto do comentário (se existir),
            // ou simplesmente mantenha '#'.
            const url = postId
                ? `/content/${postId}#comment-${comment?.id}`
                : comment?.url ?? "#";
            try {
                if (this.$inertia && typeof this.$inertia.visit === "function")
                    this.$inertia.visit(url);
                else window.location.href = url;
            } catch (e) {
                window.location.href = url;
            }
        },

        // abre o menu de ações (Responder / Editar / Apagar) para um comentário
        toggleMenu(commentId, ev) {
            if (ev && typeof ev.stopPropagation === "function")
                ev.stopPropagation();
            this.showMenuFor =
                this.showMenuFor === commentId ? null : commentId;
        },

        handleDocClick() {
            this.showMenuFor = null;
        },

        // ação de responder: redireciona para a página do post e anexa a âncora do comentário
        replyToComment(comment) {
            // manter comportamento de navegação para abrir editor na página do post
            const postId =
                comment?.post_id ?? comment?.postId ?? this.post?.id ?? null;
            const url = postId
                ? `/content/${postId}?replyTo=${comment?.id}#comment-${comment?.id}`
                : comment?.url ?? "#";
            try {
                if (this.$inertia && typeof this.$inertia.visit === "function")
                    this.$inertia.visit(url);
                else window.location.href = url;
            } catch (e) {
                window.location.href = url;
            }
        },

        // inline reply removed; use post page to reply

        isCommentAuthor(comment) {
            try {
                const auth = useAuthStore();
                if (!auth || !auth.user || !comment) return false;
                if (
                    typeof comment.raw?.user_id !== "undefined" &&
                    comment.raw?.user_id !== null
                ) {
                    return auth.user.id === comment.raw.user_id;
                }
                if (
                    typeof comment.user_id !== "undefined" &&
                    comment.user_id !== null
                ) {
                    return auth.user.id === comment.user_id;
                }
                const userName = (auth.user.name || "")
                    .toString()
                    .toLowerCase();
                const authorName = (
                    comment.raw?.user?.name ||
                    comment.username ||
                    ""
                )
                    .toString()
                    .toLowerCase();
                return userName && authorName && userName === authorName;
            } catch (e) {
                return false;
            }
        },

        openEditComment(comment) {
            this.commentEdit = Object.assign({}, comment);
            this.commentEditErrors = {};
            this.commentEditError = "";
            this.showCommentEditModal = true;
        },

        async submitCommentEdit() {
            if (!this.commentEdit || !this.commentEdit.id) return;
            this.commentEditError = "";
            this.commentDeleteLoading = true;
            try {
                const token = localStorage.getItem("token");
                if (!token) {
                    window.location.href = "/login";
                    return;
                }
                const res = await fetch(
                    `/api/comments/${this.commentEdit.id}`,
                    {
                        method: "PUT",
                        headers: Object.assign(
                            { "Content-Type": "application/json" },
                            token ? { Authorization: `Bearer ${token}` } : {}
                        ),
                        body: JSON.stringify({ body: this.commentEdit.body }),
                    }
                );
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    if (data && data.errors)
                        this.commentEditErrors = data.errors;
                    else
                        this.commentEditError =
                            data.message || "Erro ao atualizar comentário";
                    return;
                }
                const updated = await res.json();
                // atualiza store global
                this.commentsStore.updateComment(updated);
                // atualiza local view (computed comes from store)
                this.showCommentEditModal = false;
                this.commentEdit = null;
            } catch (e) {
                console.error("Erro ao editar comentário", e);
                this.commentEditError = "Erro de rede ao atualizar comentário";
            } finally {
                this.commentDeleteLoading = false;
            }
        },

        confirmCommentDelete(comment) {
            this.commentDeleteTarget = comment;
            this.commentDeleteError = "";
            this.showCommentDeleteModal = true;
            this.commentDeleteLoading = false;
        },

        openEditFromMenu(comment) {
            this.showMenuFor = null;
            this.openEditComment(comment);
        },

        deleteFromMenu(comment) {
            this.showMenuFor = null;
            this.confirmCommentDelete(comment);
        },

        async deleteComment() {
            if (!this.commentDeleteTarget || !this.commentDeleteTarget.id)
                return;
            this.commentDeleteLoading = true;
            try {
                const token = localStorage.getItem("token");
                if (!token) {
                    window.location.href = "/login";
                    return;
                }
                const res = await fetch(
                    `/api/comments/${this.commentDeleteTarget.id}`,
                    {
                        method: "DELETE",
                        headers: Object.assign(
                            { "Content-Type": "application/json" },
                            token ? { Authorization: `Bearer ${token}` } : {}
                        ),
                    }
                );
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    this.commentDeleteError =
                        data.message || "Erro ao apagar comentário";
                    return;
                }
                this.commentsStore.removeComment(this.commentDeleteTarget.id);
                this.showCommentDeleteModal = false;
                this.commentDeleteTarget = null;
            } catch (e) {
                console.error("Erro ao apagar comentário", e);
                this.commentDeleteError = "Erro de rede ao apagar comentário";
            } finally {
                this.commentDeleteLoading = false;
            }
        },

        shareComment(comment) {
            try {
                const postId = comment?.post_id ?? this.post?.id ?? null;
                const url = postId
                    ? window.location.origin +
                      `/content/${postId}#comment-${comment.id}`
                    : comment?.url ?? window.location.href;
                if (navigator.share)
                    navigator.share({
                        title: this.post?.title || "Comentário",
                        url,
                    });
                else {
                    navigator.clipboard.writeText(url);
                    try {
                        // rápido feedback via InlineToast
                        this.toastMessage = "Link do comentário copiado";
                        this.showToast = true;
                        setTimeout(() => (this.showToast = false), 2500);
                    } catch (e) {}
                }
            } catch (e) {
                try {
                    navigator.clipboard.writeText(window.location.href);
                } catch (e) {}
            }
        },
    },
};
</script>

<template>
    <div class="mt-8">
        <!-- Cabeçalho da publicação -->
        <div class="mb-4">
            <a
                href="#"
                @click.prevent="openPostItem(post)"
                class="text-blue-700 font-medium hover:underline text-sm"
            >
                {{ post.title }}
            </a>

            <p class="text-gray-500 text-xs mt-1">
                Contribuindo com
                <span class="text-black font-medium">
                    {{ post.author }}
                </span>
            </p>
        </div>

        <!-- LISTA DE COMENTÁRIOS -->
        <div class="flex flex-col gap-2">
            <a
                v-for="(comment, index) in comments"
                :key="comment.id"
                href="#"
                @click.prevent="openComment(comment)"
                class="block pb-1 rounded-md px-2 hover:bg-gray-50 transition-all duration-150 cursor-pointer group"
            >
                <div class="flex items-start gap-3">
                    <span
                        class="text-gray-700 w-8 text-right font-bold self-start flex-shrink-0 leading-tight"
                        >{{ index + 1 }}.</span
                    >
                    <p
                        class="text-gray-800 leading-relaxed text-base group-hover:text-blue-700 flex-1 min-w-0"
                    >
                        {{ comment.body }}
                    </p>
                </div>
            </a>
        </div>

        <!-- MODAL DO COMENTÁRIO EXPANDIDO -->
        <transition name="fade">
            <div
                v-if="expandedComment"
                class="fixed inset-0 z-40 flex items-center justify-center"
            >
                <div
                    class="absolute inset-0 bg-black/40"
                    @click="expandedComment = null"
                ></div>

                <div
                    class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 sm:mx-auto p-6 animate-pop"
                >
                    <!-- Header do comentário -->
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm text-gray-700 font-medium">
                                {{ expandedComment.user?.name || "Anônimo" }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ formatDate(expandedComment.created_at) }}
                            </div>
                        </div>

                        <button
                            @click="expandedComment = null"
                            class="text-gray-500"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Corpo do comentário -->
                    <div class="mt-4 text-gray-800 whitespace-pre-wrap">
                        {{ expandedComment.body }}
                    </div>

                    <!-- Ações -->
                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            v-if="isCommentAuthor(expandedComment)"
                            @click="openEditFromMenu(expandedComment)"
                            class="px-3 py-1 bg-gray-100 rounded"
                        >
                            Editar
                        </button>

                        <button
                            v-if="isCommentAuthor(expandedComment)"
                            @click="deleteFromMenu(expandedComment)"
                            class="px-3 py-1 bg-red-600 text-white rounded"
                        >
                            Apagar
                        </button>

                        <button
                            @click="shareComment(expandedComment)"
                            class="px-3 py-1 bg-white border rounded"
                        >
                            Compartilhar
                        </button>

                        <button
                            @click="replyToComment(expandedComment)"
                            class="px-3 py-1 bg-[#d3ad71] text-white rounded"
                        >
                            Responder
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- MODAL DE EDIÇÃO -->
        <transition name="fade">
            <div
                v-if="showCommentEditModal"
                class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm"
            >
                <div
                    class="absolute inset-0 bg-black/40"
                    @click="showCommentEditModal = false"
                ></div>

                <div
                    class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6 animate-pop"
                >
                    <h3
                        class="text-lg font-semibold mb-4 flex items-center gap-2"
                    >
                        ✏️ Editar comentário
                    </h3>

                    <MarkdownEditor v-model="commentEdit.body">
                        <template #header-right>
                            {{ (commentEdit.body || "").length }}/5000
                        </template>
                    </MarkdownEditor>

                    <div
                        v-if="commentEditErrors.body"
                        class="text-red-600 text-sm mt-2"
                    >
                        {{ commentEditErrors.body[0] }}
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            @click="showCommentEditModal = false"
                            class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded transition"
                        >
                            Cancelar
                        </button>

                        <button
                            @click="submitCommentEdit"
                            class="px-4 py-2 bg-[#d3ad71] text-white rounded hover:bg-[#c19a5f] transition"
                        >
                            Salvar
                        </button>
                    </div>

                    <div
                        v-if="commentEditError"
                        class="text-red-600 text-sm mt-3"
                    >
                        {{ commentEditError }}
                    </div>
                </div>
            </div>
        </transition>

        <!-- MODAL DE EXCLUSÃO -->
        <Modal
            :visible="showCommentDeleteModal"
            title="Apagar comentário"
            @confirm="deleteComment"
            @cancel="showCommentDeleteModal = false"
            :loading="commentDeleteLoading"
            confirmText="Apagar"
            cancelText="Cancelar"
        >
            <div class="flex items-start gap-3">
                <span class="text-red-600 text-xl">🗑️</span>

                <div>
                    <div class="font-medium">
                        Tem certeza que deseja apagar este comentário?
                    </div>

                    <div
                        v-if="commentDeleteError"
                        class="text-red-600 text-sm mt-2"
                    >
                        {{ commentDeleteError }}
                    </div>
                </div>
            </div>
        </Modal>
        <InlineToast :message="toastMessage" :visible="showToast" />
    </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-scale-enter-active {
    transition: all 0.15s ease;
}
.fade-scale-enter-from {
    opacity: 0;
    transform: scale(0.95);
}
.fade-scale-leave-active {
    transition: all 0.12s ease;
}
.fade-scale-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

.animate-pop {
    animation: pop 0.18s ease-out;
}
@keyframes pop {
    from {
        opacity: 0;
        transform: scale(0.94);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
