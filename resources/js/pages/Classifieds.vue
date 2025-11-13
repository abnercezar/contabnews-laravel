<script>
import StandardLayout from "../components/StandardLayout.vue";

export default {
    name: "Classifieds",
    components: { StandardLayout },
    data() {
        return {
            classifieds: [
                {
                    id: 3001,
                    title: "tutoriais modo texto",
                    username: "RodrigoSchio",
                    comments: 0,
                    time: "10 dias atrás",
                    url: "#",
                },
                {
                    id: 3002,
                    title: "🎤 Aprenda a cantar GRÁTIS com sua música favorita!",
                    username: "justtheryston",
                    comments: 0,
                    time: "15 dias atrás",
                    url: "#",
                },
                {
                    id: 3003,
                    title: "[PITCH] - Todos pelo Beny! - Um site beneficente feito com IA e alguns ajustes manuais.",
                    username: "jonwlf",
                    comments: 0,
                    time: "16 dias atrás",
                    url: "#",
                },
                {
                    title: "Fiz um Module Bundler em JS",
                    username: "Marley",
                    comments: 1,
                    time: "20 dias atrás",
                    url: "#",
                },
                {
                    title: "Conheça o BLOCO DE NOTAS 2.0 totalmente BRASILEIRO",
                    username: "Andreldev",
                    comments: 0,
                    time: "20 dias atrás",
                    url: "#",
                },
                {
                    title: "[COMET] 1 mês grátis do plano pro do navegador comet",
                    username: "Kvrt",
                    comments: 0,
                    time: "23 dias atrás",
                    url: "#",
                },
                {
                    title: "Buscando parceiro backend pra projeto de emissão de notas fiscais",
                    username: "lukaslumiere",
                    comments: 3,
                    time: "25 dias atrás",
                    url: "#",
                },
                {
                    title: "FastroAI – uma stack pro seu SaaS de IA finalmente sair",
                    username: "igorbenav",
                    comments: 0,
                    time: "2 meses atrás",
                    url: "#",
                },
                {
                    title: "SaaS: Use a ferramenta certa para produzir seu software",
                    username: "KitsuneSemCalda",
                    comments: 0,
                    time: "2 meses atrás",
                    url: "#",
                },
                {
                    title: "SpyDecloakfy Adspy - Encontre ideias validadas em qualquer nicho (sem gastar milhares de reais)",
                    username: "FelipeLC",
                    comments: 0,
                    time: "2 meses atrás",
                    url: "#",
                },
            ],
        };
    },
    methods: {
        openClassified(item) {
            // Try to open a content page if id exists, otherwise follow url
            const id = item?.id ?? item?.post_id ?? null;
            const url = id ? `/content/${id}` : item?.url ?? "#";
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
    <StandardLayout>
        <div class="mt-8 text-left">
            <!-- Lista de classificados -->
            <div class="flex flex-col gap-6">
                <a
                    v-for="(classified, index) in classifieds"
                    :key="index"
                    href="#"
                    @click.prevent="openClassified(classified)"
                    class="block border-b border-gray-100 pb-4 hover:bg-gray-50 transition-colors duration-150 cursor-pointer group"
                >
                    <p
                        class="text-gray-900 font-medium text-base group-hover:text-blue-700 group-hover:underline"
                    >
                        {{ classified.title }}
                    </p>
                    <div
                        class="flex flex-wrap gap-2 text-xs text-gray-500 mt-2"
                    >
                        <span class="text-gray-700">Patrocinado</span>
                        <span>·</span>
                        <span>
                            {{ classified.comments }}
                            {{
                                classified.comments === 1
                                    ? "comentário"
                                    : "comentários"
                            }}
                        </span>
                        <span>·</span>
                        <span>{{ classified.username }}</span>
                        <span>·</span>
                        <span>{{ classified.time }}</span>
                    </div>
                </a>
            </div>
        </div>
    </StandardLayout>
</template>
