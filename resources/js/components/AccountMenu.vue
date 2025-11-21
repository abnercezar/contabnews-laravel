<script>
import HamburgerIcon from "./HamburgerIcon.vue";
import { useAuthStore } from "../stores/auth";

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
            auth: null,
        };
    },
    methods: {
        toggle() {
            this.showHamburgerMenu = !this.showHamburgerMenu;
        },
        select(item) {
            // inicializa a store de auth de forma preguiçosa
            if (!this.auth) {
                try {
                    this.auth = useAuthStore();
                } catch (e) {
                    this.auth = null;
                }
            }

            if (item.label === "Deslogar") {
                if (this.auth) this.auth.logout();
                this.showHamburgerMenu = false;
                try {
                    if (
                        this.$inertia &&
                        typeof this.$inertia.visit === "function"
                    ) {
                        this.$inertia.visit("/");
                    } else {
                        window.location.href = "/";
                    }
                } catch (e) {
                    window.location.href = "/";
                }
                return;
            }

            if (item.label === "Novo conteúdo") {
                this.showHamburgerMenu = false;
                const isLogged = !!(this.auth && this.auth.token);
                if (!isLogged) {
                    try {
                        localStorage.setItem("intendedPath", "/content/create");
                    } catch (e) {}
                    try {
                        if (
                            this.$inertia &&
                            typeof this.$inertia.visit === "function"
                        ) {
                            this.$inertia.visit("/login");
                        } else {
                            window.location.href = "/login";
                        }
                    } catch (e) {
                        window.location.href = "/login";
                    }
                    return;
                }
                try {
                    if (
                        this.$inertia &&
                        typeof this.$inertia.visit === "function"
                    ) {
                        this.$inertia.visit("/content/create");
                    } else {
                        window.location.href = "/content/create";
                    }
                } catch (e) {
                    window.location.href = "/content/create";
                }
                return;
            }

            if (item.label === "Meus conteúdos") {
                this.showHamburgerMenu = false;
                const isLogged = !!(this.auth && this.auth.token);
                if (!isLogged) {
                    try {
                        localStorage.setItem(
                            "intendedPath",
                            "/publications?mine=1"
                        );
                    } catch (e) {}
                    try {
                        if (
                            this.$inertia &&
                            typeof this.$inertia.visit === "function"
                        ) {
                            this.$inertia.visit("/login");
                        } else {
                            window.location.href = "/login";
                        }
                    } catch (e) {
                        window.location.href = "/login";
                    }
                    return;
                }
                // navega para a lista de publicações filtrada para o usuário atual (client-side quando possível)
                try {
                    if (
                        this.$inertia &&
                        typeof this.$inertia.visit === "function"
                    ) {
                        this.$inertia.visit("/publications?mine=1");
                    } else {
                        window.location.href = "/publications?mine=1";
                    }
                } catch (e) {
                    window.location.href = "/publications?mine=1";
                }
                return;
            }

            this.showHamburgerMenu = false;
        },
    },
    mounted() {
        try {
            this.auth = useAuthStore();
        } catch (e) {}
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
