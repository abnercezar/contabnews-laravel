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

<template>
    <button
        :class="[
            'nav-button px-3 sm:px-4 py-2 rounded text-sm transition w-full sm:w-auto',
            primary
                ? 'bg-[#daa520] text-white hover:bg-[#d3ad71]'
                : 'bg-[#daa520] text-white hover:bg-[#d3ad71]',
        ]"
        @click="onClick"
    >
        <slot />
    </button>
</template>
