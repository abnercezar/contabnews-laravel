<template>
    <div class="min-h-screen bg-white flex flex-col">
        <Header />

        <main class="flex-1">
            <slot />
        </main>

        <Footer />
    </div>
</template>

<script>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";

export default {
    name: "AppLayout",
    components: {
        Header,
        Footer,
    },
};
</script>

<style>
/* layout-level styles (if any) */
</style>
