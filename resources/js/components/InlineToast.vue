<template>
    <transition name="fade">
        <div
            v-if="visible"
            class="fixed bottom-6 right-6 bg-black text-white px-4 py-2 rounded shadow-lg z-50"
        >
            {{ message }}
        </div>
    </transition>
</template>

<script>
export default {
    name: "InlineToast",
    props: {
        message: { type: String, default: "" },
        visible: { type: Boolean, default: false },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
