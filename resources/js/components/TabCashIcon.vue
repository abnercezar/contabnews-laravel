<script>
import { BanknotesIcon } from "@heroicons/vue/24/outline";

export default {
    name: "TabCashIcon",
    components: {
        BanknotesIcon,
    },
    // forward attributes (class) to the icon
    inheritAttrs: false,
    setup(props, { attrs }) {
        return { attrs };
    },
};
</script>

<template>
    <!-- forward any attrs (including class/text color) to the heroicon -->
    <BanknotesIcon v-bind="attrs" aria-hidden="true" />
</template>
