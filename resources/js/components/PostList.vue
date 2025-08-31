<template>
    <section class="post-list pt-2">
        <ul>
            <PostItem
                v-for="(post, index) in posts"
                :key="index"
                :post="post"
                :index="index"
            />
        </ul>
    </section>
</template>
<script>
import PostItem from "./PostItem.vue";
export default {
    name: "PostList",
    components: { PostItem },
    props: {
        posts: Array,
    },
};
</script>
