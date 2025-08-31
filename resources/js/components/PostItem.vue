<template>
    <li class="post-item py-3 border-b border-gray-200">
        <template v-if="index === 0">
            <a
                href="#"
                class="post-ad text-green-600 font-bold text-sm mb-2 block"
                >Anúncio: Clique aqui para saber mais!</a
            >
        </template>
        <p class="post-title text-lg font-bold text-gray-800 mb-2">
            {{ index + 1 }}. {{ post.title }}
        </p>
        <div class="post-info text-sm text-gray-500 flex gap-4 flex-wrap">
            <span class="post-coin text-[#daa520]">{{ post.coin }}</span>
            <span class="post-comments">· {{ post.comments }}</span>
            <span class="post-author">· {{ post.author }}</span>
            <span class="post-time italic">· {{ post.time }}</span>
        </div>
    </li>
</template>
<script>
export default {
    name: "PostItem",
    props: {
        post: Object,
        index: Number,
    },
};
</script>
