<script>
import SearchIcon from "./SearchIcon.vue";

export default {
    name: "SearchInput",
    components: { SearchIcon },
    props: {
        placeholder: {
            type: String,
            default: "Pesquisar...",
        },
    },
};
</script>

<template>
    <div class="relative flex items-center w-full sm:w-72">
        <input
            type="text"
            :placeholder="placeholder"
            class="search-input px-3 py-2 rounded border border-[#daa520] text-sm text-[#daa520] placeholder:text-[#daa520] focus:border-[#daa520] w-full sm:w-72 max-w-full"
        />
        <span class="absolute right-2 text-[#daa520] pointer-events-none">
            <SearchIcon />
        </span>
    </div>
</template>
