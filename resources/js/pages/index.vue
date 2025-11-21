<script>
import PostList from "../components/PostList.vue";

export default {
    name: "Index",
    components: {
        PostList,
    },
    data() {
        return {
            posts: [
                {
                    id: 2001,
                    title: "Como declarar imposto de renda em 2025?",
                    coin: "30 tabcoins",
                    comments: "5 comentários",
                    author: "ContadorExpert",
                    time: "3 horas atrás",
                },
                {
                    id: 2002,
                    title: "Principais mudanças na legislação tributária este ano",
                    coin: "25 tabcoins",
                    comments: "8 comentários",
                    author: "FiscalNews",
                    time: "1 dia atrás",
                },
                {
                    id: 2003,
                    title: "Dicas para organizar a contabilidade da sua empresa",
                    coin: "18 tabcoins",
                    comments: "3 comentários",
                    author: "GestorFinanceiro",
                    time: "6 horas atrás",
                },
                {
                    id: 2004,
                    title: "Como funciona o Simples Nacional?",
                    coin: "20 tabcoins",
                    comments: "10 comentários",
                    author: "ContabFácil",
                    time: "12 horas atrás",
                },
                {
                    id: 2005,
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
            activeTab: "Recentes",
            subTabs: ["Publicações", "Comentários", "Classificados", "Todos"],
            activeSubTab: "Publicações",
        };
    },
    mounted() {
        try {
            const saved = localStorage.getItem("activeSubTab");
            if (saved) this.activeSubTab = saved;
        } catch (e) {
            // ignora
        }
    },
    methods: {
        selectTab(tab) {
            this.activeTab = tab;
        },
        selectSubTab(subTab) {
            this.activeSubTab = subTab;
            const routeMap = {
                Publicações: "/publications",
                Comentários: "/comments",
                Classificados: "/classifieds",
                Todos: "/",
            };
            const target = routeMap[subTab];
            if (target) {
                try {
                    if (
                        this.$inertia &&
                        typeof this.$inertia.visit === "function"
                    ) {
                        this.$inertia.visit(target);
                    } else {
                        window.location.href = target;
                    }
                } catch (e) {
                    window.location.href = target;
                }
            }
        },
        onHeaderSubtabChanged(sub) {
            this.activeSubTab = sub;
        },
        openPost(payload) {
            console.log("[Index] openPost", payload);
            if (!payload) return;

            // If PostList emitted an object with a precomputed URL (comment case), use it
            if (payload && typeof payload === "object" && payload.url) {
                const url = payload.url;
                try {
                    if (
                        this.$inertia &&
                        typeof this.$inertia.visit === "function"
                    )
                        this.$inertia.visit(url);
                    else window.location.href = url;
                } catch (e) {
                    window.location.href = url;
                }
                return;
            }

            // Otherwise payload is an id (number|string) or an object with postId
            const id =
                payload && typeof payload === "object"
                    ? payload.postId ?? null
                    : payload;
            if (!id) return;
            const url = `/content/${id}`;
            try {
                if (this.$inertia && typeof this.$inertia.visit === "function")
                    this.$inertia.visit(url);
                else window.location.href = url;
            } catch (e) {
                window.location.href = url;
            }
        },
    },
};
</script>

<template>
    <div class="mt-8">
        <!-- Sub-abas agora são renderizadas pelo Header (fixas abaixo do header) -->

        <PostList :posts="posts" :filter="activeSubTab" @open="openPost" />
    </div>
</template>

<style scoped>
/* Tailwind CSS está sendo usado para estilização */
</style>
