<script>
import AppButton from "./AppButton.vue";
import NavButtons from "./NavButtons.vue";
import SearchIcon from "./SearchIcon.vue";
import PlusIcon from "./PlusIcon.vue";
import HamburgerIcon from "./HamburgerIcon.vue";
import TabCoinIcon from "./TabCoinIcon.vue";

export default {
    name: "Header",
    components: {
        AppButton,
        NavButtons,
        SearchIcon,
        PlusIcon,
        HamburgerIcon,
        TabCoinIcon,
    },
    data() {
        return {
            isLoggedIn: true, // Troque para true para simular usuário logado
        };
    },
};
</script>
<template>
    <header class="bg-[#00244a] text-white py-4 shadow-md min-h-[80px]">
        <div
            class="container mx-auto flex items-center px-4 gap-8 justify-center"
        >
            <div class="flex items-center gap-8 justify-center flex-1">
                <h1
                    class="logo flex items-center text-3xl font-bold mr-8 ml-4 whitespace-nowrap"
                >
                    <span class="mr-4">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="#daa520"
                            viewBox="0 0 24 24"
                            width="42"
                            height="42"
                        >
                            <path
                                d="M19 3H5a2 2 0 0 0-2 2v14a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V5a2 2 0 0 0-2-2zm0 2v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5h14zm-2 3H7v2h10V8zm0 4H7v2h10v-2zm-4 4H7v2h6v-2z"
                            />
                        </svg>
                    </span>
                    <span>Contab News</span>
                </h1>
                <NavButtons />
            </div>
            <div class="flex items-center gap-6 justify-center flex-1">
                <div class="relative flex items-center">
                    <input
                        type="text"
                        class="search-input px-3 py-2 rounded border border-gray-300 text-sm focus:border-[#ff7b00]"
                        placeholder="Pesquisar..."
                    />
                    <span
                        class="absolute right-2 text-gray-500 pointer-events-none"
                    >
                        <SearchIcon />
                    </span>
                </div>
                <template v-if="isLoggedIn">
                    <div class="plus-icon text-xl cursor-pointer">
                        <PlusIcon />
                    </div>
                    <div class="tabcoin flex items-center ml-2">
                        <TabCoinIcon />
                        <span class="ml-1 text-xs font-bold text-blue-700"
                            >0 TabCoin</span
                        >
                    </div>
                    <div class="tabcash flex items-center ml-2">
                        <TabCoinIcon color="#daa520" />
                        <span class="ml-1 text-xs font-bold text-yellow-600"
                            >0 TabCash</span
                        >
                    </div>
                    <div class="hamburger text-xl cursor-pointer ml-4 mr-4">
                        <HamburgerIcon />
                    </div>
                </template>
            </div>
            <template v-if="!isLoggedIn">
                <div class="flex gap-4 justify-center flex-1">
                    <AppButton :to="'/login'">Login</AppButton>
                    <AppButton :to="'/register'">Cadastrar</AppButton>
                    <AppButton :to="'/login'">Login</AppButton>
                    <AppButton :to="'/register'">Cadastrar</AppButton>
                </div>
            </template>
        </div>
    </header>
</template>
