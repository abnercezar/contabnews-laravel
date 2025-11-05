<script>
import MarkdownEditor from "../components/MarkdownEditor.vue";
import Footer from "../components/Footer.vue";
import Viewer from "viewerjs";
import "viewerjs/dist/viewer.css";

export default {
    name: "Classifieds",
    components: { MarkdownEditor, Footer },
    data() {
        return {
            classifiedContent: "",
        };
    },
    mounted() {
        // Exemplo de inicialização do Viewer, se necessário
        // const gallery = this.$refs.gallery;
        // se (gallery) this.viewer = new Viewer(gallery);
    },
    methods: {
        goToCreateContent() {
            const isLogged =
                typeof window !== "undefined" &&
                !!localStorage.getItem("token");
            if (!isLogged) {
                try {
                    localStorage.setItem("intendedPath", "/content/create");
                } catch (e) {}
                window.location.href = "/login";
                return;
            }
            if (this.$inertia && typeof this.$inertia.visit === "function") {
                this.$inertia.visit("/content/create");
            } else {
                window.location.href = "/content/create";
            }
        },
    },
};
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col">
        <div class="flex-1 flex flex-col items-center mt-8">
            <div class="w-full max-w-4xl p-8 mb-8 mx-4 sm:mx-auto">
                <!-- Editor e contador dentro do card -->
                <MarkdownEditor
                    v-model="classifiedContent"
                    placeholder="Digite seu conteúdo classificado..."
                    :maxlength="20000"
                />
                <div class="text-xs text-gray-500 mt-1 text-right">
                    {{ (classifiedContent || "").length }}/20000
                </div>
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
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-t transition"
                        >Comentários</a
                    >
                    <a
                        href="/classifieds"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-t font-bold"
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
                        Nenhum classificado encontrado
                    </h2>
                    <span class="text-gray-500 mb-4"
                        >Você não possui anúncios publicados.</span
                    >
                    <button
                        @click="goToCreateContent"
                        class="bg-[#daa520] text-white px-4 py-2 rounded font-bold hover:bg-[#d3ad71] transition flex items-center gap-2"
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
                        Publicar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
