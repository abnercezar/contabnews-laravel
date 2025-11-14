<template>
    <div
        v-if="visible"
        class="fixed inset-0 z-40 flex items-center justify-center"
    >
        <div
            class="absolute inset-0 bg-black opacity-50"
            @click="onCancel"
        ></div>
        <div
            class="relative bg-white rounded-lg shadow-lg w-full max-w-lg mx-4"
            role="dialog"
            aria-modal="true"
        >
            <header
                class="flex items-center justify-between px-4 py-3 border-b"
            >
                <h3 class="text-lg font-semibold text-gray-800">{{ title }}</h3>
                <button
                    v-if="showClose"
                    @click="onCancel"
                    class="text-gray-500 hover:text-gray-700"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </header>

            <div class="px-4 py-4 text-sm text-gray-700">
                <slot>
                    <div v-if="message" v-html="message"></div>
                </slot>
            </div>

            <footer
                class="flex items-center justify-end gap-2 px-4 py-3 border-t"
            >
                <button
                    @click="onCancel"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200"
                >
                    {{ cancelText }}
                </button>
                <button
                    @click="onConfirm"
                    :class="['px-4 py-2 rounded font-medium', confirmClass]"
                    :disabled="loading"
                >
                    <span v-if="!loading">{{ confirmText }}</span>
                    <span v-else>Carregando...</span>
                </button>
            </footer>
        </div>
    </div>
</template>

<script>
export default {
    name: "Modal",
    props: {
        visible: { type: Boolean, default: false },
        title: { type: String, default: "" },
        message: { type: String, default: "" },
        confirmText: { type: String, default: "Confirmar" },
        cancelText: { type: String, default: "Cancelar" },
        confirmClass: {
            type: String,
            default: "bg-[#d3ad71] text-white hover:bg-[#bfae76]",
        },
        showClose: { type: Boolean, default: true },
        loading: { type: Boolean, default: false },
    },
    emits: ["close", "confirm", "cancel"],
    methods: {
        onCancel() {
            this.$emit("cancel");
            this.$emit("close");
        },
        onConfirm() {
            this.$emit("confirm");
        },
    },
};
</script>

<style scoped>
/* Pequenos estilos de fallback caso Tailwind não esteja ativo */
.bg-black {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
