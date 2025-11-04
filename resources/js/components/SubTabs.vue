<script>
export default {
    name: "SubTabs",
    props: {
        subTabs: {
            type: Array,
            default: () => [
                "Publicações",
                "Comentários",
                "Classificados",
                "Todos",
            ],
        },
        activeSubTab: {
            type: String,
            default: () =>
                (typeof window !== "undefined" &&
                    localStorage.getItem("activeSubTab")) ||
                "Publicações",
        },
    },
    emits: ["subtab-changed"],
    methods: {
        selectSubTab(sub) {
            try {
                localStorage.setItem("activeSubTab", sub);
            } catch (e) {}
            this.$emit("subtab-changed", sub);
            const routeMap = {
                Publicações: "/publications",
                Comentários: "/comments",
                Classificados: "/classifieds",
                Todos: "/",
            };
            const target = routeMap[sub];
            if (target) {
                try {
                    if (
                        this.$inertia &&
                        typeof this.$inertia.visit === "function"
                    ) {
                        this.$inertia.visit(target);
                    } else {
                        window.location.href = target;
                    }
                } catch (e) {
                    window.location.href = target;
                }
            }
        },
    },
};
</script>

<template>
    <div class="bg-transparent mt-4">
        <div class="container mx-auto px-4">
            <div class="border-b border-gray-200">
                <div class="flex justify-center -mb-px space-x-2">
                    <button
                        v-for="sub in subTabs"
                        :key="sub"
                        @click="selectSubTab(sub)"
                        class="px-4 py-2 text-sm font-medium transition"
                        :class="
                            activeSubTab === sub
                                ? 'bg-white text-gray-700 border border-gray-200 border-b-0 rounded-t-md -mb-1'
                                : 'text-gray-600 hover:text-gray-800'
                        "
                    >
                        {{ sub }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
