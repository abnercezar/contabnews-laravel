<script>
import HamburgerIcon from "./HamburgerIcon.vue";

export default {
    name: "AccountMenu",
    components: { HamburgerIcon },
    props: {
        items: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            showHamburgerMenu: false,
        };
    },
    methods: {
        toggle() {
            this.showHamburgerMenu = !this.showHamburgerMenu;
        },
        select(item) {
            if (item.label === "Deslogar") {
                try {
                    localStorage.removeItem("isLoggedIn");
                } catch (e) {}
                this.showHamburgerMenu = false;
                window.location.href = "/";
            } else if (item.label === "Novo conteúdo") {
                this.showHamburgerMenu = false;
                window.location.href = "/content/create";
            } else if (item.label === "Meus conteúdos") {
                this.showHamburgerMenu = false;
                window.location.href = "/publications";
            } else {
                this.showHamburgerMenu = false;
            }
        },
    },
};
</script>

<template>
    <div class="relative">
        <div
            class="hamburger text-lg sm:text-xl cursor-pointer ml-0 sm:ml-4 mr-0 sm:mr-4"
            @click="toggle"
        >
            <HamburgerIcon class="w-6 h-6 sm:w-8 sm:h-8 text-[#daa520]" />
        </div>
        <transition name="fade">
            <ul
                v-if="showHamburgerMenu"
                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50"
            >
                <li
                    v-for="item in items"
                    :key="item.label"
                    @click="select(item)"
                    class="px-4 py-2 cursor-pointer font-semibold flex items-center text-gray-700 hover:bg-[#daa520] hover:text-white"
                >
                    <component
                        :is="item.icon"
                        class="inline w-5 h-5 mr-2 text-[#daa520]"
                    />
                    <span class="ml-2">{{ item.label }}</span>
                </li>
            </ul>
        </transition>
    </div>
</template>
