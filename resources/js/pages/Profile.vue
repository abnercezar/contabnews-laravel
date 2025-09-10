<script>
import Header from "../components/Header.vue";
import ConTabNewsIcon from "../components/ConTabNewsIcon.vue";
export default {
    name: "Profile",
    components: { Header, ConTabNewsIcon },
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
                        <ConTabNewsIcon
                            style="width: 28px; height: 28px; fill: #00244a"
                        />
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
                            fill="#daa520"
                            viewBox="0 0 24 24"
                            width="28"
                            height="28"
                            class="min-w-[28px] min-h-[28px] sm:min-w-[42px] sm:min-h-[42px]"
                        >
                            <path
                                d="M19 3H5a2 2 0 0 0-2 2v14a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V5a2 2 0 0 0-2-2zm0 2v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5h14zm-2 3H7v2h10V8zm0 4H7v2h10v-2zm-4 4H7v2h6v-2z"
                            ></path>
                        </svg>
                    </a>
                    © 2025 ConTabNews
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
