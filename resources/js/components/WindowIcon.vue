<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        :width="size"
        :height="size"
        :class="iconClass"
        :fill="fill"
        viewBox="0 0 24 24"
        aria-hidden="true"
    >
        <path
            d="M19 3H5a2 2 0 0 0-2 2v14a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V5a2 2 0 0 0-2-2zm0 2v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5h14zm-2 3H7v2h10V8zm0 4H7v2h10v-2zm-4 4H7v2h6v-2z"
        />
    </svg>
</template>

<script>
export default {
    name: "WindowIcon",
    props: {
        size: {
            type: [Number, String],
            default: 42,
        },
        fill: {
            type: String,
            default: "#daa520",
        },
        iconClass: {
            type: String,
            default:
                "min-w-[28px] min-h-[28px] sm:min-w-[42px] sm:min-h-[42px]",
        },
    },
};
</script>
