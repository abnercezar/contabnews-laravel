<script>
import AppButton from "./AppButton.vue";
import NavButtons from "./NavButtons.vue";
import SearchIcon from "./SearchIcon.vue";
import HamburgerIcon from "./HamburgerIcon.vue";
import TabCoinIcon from "./TabCoinIcon.vue";
import DropdownMenu from "./DropdownMenu.vue";
import ConTabNewsIcon from "./ConTabNewsIcon.vue"; // Importando o novo ícone ConTabNews
// Ícones SVG simples para cada item
const icons = {
    ACA: `<svg class='inline w-5 h-5 mr-2' fill='none' stroke='#daa520' viewBox='0 0 24 24'><path d='M3 12l9-9 9 9v8a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-4H9v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z'/></svg>`, // Casa
    "Novo conteúdo": `<svg class='inline w-5 h-5 mr-2' fill='none' stroke='#daa520' viewBox='0 0 24 24'><path d='M12 5v14m7-7H5' stroke-width='2' stroke-linecap='round'/></svg>`, // Adicionar
    "Meus conteúdos": `<svg class='inline w-5 h-5 mr-2' fill='none' stroke='#daa520' viewBox='0 0 24 24'><rect x='4' y='4' width='16' height='16' rx='2'/><path d='M8 9h8M8 13h6' stroke-width='2' stroke-linecap='round'/></svg>`, // Lista
    "Editar perfil": `<svg class='inline w-5 h-5 mr-2' fill='none' stroke='#daa520' viewBox='0 0 24 24'><circle cx='12' cy='8' r='4'/><path d='M4 20v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2' stroke-width='2'/></svg>`, // Usuário
    Auto: `<svg class='inline w-5 h-5 mr-2' fill='none' stroke='#daa520' viewBox='0 0 24 24'><circle cx='12' cy='12' r='3'/><path d='M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09A1.65 1.65 0 0 0 12 3a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09c0 .66.26 1.3.73 1.77z' stroke-width='2'/></svg>`, // Configurações
    Deslogar: `<svg class='inline w-5 h-5 mr-2' fill='none' stroke='#daa520' viewBox='0 0 24 24'><path d='M17 16l4-4m0 0l-4-4m4 4H7' stroke-width='2' stroke-linecap='round'/></svg>`, // Sair
};

export default {
    name: "Header",
    components: {
        AppButton,
        NavButtons,
        SearchIcon,
        HamburgerIcon,
        TabCoinIcon,
        DropdownMenu,
        ConTabNewsIcon, // Adicionando o novo ícone ao componente
    },
    data() {
        return {
            isLoggedIn: false,
            activeTab: "ConTabNews",
            showSubHeader: false,
            subTabs: ["Publicações", "Comentários", "Classificados", "Todos"],
            activeSubTab: "Publicações",
            showHamburgerMenu: false,
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
            this.activeTab = tab;
            this.showSubHeader = tab === "Recentes";
        },
        selectSubTab(subTab) {
            this.activeSubTab = subTab;
            // Mapeamento de sub-tabs para rotas
            const routeMap = {
                Publicações: "/publications",
                Comentários: "/comments",
                Classificados: "/classifieds",
                Todos: "/",
            };
            const target = routeMap[subTab];
            if (target) {
                // Preferir Inertia para navegação SPA quando disponível
                try {
                    if (
                        this.$inertia &&
                        typeof this.$inertia.visit === "function"
                    ) {
                        this.$inertia.visit(target);
                    } else {
                        window.location.href = target;
                    }
                } catch (e) {
                    window.location.href = target;
                }
            }
        },
        toggleHamburgerMenu() {
            this.showHamburgerMenu = !this.showHamburgerMenu;
        },
        selectHamburgerItem(item) {
            if (item.label === "Deslogar") {
                localStorage.removeItem("isLoggedIn");
                this.isLoggedIn = false;
                this.showHamburgerMenu = false;
                window.location.href = "/";
            } else if (item.label === "Novo conteúdo") {
                this.showHamburgerMenu = false;
                window.location.href = "/content/create";
            } else if (item.label === "Meus conteúdos") {
                this.showHamburgerMenu = false;
                window.location.href = "/publications";
            } else {
                // Outras ações de menu
                this.showHamburgerMenu = false;
            }
        },
        updateWindowWidth() {
            this.windowWidth = window.innerWidth;
        },
    },
    mounted() {
        // Verifica login persistente
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
    <header class="bg-[#00244a] text-white py-4 shadow-md min-h-[80px]">
        <div
            class="container mx-auto flex flex-col sm:flex-row items-center px-2 sm:px-4 gap-2 sm:gap-4 justify-start w-full"
        >
            <h1
                class="logo flex items-center text-xl sm:text-3xl font-bold mr-2 ml-2 whitespace-nowrap mb-2 sm:mb-0"
            >
                <span class="mr-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="#daa520"
                        viewBox="0 0 24 24"
                        :width="windowWidth < 640 ? 28 : 42"
                        :height="windowWidth < 640 ? 28 : 42"
                        class="min-w-[28px] min-h-[28px] sm:min-w-[42px] sm:min-h-[42px]"
                    >
                        <path
                            d="M19 3H5a2 2 0 0 0-2 2v14a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V5a2 2 0 0 0-2-2zm0 2v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5h14zm-2 3H7v2h10V8zm0 4H7v2h10v-2zm-4 4H7v2h6v-2z"
                        />
                    </svg>
                </span>
                <span>Tabnews de CRM</span>
            </h1>
            <!-- Abas principais -->
            <nav
                class="flex flex-row flex-wrap gap-1 ml-0 sm:ml-2 w-full sm:w-auto justify-center sm:justify-start mb-2 sm:mb-0"
            >
                <AppButton
                    v-for="tab in ['Relevantes']"
                    :key="tab"
                    :primary="true"
                    :class="
                        activeTab === tab
                            ? 'bg-[#d3ad71] text-white border-2 border-[#daa520]'
                            : ''
                    "
                    @click="selectTab(tab)"
                >
                    {{ tab }}
                </AppButton>
                <DropdownMenu
                    label="Recentes"
                    :items="subTabs"
                    @select="selectSubTab"
                    :class="
                        activeTab === 'Recentes'
                            ? 'bg-[#d3ad71] text-white border-2 border-[#daa520]'
                            : ''
                    "
                />
            </nav>
            <!-- Subheader para aba Recentes -->
            <div
                v-if="showSubHeader"
                class="flex flex-wrap gap-2 justify-center mt-2 w-full"
            >
                <button
                    v-for="subTab in subTabs"
                    :key="subTab"
                    :class="[
                        'px-3 py-1 rounded text-sm font-semibold transition',
                        activeSubTab === subTab
                            ? 'bg-[#ff7b00] text-white'
                            : 'bg-white text-[#00244a] hover:bg-[#ffe0b2]',
                    ]"
                    @click="selectSubTab(subTab)"
                >
                    {{ subTab }}
                </button>
            </div>
            <div
                v-if="isLoggedIn"
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

                <div class="tabcoin flex items-center ml-0 sm:ml-2">
                    <TabCoinIcon class="w-5 h-5 sm:w-7 sm:h-7" />
                    <span
                        class="ml-1 text-xs sm:text-sm font-bold text-blue-700"
                        >0 TabCoin</span
                    >
                </div>
                <div class="tabcash flex items-center ml-0 sm:ml-2">
                    <TabCoinIcon
                        color="#daa520"
                        class="w-5 h-5 sm:w-7 sm:h-7"
                    />
                    <span
                        class="ml-1 text-xs sm:text-sm font-bold text-yellow-600"
                        >0 TabCash</span
                    >
                </div>
                <div class="relative">
                    <div
                        class="hamburger text-lg sm:text-xl cursor-pointer ml-0 sm:ml-4 mr-0 sm:mr-4"
                        @click="toggleHamburgerMenu"
                    >
                        <HamburgerIcon class="w-6 h-6 sm:w-8 sm:h-8" />
                    </div>
                    <transition name="fade">
                        <ul
                            v-if="showHamburgerMenu"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50"
                        >
                            <li
                                v-for="item in hamburgerItems"
                                :key="item.label"
                                @click="selectHamburgerItem(item)"
                                class="px-4 py-2 cursor-pointer font-semibold flex items-center text-[#00244a] hover:bg-[#daa520] hover:text-white"
                            >
                                <span v-html="item.icon"></span>
                                <span>{{ item.label }}</span>
                            </li>
                        </ul>
                    </transition>
                </div>
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
            <!-- Removido bloco duplicado de login/cadastrar -->
        </div>
    </header>
</template>
