<script>
import Header from "../components/Header.vue";
export default {
    name: "Profile",
    components: { Header },
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
                    <a
                        href="/profile"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] bg-gray-100 rounded-t font-bold"
                        >Perfil</a
                    >
                    <a
                        href="/publications"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
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
                    <h2
                        class="text-2xl font-bold text-[#00244a] mb-6 flex items-center gap-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="28"
                            height="28"
                            fill="#00244a"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"
                            />
                        </svg>
                        Perfil
                    </h2>
                    <div class="flex flex-col items-center gap-4 mb-4 w-full">
                        <div
                            class="flex items-center gap-2 bg-blue-50 rounded-lg px-4 py-2 w-full max-w-xs justify-between shadow"
                        >
                            <span
                                class="text-blue-900 font-bold text-lg flex items-center gap-1"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    fill="#1e40af"
                                    viewBox="0 0 24 24"
                                >
                                    <circle cx="12" cy="12" r="10" />
                                    <text
                                        x="12"
                                        y="16"
                                        text-anchor="middle"
                                        font-size="12"
                                        fill="#fff"
                                    >
                                        TC
                                    </text>
                                </svg>
                                0 TabCoin
                            </span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-green-50 rounded-lg px-4 py-2 w-full max-w-xs justify-between shadow"
                        >
                            <span
                                class="text-green-900 font-bold text-lg flex items-center gap-1"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    fill="#22c55e"
                                    viewBox="0 0 24 24"
                                >
                                    <rect
                                        x="4"
                                        y="4"
                                        width="16"
                                        height="16"
                                        rx="4"
                                    />
                                    <text
                                        x="12"
                                        y="16"
                                        text-anchor="middle"
                                        font-size="12"
                                        fill="#fff"
                                    >
                                        TC$
                                    </text>
                                </svg>
                                0 TabCash
                            </span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-gray-100 rounded-lg px-4 py-2 w-full max-w-xs justify-between shadow"
                        >
                            <span
                                class="text-gray-700 font-semibold text-base flex items-center gap-1"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    fill="#64748b"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M12 8v4l3 2" />
                                </svg>
                                Membro há 11 meses
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <footer
                class="w-full max-w-2xl mx-auto text-center text-xs text-gray-500 py-4 border-t flex flex-col items-center"
            >
                <div class="flex items-center gap-2 mb-2">
                    <a
                        href="/"
                        aria-label="Página inicial"
                        class="inline-block"
                    >
                        <svg
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
                            />
                        </svg>
                    </a>
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
