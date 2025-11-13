<script>
import StandardLayout from "../components/StandardLayout.vue";

export default {
    name: "Comments",
    components: { StandardLayout },
    data() {
        return {
            post: {
                title: "🎤 Aprenda a cantar GRÁTIS com sua música favorita! (notefinder.com.br)",
                url: "#",
                author: "justtheryston",
            },
            comments: [
                {
                    text: `"Aguardem a bolha estourar... Vai ser muito pior que a bolha .com e ai veremos o quanto quem substituiu 'gente' por máquina vai se arrepender..."`,
                    tabcoins: 1,
                    replies: 0,
                    username: "seujorge",
                    time: "4 minutos atrás",
                    url: "#",
                },
                {
                    text: `"faaaala cara!! Que comentario COMPLETO! Muito muito agradecido! Confesso que tenho que dar uma estudada..."`,
                    tabcoins: 1,
                    replies: 0,
                    username: "maiaemanuel",
                    time: "25 minutos atrás",
                    url: "#",
                },
                {
                    text: `"Cara, eu também achei show a landing page. Você poderia me dizer qual é esse template?"`,
                    tabcoins: 1,
                    replies: 0,
                    username: "wesleyfralima",
                    time: "46 minutos atrás",
                    url: "#",
                },
                {
                    text: `"Meus 2 cents, Uma coisa que quem desenvolve app ainda nao percebeu eh que a IA/LLM permite criar interacoes nao lineares..."`,
                    tabcoins: 1,
                    replies: 0,
                    username: "Oletros",
                    time: "1 hora atrás",
                    url: "#",
                },
            ],
        };
    },
};
</script>

<template>
    <StandardLayout>
        <div class="mt-8 text-left">
            <!-- Cabeçalho da publicação -->
            <div class="mb-4">
                <a
                    :href="post.url"
                    class="text-blue-700 font-medium hover:underline text-sm"
                >
                    {{ post.title }}
                </a>
                <p class="text-gray-500 text-xs mt-1">
                    Contribuindo com
                    <span class="text-black font-medium">{{
                        post.author
                    }}</span>
                </p>
            </div>

            <!-- Lista de comentários -->
            <div class="flex flex-col gap-5">
                <a
                    v-for="(comment, index) in comments"
                    :key="index"
                    :href="comment.url"
                    class="block border-b border-gray-100 pb-4 rounded-md px-2 hover:bg-gray-50 transition-colors duration-150 cursor-pointer group"
                >
                    <p
                        class="text-gray-800 leading-relaxed text-base group-hover:text-blue-700 group-hover:underline"
                    >
                        {{ comment.text }}
                    </p>

                    <div
                        class="flex flex-wrap gap-2 text-xs text-gray-500 mt-2"
                    >
                        <span>
                            {{ comment.tabcoins }}
                            {{ comment.tabcoins > 1 ? "tabcoins" : "tabcoin" }}
                        </span>
                        <span>·</span>
                        <span>
                            {{ comment.replies }}
                            {{
                                comment.replies === 1
                                    ? "comentário"
                                    : "comentários"
                            }}
                        </span>
                        <span>·</span>
                        <span class="font-medium text-gray-700">{{
                            comment.username
                        }}</span>
                        <span>·</span>
                        <span>{{ comment.time }}</span>
                    </div>
                </a>
            </div>
        </div>
    </StandardLayout>
</template>
