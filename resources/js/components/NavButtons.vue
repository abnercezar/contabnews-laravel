<script>
import AppButton from "./AppButton.vue";

export default {
    name: "NavButtons",
    components: {
        AppButton,
    },
};
</script>

<template>
    <div class="nav-items flex flex-wrap gap-2 sm:gap-4 w-full">
        <AppButton primary :to="'/publicacoes'">Publicações</AppButton>
        <AppButton primary :to="'/comentarios'">Comentários</AppButton>
        <AppButton primary :to="'/classificados'">Classificados</AppButton>
        <AppButton primary :to="'/todos'">Todos</AppButton>
    </div>
</template>
