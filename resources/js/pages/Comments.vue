<script>
import StandardLayout from "../components/StandardLayout.vue";
import Modal from "../components/Modal.vue";
import MarkdownEditor from "../components/MarkdownEditor.vue";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { useCommentsStore } from "../stores/comments";
import { useAuthStore } from "../stores/auth";
import { onMounted } from "vue";

export default {
    name: "Comments",
    components: {
        StandardLayout,
        Modal,
        MarkdownEditor,
        PencilIcon,
        TrashIcon,
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
        };
    },
    computed: {
        comments() {
            return this.commentsStore.comments || [];
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

        openComment(comment) {
            // If comment references a post id, go to that post. Otherwise try the parent post in this page, then fallback to comment.url
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
    },
};
</script>

<template>
    <StandardLayout>
        <div class="mt-8 text-left">
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
                    <span class="text-black font-medium">{{
                        post.author
                    }}</span>
                </p>
            </div>

            <!-- Lista de comentários -->
            <div class="flex flex-col gap-5">
                <a
                    v-for="(comment, index) in comments"
                    :key="index"
                    href="#"
                    @click.prevent="openComment(comment)"
                    class="block border-b border-gray-100 pb-4 rounded-md px-2 hover:bg-gray-50 transition-colors duration-150 cursor-pointer group"
                >
                    <p
                        class="text-gray-800 leading-relaxed text-base group-hover:text-blue-700 group-hover:underline"
                    >
                        {{ comment.body }}
                    </p>

                    <div
                        class="flex flex-wrap gap-2 text-xs text-gray-500 mt-2"
                    >
                        <span>
                            {{ comment.tabcoins }}
                            {{ comment.tabcoins > 1 ? "tabcoins" : "tabcoin" }}
                        </span>
                        <span>·</span>
                        <span>
                            {{ comment.replies }}
                            {{
                                comment.replies === 1
                                    ? "comentário"
                                    : "comentários"
                            }}
                        </span>
                        <span>·</span>
                        <span class="font-medium text-gray-700">{{
                            comment.username
                        }}</span>
                        <span>·</span>
                        <span>{{ comment.created_at }}</span>
                        <span class="ml-2"> </span>
                        <span class="ml-auto flex items-center gap-2">
                            <button
                                @click.stop.prevent="shareComment(comment)"
                                class="p-1 rounded hover:bg-gray-100"
                            >
                                🔗
                            </button>
                            <button
                                v-if="isCommentAuthor(comment)"
                                @click.stop.prevent="openEditComment(comment)"
                                class="p-1 rounded hover:bg-gray-100"
                            >
                                <PencilIcon class="h-4 w-4 text-gray-600" />
                            </button>
                            <button
                                v-if="isCommentAuthor(comment)"
                                @click.stop.prevent="
                                    confirmCommentDelete(comment)
                                "
                                class="p-1 rounded hover:bg-gray-100"
                            >
                                <TrashIcon class="h-4 w-4 text-red-600" />
                            </button>
                        </span>
                    </div>
                </a>
            </div>

            <!-- Edit comment modal -->
            <div
                v-if="showCommentEditModal"
                class="fixed inset-0 z-50 flex items-center justify-center"
            >
                <div
                    class="absolute inset-0 bg-black opacity-50"
                    @click="showCommentEditModal = false"
                ></div>
                <div
                    class="relative bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6"
                >
                    <h3
                        class="text-lg font-semibold mb-3 flex items-center gap-2"
                    >
                        <PencilIcon class="h-5 w-5 text-gray-600" />Editar
                        comentário
                    </h3>
                    <MarkdownEditor v-model="commentEdit.body" />
                    <div
                        v-if="commentEditErrors.body"
                        class="text-red-600 text-sm mt-2"
                    >
                        {{ commentEditErrors.body[0] }}
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            @click="showCommentEditModal = false"
                            class="px-3 py-1 bg-gray-100 rounded"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="submitCommentEdit"
                            :disabled="commentDeleteLoading"
                            class="px-4 py-2 bg-[#d3ad71] text-white rounded"
                        >
                            Salvar
                        </button>
                    </div>
                    <div
                        v-if="commentEditError"
                        class="text-red-600 text-sm mt-2"
                    >
                        {{ commentEditError }}
                    </div>
                </div>
            </div>

            <!-- Delete comment modal -->
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
                    <TrashIcon class="h-6 w-6 text-red-600 mt-0.5" />
                    <div>
                        <div>
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
        </div>
    </StandardLayout>
</template>
