<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center bg-gray-50"
    >
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
            <form @submit.prevent="login">
                <div class="mb-4">
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1"
                        for="email"
                        >Seu Email</label
                    >
                    <input
                        v-model="email"
                        id="email"
                        type="email"
                        required
                        placeholder="Digite seu email"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                    />
                </div>
                <div class="mb-6">
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1"
                        for="password"
                        >Senha</label
                    >
                    <input
                        v-model="password"
                        id="password"
                        type="password"
                        required
                        placeholder="Digite sua senha"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                    />
                </div>
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700 transition"
                >
                    Entrar
                </button>
            </form>
            <div class="mt-6 flex flex-col gap-2 text-sm text-center">
                <a href="/register" class="text-blue-600 hover:underline"
                    >Novo no ContabNews? Crie sua conta aqui.</a
                >
                <a href="/forgot-password" class="text-blue-600 hover:underline"
                    >Esqueceu sua senha? Clique aqui.</a
                >
            </div>
        </div>
        <footer class="mt-8 text-xs text-gray-500 text-center">
            <div>© 2025 ContabNews</div>
            <div class="flex flex-wrap gap-3 justify-center mt-2">
                <a href="/contato" class="hover:underline">Contato</a>
                <a href="/faq" class="hover:underline">FAQ</a>
                <a
                    href="https://github.com/abnercezar/contabnews-laravel"
                    target="_blank"
                    class="hover:underline"
                    >GitHub</a
                >
                <a href="/museu" class="hover:underline">Museu</a>
                <a href="/rss" class="hover:underline">RSS</a>
                <a href="/sobre" class="hover:underline">Sobre</a>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from "vue";

const email = ref("");
const password = ref("");

function login() {
    // Aqui você pode integrar com a API de login
    // Exemplo:
    // axios.post('/api/login', { email: email.value, password: password.value })
    //   .then(...)
}
</script>

<style scoped>
/* Personalize se necessário */
</style>
