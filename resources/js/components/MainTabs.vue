<script>
export default {
    name: "MainTabs",
    props: {
        activeTab: {
            type: String,
            default: "Recentes",
        },
    },
    emits: ["tab-changed"],
    methods: {
        select(tab) {
            this.$emit("tab-changed", tab);
        },
    },
};
</script>

<template>
    <nav
        class="flex flex-row items-center gap-2 ml-0 sm:ml-2 w-full sm:w-auto justify-center sm:justify-start mb-2 sm:mb-0"
    >
        <div class="flex items-center gap-2">
            <button
                v-for="tab in ['Relevantes', 'Recentes']"
                :key="tab"
                @click="select(tab)"
                :aria-pressed="activeTab === tab"
                class="px-3 py-2 text-sm font-medium focus:outline-none transition"
                :class="
                    activeTab === tab
                        ? 'text-[#daa520] border-b-2 border-[#daa520]'
                        : 'text-gray-200 hover:text-[#daa520]'
                "
            >
                {{ tab }}
            </button>
        </div>
    </nav>
</template>
