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
                    {{ post.tabcoins ?? 0 }}
                </div>
                <button
                    @click.prevent="vote('down')"
                    class="text-red-500 hover:text-red-700"
                >
                    ▼
                </button>
            </div>

            <div class="flex-1">
                <h1 class="text-4xl font-extrabold mb-2 leading-tight">
                    {{ post.title }}
                </h1>
                <div class="text-sm text-gray-500 mb-4">
                    Por {{ post.author ? post.author : "Anônimo" }} •
                    {{ formattedDate }}
                </div>

                <div class="prose prose-lg mb-6" v-html="post.content"></div>

                <div v-if="post.source_url" class="mb-6 text-sm">
                    <a
                        :href="post.source_url"
                        target="_blank"
                        class="text-blue-600 hover:underline"
                        >Fonte: {{ post.source_url }}</a
                    >
                </div>

                <!-- Reply card (collapsed) -->
                <div class="mt-4">
                    <div
                        @click="openReply"
                        class="reply-card cursor-pointer rounded border border-gray-200 bg-white p-4 flex items-center justify-between"
                    >
                        <div class="flex items-center gap-3">
                            <button
                                @click.stop="openReply"
                                class="px-4 py-2 bg-white border rounded font-medium"
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

                    <transition name="fade">
                        <div
                            v-if="showReply"
                            class="mt-4 border border-gray-200 rounded p-4 bg-white"
                        >
                            <textarea
                                v-model="replyBody"
                                rows="4"
                                class="w-full p-2 border rounded"
                                placeholder="Escreva sua resposta..."
                            ></textarea>
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

                <section class="mt-8">
                    <h2 class="text-lg font-semibold mb-2">Comentários</h2>
                    <ul>
                        <li
                            v-for="comment in post.comments"
                            :key="comment.id"
                            class="mb-3"
                        >
                            <div class="text-sm text-gray-700">
                                <strong>{{
                                    comment.user ? comment.user.name : "Anônimo"
                                }}</strong>
                            </div>
                            <div class="text-base">{{ comment.body }}</div>
                        </li>
                    </ul>
                </section>
            </div>
        </div>

        <div class="mt-8 text-sm text-green-600">
            Receba por email as mais importantes Notícias de Tecnologia do
            mundo!
        </div>
    </div>
</template>

<script>
export default {
    props: {
        post: Object,
    },
    data() {
        return {
            showReply: false,
            replyBody: "",
            submitting: false,
            replyError: "",
        };
    },
    computed: {
        formattedDate() {
            if (!this.post || !this.post.created_at) return "";
            return new Date(this.post.created_at).toLocaleString();
        },
    },
    methods: {
        openReply() {
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
                this.showReply = true;
                this.$nextTick(() => {
                    const ta = this.$el.querySelector("textarea");
                    if (ta) ta.focus();
                });
            } catch (e) {
                this.showReply = true;
            }
        },
        cancelReply() {
            this.showReply = false;
            this.replyBody = "";
            this.replyError = "";
        },
        async submitReply() {
            this.replyError = "";
            if (!this.replyBody || !this.replyBody.trim()) {
                this.replyError = "Digite sua resposta antes de enviar.";
                return;
            }
            const intended = `/content/create?replyTo=${this.post.id}`;
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
                        post_id: this.post.id,
                        body: this.replyBody,
                    }),
                });
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    this.replyError =
                        data.message || "Erro ao enviar comentário.";
                    return;
                }
                const comment = await res.json();
                if (!this.post.comments) this.post.comments = [];
                this.post.comments.unshift(comment);
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
                    navigator.share({ title: this.post.title, url });
                } else {
                    navigator.clipboard.writeText(url);
                    alert("Link copiado para a área de transferência");
                }
            } catch (e) {
                // fallback
                try {
                    navigator.clipboard.writeText(window.location.href);
                } catch (e) {}
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
                if (!res.ok) return;
                const data = await res.json();
                this.$set(this.post, "tabcoins", data.tabcoins);
                this.$set(this.post, "tabcashs", data.tabcashs);
            } catch (e) {
                console.error(e);
            }
        },
    },
};
</script>

<style scoped>
.prose img {
    max-width: 100%;
}
</style>
