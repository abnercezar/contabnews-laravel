<template>
    <div
        v-if="visible"
        class="fixed inset-0 z-50 flex items-center justify-center"
    >
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
            <h2 v-if="title" class="text-lg font-bold mb-2 text-gray-700">
                {{ title }}
            </h2>
            <div class="mb-4 text-gray-700">{{ message }}</div>
            <button
                @click="close"
                class="w-full py-2 bg-[#daa520] text-white rounded font-bold hover:bg-[#d3ad71] transition"
            >
                OK
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Modal",
    props: {
        visible: { type: Boolean, required: true },
        message: { type: String, required: true },
        title: { type: String, default: "" },
    },
    emits: ["close"],
    methods: {
        close() {
            this.$emit("close");
        },
    },
};
</script>

<style scoped>
/* Adicione estilos extras se necessário */
</style>
