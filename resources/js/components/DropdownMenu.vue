<script>
export default {
    name: "DropdownMenu",
    props: {
        label: { type: String, default: "Menu" },
        items: { type: Array, required: true },
    },
    data() {
        return {
            open: false,
            selected: null,
        };
    },
    methods: {
        toggleMenu() {
            this.open = !this.open;
        },
        select(item) {
            this.selected = item;
            this.$emit("select", item);
            this.open = false;
        },
    },
    mounted() {
        document.addEventListener("click", this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
    },
    methods: {
        toggleMenu() {
            this.open = !this.open;
        },
        select(item) {
            this.$emit("select", item);
            this.open = false;
        },
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.open = false;
            }
        },
    },
};
</script>

<template>
    <div class="relative inline-block w-full sm:w-auto">
        <button
            class="px-4 py-2 rounded font-bold transition bg-white text-gray-700 border border-gray-200 shadow hover:bg-[#f5f5f5] focus:outline-none"
            @click="toggleMenu"
        >
            {{ label }}
            <svg
                class="inline ml-2 w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>
        <transition name="fade">
            <ul
                v-if="open"
                class="absolute left-0 mt-2 w-full sm:w-48 bg-white border border-gray-200 rounded shadow-lg z-50"
            >
                <li
                    v-for="item in items"
                    :key="item"
                    @click="select(item)"
                    :class="[
                        'px-4 py-2 cursor-pointer font-semibold',
                        selected === item
                            ? 'bg-[#d3ad71] text-white'
                            : 'text-gray-700 hover:bg-[#daa520] hover:text-white',
                    ]"
                >
                    {{ item }}
                </li>
            </ul>
        </transition>
    </div>
</template>

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
