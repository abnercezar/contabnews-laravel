<script>
import TabCoinIcon from "./TabCoinIcon.vue";
import TabCashIcon from "./TabCashIcon.vue";

export default {
    name: "CoinsDisplay",
    components: { TabCoinIcon, TabCashIcon },
    props: {
        tabCoinCount: {
            type: [Number, String],
            default: 0,
        },
        tabCashCount: {
            type: [Number, String],
            default: 0,
        },
    },
};
</script>

<template>
    <div class="flex items-center gap-2">
        <div class="tabcoin flex items-center ml-0 sm:ml-2">
            <TabCoinIcon class="w-5 h-5 sm:w-7 sm:h-7 text-[#daa520]" />
            <span class="ml-1 text-xs sm:text-sm font-bold text-[#daa520]"
                >{{ tabCoinCount }} TabCoin</span
            >
        </div>
        <div class="tabcash flex items-center ml-0 sm:ml-2">
            <TabCashIcon class="w-5 h-5 sm:w-7 sm:h-7 text-[#daa520]" />
            <span class="ml-1 text-xs sm:text-sm font-bold text-[#daa520]"
                >{{ tabCashCount }} TabCash</span
            >
        </div>
    </div>
</template>
