<script>
import AppButton from "./AppButton.vue";
import NavButtons from "./NavButtons.vue";
import SearchIcon from "./SearchIcon.vue";
import HamburgerIcon from "./HamburgerIcon.vue";
import TabCoinIcon from "./TabCoinIcon.vue";
import TabCashIcon from "./TabCashIcon.vue";
import SearchInput from "./SearchInput.vue";
import CoinsDisplay from "./CoinsDisplay.vue";
import AccountMenu from "./AccountMenu.vue";
import MainTabs from "./MainTabs.vue";
import SubTabs from "./SubTabs.vue";
import DropdownMenu from "./DropdownMenu.vue";
import ConTabNewsIcon from "./ConTabNewsIcon.vue"; // Importando o novo ícone ConTabNews
// use official Heroicons Window icon
import { WindowIcon } from "@heroicons/vue/24/outline";
import icons from "./icons.js";

export default {
    name: "Header",
    components: {
        AppButton,
        NavButtons,
        SearchIcon,
        HamburgerIcon,
        TabCoinIcon,
        TabCashIcon,
        SearchInput,
        CoinsDisplay,
        AccountMenu,
        MainTabs,
        SubTabs,
        DropdownMenu,
        ConTabNewsIcon,
        WindowIcon,
    },
    props: {
        activeTab: {
            type: String,
            default: "Recentes",
        },
    },
    emits: ["tab-changed"],
    data() {
        return {
            isLoggedIn: false,
            subTabs: ["Publicações", "Comentários", "Classificados", "Todos"],
            activeSubTab:
                (typeof window !== "undefined" &&
                    localStorage.getItem("activeSubTab")) ||
                "Publicações",
            hamburgerItems: [
                { label: "ACA", icon: icons.ACA },
                { label: "Novo conteúdo", icon: icons["Novo conteúdo"] },
                { label: "Meus conteúdos", icon: icons["Meus conteúdos"] },
                { label: "Editar perfil", icon: icons["Editar perfil"] },
                { label: "Auto", icon: icons.Auto },
                { label: "Deslogar", icon: icons.Deslogar },
            ],
            windowWidth:
                typeof window !== "undefined" ? window.innerWidth : 1024,
        };
    },
    methods: {
        selectTab(tab) {
            this.$emit("tab-changed", tab);
        },
        selectSubTab(subTab) {
            this.activeSubTab = subTab;
            try {
                localStorage.setItem("activeSubTab", subTab);
            } catch (e) {}
            this.$emit("subtab-changed", subTab);
        },
        updateWindowWidth() {
            this.windowWidth = window.innerWidth;
        },
    },
    mounted() {
        this.isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
        window.addEventListener("resize", this.updateWindowWidth);
        this.windowWidth = window.innerWidth;
    },
    beforeUnmount() {
        window.removeEventListener("resize", this.updateWindowWidth);
    },
};
</script>

<template>
    <header class="bg-[#3c3c3c] text-white py-2 shadow-md min-h-[50px]">
        <div
            class="container mx-auto flex flex-col sm:flex-row items-center px-2 sm:px-4 gap-2 sm:gap-4 justify-start w-full"
        >
            <h1
                class="logo flex items-center text-lg sm:text-xl font-bold mr-2 ml-0 pl-0 whitespace-nowrap mb-2 sm:mb-0"
            >
                <span class="mr-2">
                    <WindowIcon
                        :class="
                            windowWidth < 640
                                ? 'w-7 h-7 text-[#daa520]'
                                : 'w-10 h-10 text-[#daa520]'
                        "
                        aria-hidden="true"
                    />
                </span>
                <span class="text-base sm:text-lg text-[#daa520]"
                    >Tabnews de CRM</span
                >
            </h1>

            <MainTabs :activeTab="activeTab" @tab-changed="selectTab" />
            <div
                v-if="isLoggedIn"
                class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 flex-wrap flex-1 justify-end w-full sm:w-auto"
            >
                <SearchInput />
                <CoinsDisplay :tabCoinCount="0" :tabCashCount="0" />
                <AccountMenu :items="hamburgerItems" />
            </div>
            <div
                v-else
                class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 flex-wrap flex-1 justify-end w-full sm:w-auto"
            >
                <div class="relative flex items-center w-full sm:w-72">
                    <input
                        type="text"
                        class="search-input px-3 py-2 rounded border border-gray-300 text-sm focus:border-[#ff7b00] w-full sm:w-72 max-w-full"
                        placeholder="Pesquisar..."
                    />
                    <span
                        class="absolute right-2 text-gray-500 pointer-events-none"
                    >
                        <SearchIcon />
                    </span>
                </div>
                <AppButton :to="'/login'" class="w-full sm:w-auto"
                    >Login</AppButton
                >
                <AppButton :to="'/register'" class="w-full sm:w-auto"
                    >Cadastrar</AppButton
                >
            </div>
        </div>
    </header>
    <SubTabs
        :subTabs="subTabs"
        :activeSubTab="activeSubTab"
        @subtab-changed="selectSubTab"
    />
</template>
