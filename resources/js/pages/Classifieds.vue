<script>
// usando AppLayout como layout padrão (definido em app.js)
import PostList from "../components/PostList.vue";
import { useClassifiedsStore } from "../stores/classifieds";

export default {
    name: "Classifieds",
    components: { PostList },
    data() {
        return {
            loading: false,
        };
    },
    computed: {
        classifieds() {
            const store = useClassifiedsStore();
            return store.classifieds;
        },
    },
    created() {
        const store = useClassifiedsStore();
        store.fetchClassifieds();
    },
    methods: {
        openClassified(id) {
            const url = id ? `/content/${id}` : "#";
            try {
                if (this.$inertia && typeof this.$inertia.visit === "function")
                    this.$inertia.visit(url);
                else window.location.href = url;
            } catch (e) {
                window.location.href = url;
            }
        },
    },
};
</script>

<template>
    <div class="mt-8">
        <!-- Lista de classificados -->
        <div class="flex flex-col gap-2">
            <a
                v-for="(classified, index) in classifieds"
                :key="index"
                href="#"
                @click.prevent="openClassified(classified.id)"
                class="block pb-1 hover:bg-gray-50 transition-colors duration-150 cursor-pointer group"
            >
                <p
                    class="text-gray-900 font-medium text-base group-hover:text-blue-700 group-hover:underline"
                >
                    {{ classified.title }}
                </p>
                <div class="flex flex-wrap gap-2 text-xs text-gray-500 mt-2">
                    <span class="text-gray-700">Patrocinado</span>
                    <span>·</span>
                    <span>
                        {{ classified.comments }}
                        {{
                            classified.comments === 1
                                ? "comentário"
                                : "comentários"
                        }}
                    </span>
                    <span>·</span>
                    <span>{{ classified.username }}</span>
                    <span>·</span>
                    <span>{{ classified.time }}</span>
                </div>
            </a>
        </div>
    </div>
</template>
