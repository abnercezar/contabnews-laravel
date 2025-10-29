<template>
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-2">{{ post.title }}</h1>
        <div class="text-sm text-gray-500 mb-4">
            Por {{ post.author ? post.author : "Anônimo" }} •
            {{ formattedDate }}
        </div>
        <div class="prose prose-lg" v-html="post.content"></div>

        <section class="mt-8">
            <h2 class="text-lg font-semibold mb-2">Comentários</h2>
            <ul>
                <li
                    v-for="comment in post.comments"
                    :key="comment.id"
                    class="mb-3"
                >
                    <div class="text-sm text-gray-700">
                        <strong>{{
                            comment.user ? comment.user.name : "Anônimo"
                        }}</strong>
                    </div>
                    <div class="text-base">{{ comment.body }}</div>
                </li>
            </ul>
        </section>
    </div>
</template>

<script>
export default {
    props: {
        post: Object,
    },
    computed: {
        formattedDate() {
            if (!this.post || !this.post.created_at) return "";
            return new Date(this.post.created_at).toLocaleString();
        },
    },
};
</script>

<style scoped>
.prose img {
    max-width: 100%;
}
</style>
