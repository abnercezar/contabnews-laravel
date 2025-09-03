<script>
import Header from "../components/Header.vue";
export default {
    name: "Publications",
    components: { Header },
    data() {
        return {
            isLoggedIn: false,
        };
    },
    mounted() {
        this.isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
    },
    methods: {
        goToCreateContent() {
            this.$inertia.visit("/content/create");
        },
    },
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <Header />
        <div class="flex-1 flex flex-col items-center mt-8">
            <div
                class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8 mb-8"
            >
                <h1 class="text-2xl font-bold mb-2 text-[#00244a] text-center">
                    ACA
                </h1>
                <nav class="flex justify-center mb-6">
                    <template v-if="isLoggedIn">
                        <a
                            href="/profile"
                            class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                            >Perfil</a
                        >
                    </template>
                    <a
                        href="/publications"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] bg-gray-100 rounded-t font-bold"
                        >Publicações</a
                    >
                    <a
                        href="/comments"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                        >Comentários</a
                    >
                    <a
                        href="/classifieds"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                        >Classificados</a
                    >
                </nav>
                <div class="flex flex-col items-center py-8">
                    <svg
                        class="mb-4 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        fill="currentColor"
                        viewBox="0 0 448 512"
                    >
                        <path
                            d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"
                        />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">
                        Nenhuma publicação encontrada
                    </h2>
                    <span class="text-gray-500 mb-4"
                        >Você ainda não fez nenhuma publicação.</span
                    >
                    <button
                        @click="goToCreateContent"
                        class="bg-[#00244a] text-white px-4 py-2 rounded font-bold hover:bg-[#001a33] transition flex items-center gap-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path
                                d="M7.75 2a.75.75 0 0 1 .75.75V7h4.25a.75.75 0 0 1 0 1.5H8.5v4.25a.75.75 0 0 1-1.5 0V8.5H2.75a.75.75 0 0 1 0-1.5H7V2.75A.75.75 0 0 1 7.75 2Z"
                            />
                        </svg>
                        Publicar conteúdo
                    </button>
                </div>
            </div>
            <footer
                class="w-full max-w-2xl mx-auto text-center text-xs text-gray-500 py-4 border-t flex flex-col items-center"
            >
                <div class="flex items-center gap-2 mb-2">
                    <a href="/" aria-label="Página inicial" class="inline-block"
                        ><svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="26"
                            height="26"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M18.9999 4C20.6568 4 21.9999 5.34315 21.9999 7V17C21.9999 18.6569 20.6568 20 18.9999 20H4.99994C3.34308 20 1.99994 18.6569 1.99994 17V7C1.99994 5.34315 3.34308 4 4.99994 4H18.9999ZM19.9999 9.62479H13C12.4478 9.62479 11.8442 9.20507 11.652 8.68732L10.6542 6H4.99994C4.44765 6 3.99994 6.44772 3.99994 7V17C3.99994 17.5523 4.44765 18 4.99994 18H18.9999C19.5522 18 19.9999 17.5523 19.9999 17V9.62479Z"
                            /></svg
                    ></a>
                    © 2025 TabNews
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <a href="/contato" class="underline">Contato</a>
                    <a href="/faq" class="underline">FAQ</a>
                    <a href="" class="underline">GitHub</a>
                    <a href="/museu" class="underline">Museu</a>
                    <a href="/recentes/rss" class="underline">RSS</a>
                    <a href="" class="underline">Sobre</a>
                    <a href="/status" class="underline">Status</a>
                    <a href="/termos-de-uso" class="underline">Termos de Uso</a>
                </div>
            </footer>
        </div>
    </div>
</template>
