<template>
    <div :class="filter === 'Todos' ? 'text-center' : 'text-left'">
        <!-- Lista genérica: muda conforme a aba -->
        <div v-if="filter === 'Todos'" class="space-y-2">
            <div
                v-for="(item, index) in itemsToRender"
                :key="index"
                class="pb-1"
            >
                <div class="mx-auto max-w-2xl text-left">
                    <!-- Título (linkável) -->
                    <a
                        :href="itemUrl(item)"
                        class="block text-base font-semibold text-gray-900 hover:text-blue-600"
                        @click="handleOpen(item, $event)"
                    >
                        {{ item.title }}
                    </a>

                    <!-- Conteúdo do comentário -->
                    <!-- Rodapé com tabcoins, autor e tempo (comentários removidos conforme solicitado) -->
                    <div
                        class="text-sm text-gray-500 mt-2 flex items-center gap-2 flex-wrap"
                    >
                        <span>{{ item.tabcoins }} tabcoin</span>
                        <span>·</span>
                        <span v-if="authorLabel(item)">{{
                            authorLabel(item)
                        }}</span>
                        <span v-if="authorLabel(item)">·</span>
                        <span>{{ item.time }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outras abas (ex: Publicações, Comentários, etc) -->
        <div v-else class="space-y-1">
            <div v-for="(post, index) in posts" :key="index" class="pb-1 relative">
                <div class="mx-auto max-w-2xl text-left">
                    <div class="flex items-start justify-between">
                        <a
                            :href="itemUrl(post)"
                            class="block text-base font-semibold text-gray-900 hover:text-blue-600"
                            @click="handleOpen(post, $event)"
                        >
                            {{ post.title }}
                        </a>

                        <!-- menu moved to Content page (three-dots now shown in content view) -->
                    </div>

                    <div
                        class="text-sm text-gray-500 mt-1 flex items-center gap-2 flex-wrap"
                    >
                        <span>{{ tabcoinsLabel(post) }}</span>
                        <span>·</span>
                        <span>{{ authorLabel(post) }}</span>
                        <span>·</span>
                        <span>{{ timeLabel(post) }}</span>
                        <!-- Badge visual para posts patrocinados -->
                        <span
                            v-if="isSponsored(post)"
                            class="ml-2 inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded-full"
                            >Anúncio</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- edit modal moved to Content page -->
    </div>
</template>

<script>
import axios from "axios";
import { usePostsStore } from "../stores/posts";
export default {
    props: {
        posts: Array,
        filter: String,
    },
    methods: {
        itemUrl(item) {
            // If item looks like a comment (has post_id or post.id), link to parent post with anchor
            const postId =
                item?.post_id ??
                item?.postId ??
                item?.post?.id ??
                item?.raw?.post_id ??
                null;
            if (postId) return `/content/${postId}#comment-${item?.id ?? ""}`;

            const id =
                item?.id ??
                item?.post_id ??
                (item?.post && item.post.id) ??
                null;
            return id ? `/content/${id}` : "#";
        },

        async handleOpen(item, event) {
            console.log("[PostList] handleOpen item:", item);

            // If this is a comment-like object, navigate to the parent post with anchor
            let postId =
                item?.post_id ??
                item?.postId ??
                item?.post?.id ??
                item?.raw?.post_id ??
                null;

            // If we don't have a postId but have an item id (likely a comment id), try fetching it
            if (!postId && item && item.id) {
                try {
                    const resp = await axios.get(`/api/comments/${item.id}`);
                    const comment = resp.data;
                    postId =
                        comment?.post_id ??
                        comment?.postId ??
                        comment?.post?.id ??
                        comment?.raw?.post_id ??
                        null;
                } catch (e) {
                    // ignore
                    console.debug(
                        "[PostList] could not fetch comment to resolve postId",
                        e
                    );
                }
            }

            if (postId) {
                const url = `/content/${postId}#comment-${item?.id}`;
                this.$emit("open", { url, postId, commentId: item?.id });
                if (event && typeof event.preventDefault === "function")
                    event.preventDefault();
                return;
            }

            // Otherwise treat as a direct post-like item
            const id =
                item?.id ??
                item?.post_id ??
                (item?.post && item.post.id) ??
                null;
            if (id) {
                console.log("[PostList] emitting open with id", id);
                this.$emit("open", id);
                if (event && typeof event.preventDefault === "function")
                    event.preventDefault();
                return;
            }

            console.warn("[PostList] item has no id, cannot open:", item);
            // no id -> allow native navigation (href may point to external)
        },
        tabcoinsLabel(post) {
            const n =
                post?.coin ??
                post?.tabcoins ??
                post?.coins ??
                post?.tabcash ??
                0;
            const num = Number(n) || 0;
            return `${num} ${num === 1 ? "tabcoin" : "tabcoins"}`;
        },
        commentsLabel(post) {
            let n = 0;
            if (Array.isArray(post?.comments)) n = post.comments.length;
            else if (typeof post?.comments === "number") n = post.comments;
            else if (typeof post?.comments_count === "number")
                n = post.comments_count;
            else if (typeof post?.commentsCount === "number")
                n = post.commentsCount;
            const plural = n === 1 ? "comentário" : "comentários";
            return `${n} ${plural}`;
        },
        authorLabel(post) {
            return (
                post?.author ||
                post?.user?.name ||
                post?.contributor ||
                post?.author_name ||
                ""
            );
        },
        timeLabel(post) {
            if (post?.time) return post.time;
            const date = post?.created_at || post?.createdAt || post?.time;
            if (!date) return "";
            try {
                const then = new Date(date);
                const diff = Math.floor((Date.now() - then.getTime()) / 1000);
                if (diff < 5) return "Agora";
                if (diff < 60) return `${diff} segundos atrás`;
                const minutes = Math.floor(diff / 60);
                if (minutes < 60)
                    return `${minutes} ${
                        minutes === 1 ? "minuto" : "minutos"
                    } atrás`;
                const hours = Math.floor(minutes / 60);
                if (hours < 24)
                    return `${hours} ${hours === 1 ? "hora" : "horas"} atrás`;
                const days = Math.floor(hours / 24);
                return `${days} ${days === 1 ? "dia" : "dias"} atrás`;
            } catch (e) {
                return "";
            }
        },
        isSponsored(post) {
            if (!post) return false;
            // use flag normalizado quando disponível
            if (typeof post.__isSponsored !== "undefined")
                return !!post.__isSponsored;
            return !!(
                post?.isSponsoredContent ||
                post?.isSponsored ||
                post?.is_sponsored ||
                post?.is_sponsored_content ||
                post?.sponsored ||
                post?.sponsored_at ||
                post?.sponsor_id ||
                post?.sponsored_until
            );
        },
        // UI helpers remain in PostList but editing/deleting happens in Content page
    },
    computed: {
        itemsToRender() {
            // Quando houver posts reais (passados via prop), use-os.
            // Caso contrário, caia para os dados de exemplo `allItems`.
            if (this.filter === "Todos") {
                return this.posts && this.posts.length
                    ? this.posts
                    : this.allItems;
            }
            return this.posts || [];
        },
    },
    data() {
        return {
            allItems: [
                {
                    id: 1001,
                    title: "Pitch: Templates com traduções em três línguas e plano gratuito. En...",
                    contributor: "jaedsonpys",
                    comment:
                        "Com 5 segundos de Google, eu encontrei isso aqui: https://canaltech.com.br/windows/8-programas-para-encontrar-arquivos-duplicados-no-windows/",
                    tabcoins: 1,
                    comments: 0,
                    author: "user1",
                    time: "13 minutos atrás",
                },
                {
                    id: 1002,
                    title: "Resuming: a plataforma open source de currículos para desenvolvedores",
                    contributor: "dealmeida",
                    comment: "",
                    tabcoins: 1,
                    comments: 0,
                    author: "davidsantana06",
                    time: "22 minutos atrás",
                },
                {
                    id: 1003,
                    title: "OpenAI lança GPT-5.1",
                    contributor: "NewsletterOficial",
                    comment:
                        "a real é q o problema n é a ia nem o mercado é a crença q tem q se adaptar pra continuar no jogo...",
                    tabcoins: 1,
                    comments: 0,
                    author: "dealmeida",
                    time: "29 minutos atrás",
                },
            ],
            // UI state (no menu/modals here; actions handled in Content view)
        };
    },
};
</script>
