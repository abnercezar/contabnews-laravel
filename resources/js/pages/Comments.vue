<script>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
export default {
    name: "Comments",
    components: { Header, Footer },
};
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col">
        <Header />
        <div class="flex-1 flex flex-col items-center mt-8">
            <div class="w-full max-w-4xl p-8 mb-8 mx-4 sm:mx-auto">
                <nav class="flex justify-center mb-6">
                    <a
                        href="/profile"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-t transition"
                        >Perfil</a
                    >
                    <a
                        href="/publications"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-t transition"
                        >Publicações</a
                    >
                    <a
                        href="/comments"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-t font-bold"
                        >Comentários 2 (2)</a
                    >
                    <a
                        href="/classifieds"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-t transition"
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
                            <span class="font-semibold text-gray-700"
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
                            <span class="font-semibold text-gray-700"
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
        </div>
        <Footer />
    </div>
</template>
