<template>
    <button
        :class="[
            'nav-button px-4 py-2 rounded text-sm transition',
            primary
                ? 'bg-[#daa520] text-white hover:bg-[#d3ad71]'
                : 'bg-[#00244a] border border-[#daa520] text-[#daa520] hover:bg-[#daa520] hover:text-white',
        ]"
        @click="onClick"
    >
        <slot />
    </button>
</template>
<script>
export default {
    name: "AppButton",
    props: {
        primary: {
            type: Boolean,
            default: false,
        },
        to: {
            type: String,
            default: null,
        },
    },
    methods: {
        onClick() {
            if (this.to) {
                window.location.href = this.to;
            }
            this.$emit("click");
        },
    },
};
</script>
