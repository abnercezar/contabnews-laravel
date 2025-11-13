<script>
import PostForm from "./PostForm.vue";
import PostItem from "./PostItem.vue";

export default {
    name: "PostList",
    components: { PostForm, PostItem },
    props: {
        posts: {
            type: Array,
            default: () => [],
        },
        start: {
            type: Number,
            default: 1,
        },
        showActions: {
            type: Boolean,
            default: true,
        },
        filter: {
            type: String,
            default: null,
        },
        // Quando true, o componente controla sua própria paginação
        paginate: {
            type: Boolean,
            default: false,
        },
        initialPage: {
            type: Number,
            default: 1,
        },
        perPage: {
            type: Number,
            default: 20,
        },
    },
    data() {
        return {
            fetchedPosts: [],
            showForm: false,
            editPost: null,
            currentUser: null, // Preencha com o usuário logado
            page: this.initialPage,
            loading: false,
            loadingMore: false,
            hasMore: false,
        };
    },
    created() {
        // se o pai já passou posts, usa-os; caso contrário, busca
        if (!this.posts || this.posts.length === 0) {
            this.fetchPosts({ reset: true });
        } else {
            // ainda tenta popular currentUser
            this.fetchCurrentUser();
        }
    },
    methods: {
        async fetchPosts({ reset = false } = {}) {
            // se o pai passou posts, não busca (a menos que paginate esteja ativo)
            if (!this.paginate && this.posts && this.posts.length) {
                return;
            }

            if (reset) {
                this.page = this.initialPage;
                this.fetchedPosts = [];
                this.hasMore = false;
            }

            this.loading = reset || this.loading === false;
            this.loadingMore = !reset && this.page > this.initialPage;

            try {
                const res = await fetch(
                    `/api/posts?page=${this.page}&per_page=${this.perPage}`,
                    {
                        headers: { Accept: "application/json" },
                    }
                );
                if (!res.ok) {
                    // tenta fallback para array vazio
                    return;
                }
                const json = await res.json();
                const dataArray = Array.isArray(json) ? json : json.data ?? [];

                const items = Array.isArray(dataArray) ? dataArray : [];

                // acrescenta evitando duplicatas
                items.forEach((p) => {
                    const exists = this.fetchedPosts.some(
                        (x) => x.id && p.id && x.id === p.id
                    );
                    if (!exists) this.fetchedPosts.push(p);
                });

                // inferir hasMore a partir de meta ou tamanho retornado
                if (json.meta) {
                    if (typeof json.meta.has_more !== "undefined") {
                        this.hasMore = !!json.meta.has_more;
                    } else if (
                        typeof json.meta.current_page !== "undefined" &&
                        typeof json.meta.last_page !== "undefined"
                    ) {
                        this.hasMore =
                            json.meta.current_page < json.meta.last_page;
                    } else {
                        this.hasMore = items.length === this.perPage;
                    }
                } else {
                    this.hasMore = items.length === this.perPage;
                }
            } catch (e) {
                // silent fail
                // console.error(e);
            } finally {
                this.loading = false;
                this.loadingMore = false;
            }
        },

        async loadMore() {
            if (this.loadingMore || !this.hasMore) return;
            this.page++;
            this.loadingMore = true;
            await this.fetchPosts();
            this.loadingMore = false;
        },

        async fetchCurrentUser() {
            try {
                const res = await fetch("/api/user", {
                    headers: { Accept: "application/json" },
                });
                if (!res.ok) return;
                const json = await res.json();
                // aceita { data: user } ou user puro
                this.currentUser = json.data ?? json;
            } catch (e) {
                // ignore
            }
        },

        onSaved(post) {
            // quando um post é salvo via PostForm: atualiza lista local sem refazer fetch completo
            try {
                // se recebe resource wrapper
                const saved = post.data ?? post;
                // atualiza item se já existir
                const idx = this.fetchedPosts.findIndex(
                    (p) => p.id && saved.id && p.id === saved.id
                );
                if (idx !== -1) {
                    this.$set(this.fetchedPosts, idx, saved);
                } else {
                    // adiciona no topo
                    this.fetchedPosts.unshift(saved);
                }
            } catch (e) {
                // fallback: refetch
                this.fetchPosts({ reset: true });
            } finally {
                this.showForm = false;
                this.editPost = null;
            }
        },

        editPostHandler(post) {
            this.editPost = post;
            this.showForm = true;
        },

        cancelEdit() {
            this.showForm = false;
            this.editPost = null;
        },

        onDeleted(post) {
            // se passado o post deletado, remove; caso contrário refetch
            try {
                const id = post && (post.id ?? null);
                if (id) {
                    this.fetchedPosts = this.fetchedPosts.filter(
                        (p) => !(p.id && p.id === id)
                    );
                    return;
                }
            } catch (e) {
                // ignore
            }
            // fallback
            this.fetchPosts({ reset: true });
        },

        // reemite eventos 'open' vindos de PostItem (útil caso PostItem emita em vez de navegar)
        onOpenFromItem(id) {
            this.$emit("open", id);
        },
    },
    computed: {
        displayedPosts() {
            // origem: prefere a prop posts passada pelo pai, caso contrário fetchedPosts
            const source =
                this.posts && this.posts.length
                    ? this.posts
                    : this.fetchedPosts;

            if (!this.filter || this.filter === "Todos") return source;

            // Se os itens tiverem o campo 'type', use-o. Caso contrário, aplica heurística simples.
            const map = {
                Publicações: (p) => !p.type || p.type === "post",
                Comentários: (p) => p.type === "comment" || !!p.comments,
                Classificados: (p) =>
                    p.type === "classified" || p.category === "classified",
            };
            const fn = map[this.filter];
            if (!fn) return source;
            return source.filter(fn);
        },
    },
};
</script>

<template>
    <section class="post-list pt-2 px-2 sm:px-0">
        <PostForm
            v-if="showForm"
            :post="editPost"
            @saved="onSaved"
            @cancel="cancelEdit"
        />
        <ul>
            <PostItem
                v-for="(post, index) in displayedPosts"
                :key="post.id || index"
                :post="post"
                :index="index + start"
                :currentUser="currentUser"
                :show-actions="showActions"
                @edit="editPostHandler"
                @deleted="onDeleted"
                @open="onOpenFromItem"
            />
        </ul>

        <!-- botão de ver mais (quando o componente controla paginação) -->
        <div v-if="paginate" class="text-center mt-6">
            <button
                v-if="hasMore && !loadingMore"
                @click="loadMore"
                class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded text-sm"
            >
                Ver mais
            </button>
            <div v-else-if="loadingMore" class="text-gray-500 text-sm py-2">
                Carregando...
            </div>
        </div>
    </section>
</template>

<style scoped>
/* ajustes locais se necessário */
</style>
