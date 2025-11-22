<template>
    <form @submit.prevent="handleSubmit" class="w-full">
        <div>
            <label for="title">Título</label>
            <input v-model="form.title" id="title" required />
            <div v-if="errors.title" class="field-error">
                {{ errors.title[0] }}
            </div>
        </div>
        <div>
            <label for="content">Conteúdo</label>
            <MarkdownEditor
                v-model="form.content"
                placeholder="Digite o conteúdo..."
            >
                <template #header-right>
                    {{ (form.content || "").length }}/5000
                </template>
            </MarkdownEditor>
            <div v-if="errors.content" class="field-error">
                {{ errors.content[0] }}
            </div>
        </div>
        <div>
            <label for="source_url">Fonte</label>
            <input v-model="form.source_url" id="source_url" />
            <div v-if="errors.source_url" class="field-error">
                {{ errors.source_url[0] }}
            </div>
        </div>
        <div>
            <label>
                <input type="checkbox" v-model="form.isSponsoredContent" />
                Conteúdo patrocinado
            </label>
        </div>
        <div class="mt-3">
            <button
                type="submit"
                class="px-4 py-2 bg-[#d3ad71] text-white rounded hover:bg-[#bfae76]"
            >
                {{ isEdit ? "Salvar" : "Publicar" }}
            </button>
            <button v-if="isEdit" type="button" @click="$emit('cancel')">
                Cancelar
            </button>
        </div>
        <div v-if="formError" class="field-error mt-2">{{ formError }}</div>
    </form>
</template>
<script>
import MarkdownEditor from "./MarkdownEditor.vue";
export default {
    name: "PostForm",
    props: {
        post: {
            type: Object,
            default: null,
        },
    },
    components: { MarkdownEditor },
    data() {
        return {
            form: {
                title: this.post ? this.post.title : "",
                content: this.post ? this.post.content : "",
                source_url: this.post ? this.post.source_url : "",
                isSponsoredContent: this.post
                    ? this.post.isSponsoredContent
                    : false,
            },
            errors: {},
            formError: "",
        };
    },
    computed: {
        isEdit() {
            return !!this.post;
        },
    },
    methods: {
        async handleSubmit() {
            this.formError = "";
            this.errors = {};
            const url = this.isEdit
                ? `/api/posts/${this.post.id}`
                : "/api/posts";
            const method = this.isEdit ? "PUT" : "POST";
            try {
                const token = localStorage.getItem("token");
                const headers = {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                };
                if (token) headers.Authorization = "Bearer " + token;
                const response = await fetch(url, {
                    method,
                    headers,
                    body: JSON.stringify(this.form),
                });
                const data = await response.json();
                if (!response.ok) {
                    // Laravel-style validation errors
                    if (data && data.errors) {
                        this.errors = data.errors;
                    } else {
                        this.formError =
                            data.message || "Erro ao salvar o post";
                    }
                    return;
                }
                this.$emit("saved", data);
            } catch (error) {
                this.formError = "Erro de rede ao salvar o post.";
            }
        },
    },
};
</script>

<style scoped>
.field-error {
    color: #b91c1c;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>
