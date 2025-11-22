<script>
import MarkdownEditor from "../components/MarkdownEditor.vue";
import MarkdownRenderer from "../components/MarkdownRenderer.vue";
import Modal from "../components/Modal.vue";
import InlineToast from "../components/InlineToast.vue";
import { PencilIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { usePostsStore } from "../stores/posts";
import { useAuthStore } from "../stores/auth";

export default {
    props: {
        post: Object,
    },
    components: {
        MarkdownEditor,
        MarkdownRenderer,
        Modal,
        InlineToast,
        PencilIcon,
        TrashIcon,
    },
    data() {
        return {
            showReply: false,
            replyBody: "",
            submitting: false,
            replyError: "",
            // id do comentário ao qual estamos respondendo (opcional)
            replyingToCommentId: null,
            // menu/edit/delete UI
            showMenu: false,
            showEditModal: false,
            editPost: null,
            saving: false,
            editErrors: {},
            editError: "",
            showDeleteModal: false,
            deleteLoading: false,
            deleteError: "",
            toastMessage: "",
            showToast: false,
            localPost: null,
            // comentário atualmente em destaque (quando a URL aponta para um comentário)
            activeComment: null,
        };
    },
    created() {
        // cria uma cópia local reativa do post para evitar mutações diretas na prop
        this.localPost = this.post ? JSON.parse(JSON.stringify(this.post)) : {};

        // Se a URL tiver query ?replyTo=ID ou ?openComment=ID, abre o editor de resposta automaticamente
        try {
            const params = new URLSearchParams(window.location.search);
            const replyTo = params.get("replyTo") || params.get("openComment");
            if (replyTo) {
                // tenta encontrar o comentário localmente para preencher quote
                const cid = replyTo;
                let commentObj = null;
                if (this.localPost && Array.isArray(this.localPost.comments)) {
                    commentObj = this.localPost.comments.find(
                        (c) => String(c.id) === String(cid)
                    );
                }
                if (commentObj) {
                    const snippet = commentObj.body
                        ? commentObj.body.slice(0, 200)
                        : "";
                    // include only the quoted snippet (no author prefix like 'Anônimo:')
                    this.replyBody = snippet ? `> ${snippet}\n\n` : "";
                }
                this.replyingToCommentId = cid;
                this.showReply = true;
                this.$nextTick(() => {
                    const editor = this.$refs.mdEditor;
                    if (editor && editor.$el) {
                        const ta = editor.$el.querySelector("textarea");
                        if (ta) ta.focus();
                    } else {
                        const ta = this.$el.querySelector("textarea");
                        if (ta) ta.focus();
                    }
                });
            }
        } catch (e) {}

        // Se a hash da URL for #comment-{id}, tenta carregar esse comentário para destacar
        try {
            const hash = window.location.hash || "";
            const m = hash.match(/#comment-(\d+)/);
            const cid = m ? m[1] : null;
            if (cid) {
                // se já temos o comentário nos dados locais, use-o; caso contrário, busque via API
                let commentObj = null;
                if (this.localPost && Array.isArray(this.localPost.comments)) {
                    commentObj = this.localPost.comments.find(
                        (c) => String(c.id) === String(cid)
                    );
                }
                if (commentObj) {
                    this.activeComment = commentObj;
                    // scroll suave até o comentário
                    this.$nextTick(() => {
                        const el = document.getElementById("comment-" + cid);
                        if (el)
                            el.scrollIntoView({
                                behavior: "smooth",
                                block: "center",
                            });
                    });
                } else {
                    this.fetchComment(cid)
                        .then((c) => {
                            if (c) {
                                this.activeComment = c;
                                this.$nextTick(() => {
                                    const el = document.getElementById(
                                        "comment-" + cid
                                    );
                                    if (el)
                                        el.scrollIntoView({
                                            behavior: "smooth",
                                            block: "center",
                                        });
                                });
                            }
                        })
                        .catch(() => {});
                }
            }
        } catch (e) {}
    },
    watch: {
        post(newVal) {
            this.localPost = newVal ? JSON.parse(JSON.stringify(newVal)) : {};
        },
    },
    computed: {
        formattedDate() {
            if (!this.localPost || !this.localPost.created_at) return "";
            return new Date(this.localPost.created_at).toLocaleString();
        },
        isAuthor() {
            try {
                const auth = useAuthStore();
                if (!auth || !auth.user || !this.localPost) return false;
                // prefer id-based check
                if (
                    typeof this.localPost.user_id !== "undefined" &&
                    this.localPost.user_id !== null
                ) {
                    return auth.user.id === this.localPost.user_id;
                }
                const userName = (auth.user.name || "")
                    .toString()
                    .toLowerCase();
                const authorName = (this.localPost.author || "")
                    .toString()
                    .toLowerCase();
                return userName && authorName && userName === authorName;
            } catch (e) {
                return false;
            }
        },
    },
    methods: {
        openReply(comment = null) {
            try {
                const token = localStorage.getItem("token");
                if (!token) {
                    try {
                        localStorage.setItem(
                            "intendedPath",
                            window.location.pathname
                        );
                    } catch (e) {}
                    window.location.href = "/login";
                    return;
                }

                // Se foi passado um comentário, responder a ele
                if (comment) {
                    this.replyingToCommentId = comment.id;
                    const snippet = comment.body
                        ? comment.body.slice(0, 200)
                        : "";
                    // include only the quoted snippet (no author prefix)
                    this.replyBody = snippet ? `> ${snippet}\n\n` : "";
                }

                this.showReply = true;
                this.$nextTick(() => {
                    // tenta focar dentro do MarkdownEditor se disponível
                    const editor = this.$refs.mdEditor;
                    if (editor && editor.$el) {
                        const ta = editor.$el.querySelector("textarea");
                        if (ta) ta.focus();
                    } else {
                        const ta = this.$el.querySelector("textarea");
                        if (ta) ta.focus();
                    }
                });
            } catch (e) {
                this.showReply = true;
            }
        },
        cancelReply() {
            this.showReply = false;
            this.replyBody = "";
            this.replyError = "";
            this.replyingToCommentId = null;
        },
        async submitReply() {
            this.replyError = "";
            if (!this.replyBody || !this.replyBody.trim()) {
                this.replyError = "Digite sua resposta antes de enviar.";
                return;
            }
            const intended = `/content/create?replyTo=${this.localPost.id}`;
            const token = localStorage.getItem("token");
            if (!token) {
                try {
                    localStorage.setItem("intendedPath", intended);
                } catch (e) {}
                window.location.href = "/login";
                return;
            }

            this.submitting = true;
            try {
                const res = await fetch("/api/comments", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`,
                    },
                    body: JSON.stringify({
                        post_id: this.localPost.id,
                        body: this.replyBody,
                        parent_id: this.replyingToCommentId,
                    }),
                });
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    this.replyError =
                        data.message || "Erro ao enviar comentário.";
                    return;
                }
                const comment = await res.json();
                if (!this.localPost.comments) this.localPost.comments = [];
                this.localPost.comments.unshift(comment);
                this.cancelReply();
            } catch (e) {
                this.replyError = "Erro de rede ao enviar comentário.";
            } finally {
                this.submitting = false;
            }
        },
        share() {
            try {
                const url = window.location.href;
                if (navigator.share) {
                    navigator.share({ title: this.localPost.title, url });
                } else {
                    navigator.clipboard.writeText(url);
                    this.toastMessage =
                        "Link copiado para a área de transferência";
                    this.showToast = true;
                    setTimeout(() => (this.showToast = false), 2500);
                }
            } catch (e) {
                // fallback
                try {
                    navigator.clipboard.writeText(window.location.href);
                } catch (e) {}
            }
        },
        shareComment(comment) {
            try {
                const url =
                    window.location.origin +
                    window.location.pathname +
                    "#comment-" +
                    comment.id;
                if (navigator.share)
                    navigator.share({ title: this.localPost.title, url });
                else {
                    navigator.clipboard.writeText(url);
                    this.toastMessage = "Link do comentário copiado";
                    this.showToast = true;
                    setTimeout(() => (this.showToast = false), 2500);
                }
            } catch (e) {
                try {
                    navigator.clipboard.writeText(
                        window.location.origin +
                            window.location.pathname +
                            "#comment-" +
                            comment.id
                    );
                    this.toastMessage = "Link do comentário copiado";
                    this.showToast = true;
                    setTimeout(() => (this.showToast = false), 2500);
                } catch (e) {}
            }
        },

        async fetchComment(id) {
            if (!id) return null;
            try {
                const res = await fetch(`/api/comments/${id}`);
                if (!res.ok) return null;
                const data = await res.json();
                return data;
            } catch (e) {
                return null;
            }
        },
        commentAnchorUrl(comment) {
            if (!comment || !comment.id) return "/comments";
            return `/comments?openComment=${comment.id}#comment-${comment.id}`;
        },
        // three-dots menu handlers
        toggleMenu() {
            this.showMenu = !this.showMenu;
        },
        openEditModal() {
            this.showMenu = false;
            this.editPost = Object.assign({}, this.localPost);
            this.showEditModal = true;
        },
        async submitEdit() {
            if (!this.editPost || !this.editPost.id) return;
            this.saving = true;
            try {
                const token = localStorage.getItem("token");
                const res = await fetch(`/api/posts/${this.editPost.id}`, {
                    method: "PUT",
                    headers: Object.assign(
                        { "Content-Type": "application/json" },
                        token ? { Authorization: `Bearer ${token}` } : {}
                    ),
                    body: JSON.stringify({
                        title: this.editPost.title,
                        content:
                            this.editPost.content ||
                            this.editPost.body ||
                            this.editPost.comment ||
                            "",
                        source_url: this.editPost.source_url,
                        isSponsoredContent: !!this.editPost.isSponsoredContent,
                    }),
                });
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    if (data && data.errors) {
                        this.editErrors = data.errors;
                    } else {
                        this.editError =
                            data.message || "Erro ao atualizar publicação";
                    }
                    return;
                }
                const updated = await res.json();
                // atualiza cópia local do post
                try {
                    for (const k in updated) {
                        this.localPost[k] = updated[k];
                    }
                } catch (e) {
                    this.localPost = Object.assign({}, this.localPost, updated);
                }
                // atualiza store de posts para manter a listagem sincronizada
                try {
                    const postsStore = usePostsStore();
                    postsStore.addPost(updated);
                } catch (e) {}
                this.showEditModal = false;
                this.editPost = null;
            } catch (e) {
                console.error("Erro ao salvar edição", e);
                this.editError = "Erro de rede ao atualizar publicação.";
            } finally {
                this.saving = false;
            }
        },
        confirmDelete() {
            this.showMenu = false;
            this.showDeleteModal = true;
            this.deleteLoading = false;
        },
        async deletePost() {
            if (!this.localPost || !this.localPost.id) return;
            this.deleteLoading = true;
            try {
                const postsStore = usePostsStore();
                await postsStore.deletePost(this.localPost.id);
                // redirect to publications after successful deletion
                window.location.href = "/publications";
            } catch (e) {
                console.error("Erro ao apagar", e);
                // try to extract message from response-like error
                this.deleteError =
                    (e &&
                        e.response &&
                        e.response.data &&
                        e.response.data.message) ||
                    "Erro ao apagar publicação";
            } finally {
                this.deleteLoading = false;
                this.showDeleteModal = false;
            }
        },
        async vote(type) {
            const token = localStorage.getItem("token");
            if (!token) {
                try {
                    localStorage.setItem(
                        "intendedPath",
                        window.location.pathname
                    );
                } catch (e) {}
                window.location.href = "/login";
                return;
            }
            try {
                const res = await fetch(
                    `/api/posts/${this.localPost.id}/reactions`,
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
                if (!res.ok) return;
                const data = await res.json();
                this.localPost.tabcoins = data.tabcoins;
                this.localPost.tabcashs = data.tabcashs;
            } catch (e) {
                console.error(e);
            }
        },
    },
};
</script>

<template>
    <div class="max-w-4xl mx-auto py-8">
        <!-- Title row with small index marker on the left like Tabnews -->
        <div class="flex items-start gap-4">
            <div class="w-10 flex-shrink-0 flex flex-col items-center gap-1">
                <button
                    @click.prevent="vote('up')"
                    class="text-green-600 hover:text-green-800"
                >
                    ▲
                </button>
                <div class="text-sm font-semibold">
                    {{ localPost.tabcoins ?? 0 }}
                </div>
                <button
                    @click.prevent="vote('down')"
                    class="text-red-500 hover:text-red-700"
                >
                    ▼
                </button>
            </div>

            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div class="pr-4">
                        <h1 class="text-4xl font-extrabold mb-2 leading-tight">
                            {{ localPost.title }}
                        </h1>
                        <div class="text-sm text-gray-500 mb-4">
                            Por
                            {{
                                localPost.author ? localPost.author : "Anônimo"
                            }}
                            •
                            {{ formattedDate }}
                        </div>
                    </div>

                    <div class="relative">
                        <button
                            v-if="isAuthor"
                            @click.stop="toggleMenu"
                            class="p-1 rounded hover:bg-gray-100"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-gray-500"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                        </button>
                        <div
                            v-if="showMenu"
                            class="absolute right-0 mt-2 w-36 bg-white border rounded shadow z-20"
                        >
                            <button
                                @click="openEditModal"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center"
                            >
                                <PencilIcon
                                    class="h-4 w-4 inline-block mr-2 text-gray-600"
                                />
                                <span>Editar</span>
                            </button>
                            <button
                                @click="confirmDelete"
                                class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-50 flex items-center"
                            >
                                <TrashIcon
                                    class="h-4 w-4 inline-block mr-2 text-red-600"
                                />
                                <span>Apagar</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Comentário em destaque quando há hash ? -->

                <div class="mb-6 text-base leading-relaxed text-gray-800">
                    <MarkdownRenderer :source="localPost.content" />
                </div>

                <div v-if="localPost.source_url" class="mb-6 text-sm">
                    <a
                        :href="localPost.source_url"
                        target="_blank"
                        class="text-blue-600 hover:underline"
                        >Fonte: {{ localPost.source_url }}</a
                    >
                </div>

                <!-- Edit modal -->
                <div
                    v-if="showEditModal"
                    class="fixed inset-0 z-40 flex items-center justify-center"
                >
                    <div
                        class="absolute inset-0 bg-black opacity-50"
                        @click="showEditModal = false"
                    ></div>
                    <div
                        class="relative bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6"
                    >
                        <h3
                            class="text-lg font-semibold mb-4 flex items-center gap-2"
                        >
                            <PencilIcon class="h-5 w-5 text-gray-600" />
                            <span>Editar publicação</span>
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Título</label
                                >
                                <input
                                    v-model="editPost.title"
                                    class="mt-1 block w-full border rounded px-3 py-2"
                                />
                                <div
                                    v-if="editErrors.title"
                                    class="text-red-600 text-sm mt-1"
                                >
                                    {{ editErrors.title[0] }}
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Conteúdo</label
                                >
                                <MarkdownEditor
                                    v-model="editPost.content"
                                    placeholder="Conteúdo da publicação"
                                >
                                    <template #header-right>
                                        {{
                                            (editPost.content || "").length
                                        }}/5000
                                    </template>
                                </MarkdownEditor>
                                <div
                                    v-if="editErrors.content"
                                    class="text-red-600 text-sm mt-1"
                                >
                                    {{ editErrors.content[0] }}
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-2"
                                    ><input
                                        type="checkbox"
                                        v-model="editPost.isSponsoredContent"
                                    />
                                    Publicação patrocinada</label
                                >
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end gap-2">
                            <button
                                @click="showEditModal = false"
                                class="px-4 py-2 bg-gray-100 rounded"
                            >
                                Cancelar
                            </button>
                            <button
                                @click="submitEdit"
                                :disabled="saving"
                                class="px-4 py-2 bg-[#d3ad71] text-white rounded hover:bg-[#bfae76]"
                            >
                                Salvar
                            </button>
                        </div>
                        <div v-if="editError" class="text-red-600 text-sm mt-2">
                            {{ editError }}
                        </div>
                    </div>
                </div>

                <!-- Delete confirmation modal using Modal component -->
                <Modal
                    :visible="showDeleteModal"
                    title="Apagar publicação"
                    @confirm="deletePost"
                    @cancel="showDeleteModal = false"
                    :loading="deleteLoading"
                    confirmText="Apagar"
                    cancelText="Cancelar"
                >
                    <div class="flex items-start gap-3">
                        <TrashIcon class="h-6 w-6 text-red-600 mt-0.5" />
                        <div>
                            <div>
                                Tem certeza que deseja apagar esta publicação?
                            </div>
                            <div
                                v-if="deleteError"
                                class="text-red-600 text-sm mt-2"
                            >
                                {{ deleteError }}
                            </div>
                        </div>
                    </div>
                </Modal>

                <!-- Reply card / editor: o card é mostrado quando fechado e substituído pelo editor quando aberto -->
                <div class="mt-4">
                    <transition name="fade">
                        <!-- Card colapsado -->
                        <div
                            v-if="!showReply"
                            @click="openReply"
                            class="reply-card cursor-pointer rounded border border-gray-200 bg-white p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3"
                        >
                            <div
                                class="flex items-center gap-3 w-full sm:w-auto"
                            >
                                <button
                                    @click.stop="openReply"
                                    class="px-4 py-2 bg-white border rounded font-medium w-full sm:w-auto text-left"
                                >
                                    Responder
                                </button>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <button
                                    @click.stop.prevent="share"
                                    class="p-2 rounded hover:bg-gray-100"
                                >
                                    🔗
                                </button>
                            </div>
                        </div>

                        <!-- Editor (substitui o card) -->
                        <div v-else class="mt-0">
                            <MarkdownEditor ref="mdEditor" v-model="replyBody">
                                <template #header-right>
                                    {{ (replyBody || "").length }}/5000
                                </template>
                            </MarkdownEditor>
                            <div
                                class="mt-2 flex items-center justify-end gap-2"
                            >
                                <button
                                    @click="cancelReply"
                                    class="px-3 py-1 text-sm"
                                >
                                    Cancelar
                                </button>
                                <button
                                    @click="submitReply"
                                    :disabled="submitting"
                                    class="px-4 py-2 bg-[#0066cc] text-white rounded text-sm"
                                >
                                    {{
                                        submitting ? "Enviando..." : "Responder"
                                    }}
                                </button>
                            </div>
                            <div
                                v-if="replyError"
                                class="text-red-600 text-sm mt-2"
                            >
                                {{ replyError }}
                            </div>
                        </div>
                    </transition>
                </div>

                <!-- Comentários removidos conforme solicitado -->
            </div>
        </div>

        <div class="mt-8 text-sm text-green-600">
            Receba por email as mais importantes Notícias de CRM do mundo!
        </div>

        <InlineToast :message="toastMessage" :visible="showToast" />
    </div>
</template>

<style scoped>
.prose img {
    max-width: 100%;
}
</style>
