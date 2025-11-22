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

<template>
    <div class="min-h-screen bg-white flex flex-col">
        <Header />

        <main class="flex-1">
            <div class="w-full max-w-4xl mx-auto px-4 py-8">
                <div class="text-left">
                    <slot />
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>

<style>
/* layout-level styles (if any) */
</style>
