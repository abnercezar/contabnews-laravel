<script>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
import ConTabNewsIcon from "../components/ConTabNewsIcon.vue";
export default {
    name: "Profile",
    components: { Header, ConTabNewsIcon, Footer },
};
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col">
        <Header />
        <div class="flex-1 flex flex-col items-center mt-8">
            <div class="w-full max-w-4xl p-8 mb-8 mx-4 sm:mx-auto">
                <h1 class="text-2xl font-bold mb-2 text-gray-700 text-center">
                    ACA
                </h1>
                <nav class="flex justify-center mb-6">
                    <a
                        href="/profile"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-t font-bold"
                        >Perfil</a
                    >
                    <a
                        href="/publications"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-t transition"
                        >Publicações</a
                    >
                    <a
                        href="/comments"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-t transition"
                        >Comentários</a
                    >
                    <a
                        href="/classifieds"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-t transition"
                        >Classificados</a
                    >
                </nav>
                <div class="flex flex-col items-center py-8">
                    <h2
                        class="text-2xl font-bold text-gray-700 mb-6 flex items-center gap-2"
                    >
                        <ConTabNewsIcon
                            style="width: 28px; height: 28px; fill: #374151"
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
        </div>
        <Footer />
    </div>
</template>
