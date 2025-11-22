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
        shortLabel(tab) {
            const map = {
                Relevantes: "Rel",
                Recentes: "Rec",
            };
            return map[tab] || tab;
        },
    },
};
</script>

<template>
    <nav
        class="flex flex-row items-center gap-2 ml-0 sm:ml-2 w-full sm:w-auto justify-center sm:justify-start mb-2 sm:mb-0 sm:overflow-visible overflow-x-auto whitespace-nowrap"
        aria-label="Main tabs"
    >
        <div class="inline-flex items-center gap-2">
            <button
                v-for="tab in ['Relevantes', 'Recentes']"
                :key="tab"
                @click="select(tab)"
                :aria-pressed="activeTab === tab"
                class="inline-block whitespace-nowrap px-3 py-2 text-sm font-medium focus:outline-none transition"
                :class="
                    activeTab === tab
                        ? 'text-[#daa520] border-b-2 border-[#daa520]'
                        : 'text-gray-400 hover:text-[#daa520]'
                "
            >
                <span class="hidden sm:inline">{{ tab }}</span>
                <span class="inline sm:hidden">{{ shortLabel(tab) }}</span>
            </button>
        </div>
    </nav>
</template>
