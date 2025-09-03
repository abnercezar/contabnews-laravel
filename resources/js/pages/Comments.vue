<script>
import Header from "../components/Header.vue";
export default {
    name: "Comments",
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
                <nav class="flex justify-center mb-6">
                    <a
                        href="/profile"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                        >Perfil</a
                    >
                    <a
                        href="/publications"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                        >Publicações</a
                    >
                    <a
                        href="/comments"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] bg-gray-100 rounded-t font-bold"
                        >Comentários 2 (2)</a
                    >
                    <a
                        href="/classifieds"
                        class="px-4 py-2 text-sm font-medium text-[#00244a] hover:bg-gray-100 rounded-t transition"
                        >Classificados</a
                    >
                </nav>
                <div class="flex flex-col gap-6">
                    <div
                        class="bg-gray-50 rounded-lg p-6 shadow flex flex-col gap-2"
                    >
                        <p class="text-gray-800 text-base">
                            "Seu projeto está muito bom, Dimitri, parabéns! Se
                            não for incômodo, poderia compartilhar como você
                            planeja lidar com os custos do projeto?
                            Especialmente se o número de usuários crescer
                            bastante. Estou desenvolvendo algo em outra área e
                            também tenho ess..."
                        </p>
                        <div
                            class="flex flex-wrap gap-2 text-xs text-gray-500 mt-2"
                        >
                            <span class="font-semibold text-[#00244a]"
                                >1 tabcoin</span
                            >
                            <span>·</span>
                            <span>1 comentário</span>
                            <span>·</span>
                            <span>ACA</span>
                            <span>·</span>
                            <span>9 meses atrás</span>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 rounded-lg p-6 shadow flex flex-col gap-2"
                    >
                        <p class="text-gray-800 text-base">
                            "Slimdev12, parabéns pela sua humildade em buscar
                            orientação! Só o fato de pensar assim já o coloca à
                            frente de muitos jovens da sua idade. É inspirador
                            ver que você já está planejando seu futuro. Desejo
                            que sua jornada seja próspera e que você alcance..."
                        </p>
                        <div
                            class="flex flex-wrap gap-2 text-xs text-gray-500 mt-2"
                        >
                            <span class="font-semibold text-[#00244a]"
                                >1 tabcoin</span
                            >
                            <span>·</span>
                            <span>0 comentário</span>
                            <span>·</span>
                            <span>ACA</span>
                            <span>·</span>
                            <span>9 meses atrás</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-8">
                    <button
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold"
                    >
                        Anterior
                    </button>
                    <button
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold"
                    >
                        Próximo
                    </button>
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
                    © 2025 ContabNews
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
