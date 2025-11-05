<script>
import { CurrencyDollarIcon } from "@heroicons/vue/24/outline";

export default {
    name: "TabCoinIcon",
    components: {
        CurrencyDollarIcon,
    },
    // permite que atributos (class) sejam repassados para o ícone
    inheritAttrs: false,
    setup(props, { attrs }) {
        return { attrs };
    },
};
</script>

<template>
    <!-- forward any attrs (including class/text color) to the heroicon -->
    <CurrencyDollarIcon v-bind="attrs" aria-hidden="true" />
</template>
