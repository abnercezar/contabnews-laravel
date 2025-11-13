<template>
    <div :class="filter === 'Todos' ? 'text-center' : 'text-left'">
        <!-- Lista genérica: muda conforme a aba -->
        <div v-if="filter === 'Todos'" class="space-y-6">
            <div
                v-for="(item, index) in allItems"
                :key="index"
                class="border-b border-gray-200 pb-4"
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

                    <!-- Contribuidor -->
                    <p class="text-sm text-gray-500 mt-1">
                        Contribuindo com
                        <span class="text-blue-600"
                            >@{{ item.contributor }}</span
                        >
                    </p>

                    <!-- Conteúdo do comentário -->
                    <p class="text-gray-700 mt-2 italic">
                        "{{ item.comment }}"
                    </p>

                    <!-- Rodapé com tabcoins, comentários, autor e tempo (left-aligned dentro da mesma caixa) -->
                    <div
                        class="text-sm text-gray-500 mt-2 flex items-center gap-2 flex-wrap"
                    >
                        <span>{{ item.tabcoins }} tabcoin</span>
                        <span>·</span>
                        <span>{{ item.comments }} comentário</span>
                        <span>·</span>
                        <span>{{ item.author }}</span>
                        <span>·</span>
                        <span>{{ item.time }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outras abas (ex: Publicações, Comentários, etc) -->
        <div v-else class="space-y-4">
            <div
                v-for="(post, index) in posts"
                :key="index"
                class="border-b border-gray-200 pb-3"
            >
                <div class="mx-auto max-w-2xl text-left">
                    <a
                        :href="itemUrl(post)"
                        class="block text-base font-semibold text-gray-900 hover:text-blue-600"
                        @click="handleOpen(post, $event)"
                    >
                        {{ post.title }}
                    </a>
                    <div
                        class="text-sm text-gray-500 mt-1 flex items-center gap-2 flex-wrap"
                    >
                        <span>{{ post.coin }}</span>
                        <span>·</span>
                        <span>{{ post.comments }}</span>
                        <span>·</span>
                        <span>{{ post.author }}</span>
                        <span>·</span>
                        <span>{{ post.time }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        posts: Array,
        filter: String,
    },
    methods: {
        itemUrl(item) {
            const id =
                item?.id ??
                item?.post_id ??
                (item?.post && item.post.id) ??
                null;
            return id ? `/content/${id}` : "#";
        },

        handleOpen(item, event) {
            console.log("[PostList] handleOpen item:", item);
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
            } else {
                console.warn("[PostList] item has no id, cannot open:", item);
                // no id -> allow native navigation (href may point to external)
            }
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
        };
    },
};
</script>
