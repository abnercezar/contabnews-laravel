<template>
    <div class="min-h-screen bg-white flex flex-col">
        <main class="flex-1">
            <div class="w-full max-w-4xl mx-auto px-4 py-8">
                <!-- Centraliza todo o conteúdo dentro do layout e aplica fonte leve por padrão -->
                <div class="flex flex-col text-center font-light">
                    <slot></slot>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    name: "StandardLayout",
};
</script>

<style scoped>
/* layout padrão utilizado por várias páginas */
</style>
