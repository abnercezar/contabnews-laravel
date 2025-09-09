<template>
    <form @submit.prevent="handleSubmit">
        <div>
            <label for="title">Título</label>
            <input v-model="form.title" id="title" required />
        </div>
        <div>
            <label for="content">Conteúdo</label>
            <textarea v-model="form.content" id="content" required></textarea>
        </div>
        <div>
            <label for="source_url">Fonte</label>
            <input v-model="form.source_url" id="source_url" />
        </div>
        <div>
            <label>
                <input type="checkbox" v-model="form.isSponsoredContent" />
                Conteúdo patrocinado
            </label>
        </div>
        <button type="submit">{{ isEdit ? "Salvar" : "Publicar" }}</button>
        <button v-if="isEdit" type="button" @click="$emit('cancel')">
            Cancelar
        </button>
    </form>
</template>
<script>
export default {
    name: "PostForm",
    props: {
        post: {
            type: Object,
            default: null,
        },
    },
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
        };
    },
    computed: {
        isEdit() {
            return !!this.post;
        },
    },
    methods: {
        async handleSubmit() {
            const url = this.isEdit
                ? `/api/posts/${this.post.id}`
                : "/api/posts";
            const method = this.isEdit ? "PUT" : "POST";
            try {
                const token = localStorage.getItem("token");
                const response = await fetch(url, {
                    method,
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        ...(token ? { Authorization: "Bearer " + token } : {}),
                    },
                    body: JSON.stringify(this.form),
                });
                const data = await response.json();
                this.$emit("saved", data);
            } catch (error) {
                alert("Erro ao salvar o post");
            }
        },
    },
};
</script>
