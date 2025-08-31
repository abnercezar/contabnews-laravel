<template>
    <div class="nav-items flex gap-4">
        <AppButton primary :to="'/publicacoes'">Publicações</AppButton>
        <AppButton primary :to="'/comentarios'">Comentários</AppButton>
        <AppButton primary :to="'/classificados'">Classificados</AppButton>
        <AppButton primary :to="'/todos'">Todos</AppButton>
    </div>
</template>
<script>
import AppButton from "./AppButton.vue";

export default {
    name: "NavButtons",
    components: {
        AppButton,
    },
};
</script>
