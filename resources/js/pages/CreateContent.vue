<script>
import Header from "../components/Header.vue";
import MarkdownEditor from "../components/MarkdownEditor.vue";
import Modal from "../components/Modal.vue";
import ConTabNewsIcon from "../components/ConTabNewsIcon.vue";
export default {
    name: "CreateContent",
    components: { Header, MarkdownEditor, Modal },
    data() {
        return {
            form: {
                title: "",
                body: "",
                source_url: "",
                isSponsoredContent: false,
            },
            showModal: false,
            modalMessage: "",
            modalTitle: "",
        };
    },
    methods: {
        async submit() {
            try {
                const payload = {
                    title: this.form.title,
                    content: this.form.body,
                    source_url: this.form.source_url,
                    isSponsoredContent: !!this.form.isSponsoredContent,
                };
                const token = localStorage.getItem("token");
                const response = await fetch("/api/posts", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        Authorization: token ? `Bearer ${token}` : undefined,
                    },
                    body: JSON.stringify(payload),
                });
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    data = null;
                }
                if (!response.ok) {
                    let msg = "Erro ao salvar publicação.";
                    if (response.status === 422 && data && data.errors) {
                        // Tradução dos campos conhecidos
                        const fieldMap = {
                            title: "Título",
                            body: "Corpo da publicação",
                            author: "Autor",
                            content: "Conteúdo",
                        };
                        msg = Object.entries(data.errors)
                            .map(([field, arr]) =>
                                arr
                                    .map((err) =>
                                        err
                                            .replace(
                                                /The ([a-zA-Z_]+) field is required\./,
                                                (m, f) =>
                                                    `O campo "${
                                                        fieldMap[f] || f
                                                    }" é obrigatório.`
                                            )
                                            .replace(
                                                /The ([a-zA-Z_]+) field/,
                                                (m, f) =>
                                                    `O campo "${
                                                        fieldMap[f] || f
                                                    }"`
                                            )
                                    )
                                    .join(" ")
                            )
                            .join("\n");
                    } else if (data && data.message) {
                        msg = data.message
                            .replace(
                                /The ([a-zA-Z_]+) field is required\./,
                                (m, f) =>
                                    `O campo "${
                                        fieldMap[f] || f
                                    }" é obrigatório.`
                            )
                            .replace(
                                /The ([a-zA-Z_]+) field/,
                                (m, f) => `O campo "${fieldMap[f] || f}"`
                            );
                    }
                    this.modalTitle = "Erro";
                    this.modalMessage = msg;
                    this.showModal = true;
                    return;
                }
                this.modalTitle = "Sucesso";
                this.modalMessage = "Sua publicação foi criada com sucesso!";
                this.showModal = true;
                // Redirecionar ou limpar formulário
                this.form.title = "";
                this.form.body = "";
                this.form.source_url = "";
                this.form.isSponsoredContent = false;
            } catch (error) {
                this.modalTitle = "Erro";
                this.modalMessage =
                    "Ocorreu um erro inesperado ao salvar a publicação.";
                this.showModal = true;
            }
        },
        cancel() {
            // Lógica para cancelar
            this.form.title = "";
            this.form.body = "";
            this.form.source_url = "";
            this.form.isSponsoredContent = false;
        },
        closeModal() {
            this.showModal = false;
        },
    },
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <Modal
            :visible="showModal"
            :message="modalMessage"
            :title="modalTitle"
            @close="closeModal"
        />
        <Header />
        <div class="flex-1 flex flex-col justify-center items-center mt-8">
            <div class="w-full max-w-xl bg-white rounded-lg shadow-lg p-8 mb-8">
                <h1 class="text-2xl font-bold mb-6 text-center text-[#00244a]">
                    Publicar novo conteúdo
                </h1>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label
                            for="title"
                            class="block text-sm font-semibold mb-1 text-[#00244a]"
                        >
                            Título <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.title"
                            id="title"
                            type="text"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#00244a] bg-gray-50"
                            placeholder="e.g. Como os jogos de Atari eram desenvolvidos"
                            maxlength="120"
                            required
                        />
                    </div>
                    <div>
                        <label
                            for="body"
                            class="block text-sm font-semibold mb-1 text-[#00244a]"
                        >
                            Corpo da publicação
                            <span class="text-red-500">*</span>
                        </label>
                        <MarkdownEditor
                            v-model="form.body"
                            placeholder="Digite o conteúdo..."
                        />
                        <div class="text-xs text-gray-500 mt-1 text-right">
                            {{ form.body.length }}/20000
                        </div>
                        <!-- Preview removido junto com bytemd -->
                    </div>
                    <div>
                        <label
                            for="source_url"
                            class="block text-sm font-semibold mb-1 text-[#00244a]"
                            >Fonte</label
                        >
                        <input
                            v-model="form.source_url"
                            id="source_url"
                            type="text"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#00244a] bg-gray-50"
                            placeholder="https://origem.site/noticia"
                        />
                    </div>
                    <div class="flex items-center space-x-2">
                        <input
                            v-model="form.isSponsoredContent"
                            id="isSponsoredContent"
                            type="checkbox"
                            class="form-checkbox h-4 w-4 text-[#00244a]"
                        />
                        <label
                            for="isSponsoredContent"
                            class="text-sm text-[#00244a]"
                        >
                            Criar como publicação patrocinada.
                            <a
                                href="/faq#publicacao-patrocinada"
                                class="underline"
                                >Saiba mais.</a
                            >
                        </label>
                    </div>
                    <div class="text-xs text-gray-500 mb-2">
                        Serão consumidos 100 TabCash para criar a publicação
                        patrocinada.
                    </div>
                    <div class="text-xs text-gray-500 mb-2">
                        Os campos marcados com um asterisco (*) são
                        obrigatórios.
                    </div>
                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="flex-1 bg-[#00244a] text-white py-2 rounded font-bold hover:bg-[#001a33] transition"
                        >
                            Publicar
                        </button>
                        <button
                            type="button"
                            @click="cancel"
                            class="flex-1 bg-gray-200 text-gray-700 py-2 rounded font-bold hover:bg-gray-300 transition"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
            <footer
                class="w-full max-w-xl mx-auto text-center text-xs text-gray-500 py-4 border-t"
            >
                © 2025 ConTabNews &nbsp;|&nbsp;
                <a href="#" class="underline">Contato</a> &nbsp;|&nbsp;
                <a href="#" class="underline">FAQ</a> &nbsp;|&nbsp;
                <a href="#" class="underline">GitHub</a> &nbsp;|&nbsp;
                <a href="#" class="underline">Museu</a> &nbsp;|&nbsp;
                <a href="#" class="underline">RSS</a> &nbsp;|&nbsp;
                <a href="#" class="underline">Sobre</a> &nbsp;|&nbsp;
                <a href="#" class="underline">Status</a> &nbsp;|&nbsp;
                <a href="#" class="underline">Termos de Uso</a>
            </footer>
        </div>
    </div>
</template>
