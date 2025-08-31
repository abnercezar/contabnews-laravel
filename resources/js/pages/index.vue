<script>
import Header from "../components/Header.vue";
import PostList from "../components/PostList.vue";
import Footer from "../components/Footer.vue";

export default {
    name: "Index",
    components: {
        Header,
        PostList,
        Footer,
    },
    data() {
        return {
            posts: [
                {
                    title: "Como declarar imposto de renda em 2025?",
                    coin: "30 tabcoins",
                    comments: "5 comentários",
                    author: "ContadorExpert",
                    time: "3 horas atrás",
                },
                {
                    title: "Principais mudanças na legislação tributária este ano",
                    coin: "25 tabcoins",
                    comments: "8 comentários",
                    author: "FiscalNews",
                    time: "1 dia atrás",
                },
                {
                    title: "Dicas para organizar a contabilidade da sua empresa",
                    coin: "18 tabcoins",
                    comments: "3 comentários",
                    author: "GestorFinanceiro",
                    time: "6 horas atrás",
                },
                {
                    title: "Como funciona o Simples Nacional?",
                    coin: "20 tabcoins",
                    comments: "10 comentários",
                    author: "ContabFácil",
                    time: "12 horas atrás",
                },
                {
                    title: "Erros comuns ao emitir notas fiscais",
                    coin: "15 tabcoins",
                    comments: "4 comentários",
                    author: "NotaFiscalPro",
                    time: "2 dias atrás",
                },
                {
                    title: "O que é eSocial e como ele impacta sua empresa?",
                    coin: "22 tabcoins",
                    comments: "6 comentários",
                    author: "RHContábil",
                    time: "1 dia atrás",
                },
                {
                    title: "Planejamento tributário: como pagar menos impostos legalmente",
                    coin: "28 tabcoins",
                    comments: "7 comentários",
                    author: "TributárioMaster",
                    time: "5 horas atrás",
                },
                {
                    title: "Entenda a diferença entre lucro real e lucro presumido",
                    coin: "12 tabcoins",
                    comments: "2 comentários",
                    author: "FinanceiroSimples",
                    time: "9 horas atrás",
                },
                {
                    title: "Como evitar multas fiscais na sua empresa",
                    coin: "16 tabcoins",
                    comments: "5 comentários",
                    author: "AuditorFiscal",
                    time: "8 horas atrás",
                },
                {
                    title: "As principais tendências contábeis para 2025",
                    coin: "35 tabcoins",
                    comments: "12 comentários",
                    author: "ContabTrends",
                    time: "1 dia atrás",
                },
            ],
        };
    },
};
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <Header />
        <main class="main-content flex-1">
            <div class="container mx-auto px-4">
                <PostList :posts="posts" />
            </div>
        </main>
        <Footer />
    </div>
</template>

<style scoped>
/* Tailwind CSS está sendo usado para estilização */
</style>
