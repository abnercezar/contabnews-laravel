<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center bg-gray-50"
    >
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
            <form @submit.prevent="login" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Seu Email</label
                    >
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#00244a]"
                        placeholder="Digite seu email"
                        required
                    />
                </div>
                <div class="relative">
                    <label class="block text-sm font-medium mb-1">Senha</label>
                    <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#00244a] pr-10"
                        placeholder="Digite sua senha"
                        required
                    />
                    <span
                        class="absolute right-3 top-9 cursor-pointer text-gray-500"
                        @click="showPassword = !showPassword"
                    >
                        <svg
                            v-if="showPassword"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                        </svg>
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.442-4.362M6.634 6.634A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.96 9.96 0 01-4.818 5.326M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3l18 18"
                            />
                        </svg>
                    </span>
                </div>
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-[#00244a] text-white py-2 rounded font-bold hover:bg-[#001a33] transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ loading ? "Entrando..." : "Entrar" }}
                </button>
                <div v-if="error" class="text-red-600 text-sm mt-2">
                    {{ error }}
                </div>
                <div v-if="success" class="text-green-600 text-sm mt-2">
                    {{ success }}
                </div>
            </form>
            <div class="mt-4 text-sm text-center">
                Novo no TabNews?
                <a href="/register" class="underline text-[#00244a]"
                    >Crie sua conta aqui.</a
                >
            </div>
            <div class="mt-2 text-sm text-center">
                Esqueceu sua senha?
                <a href="#" class="underline text-[#00244a]">Clique aqui.</a>
            </div>
        </div>
        <footer class="mt-8 text-center text-xs text-gray-500">
            © 2025 TabNews &nbsp;|&nbsp;
            <a href="#" class="underline">Contato</a> &nbsp;|&nbsp;
            <a href="#" class="underline">FAQ</a> &nbsp;|&nbsp;
            <a href="#" class="underline">GitHub</a> &nbsp;|&nbsp;
            <a href="#" class="underline">Museu</a> &nbsp;|&nbsp;
            <a href="#" class="underline">RSS</a> &nbsp;|&nbsp;
            <a href="#" class="underline">Sobre</a>
        </footer>
    </div>
</template>

<script setup>
import { reactive, ref } from "vue";

const form = reactive({
    email: "",
    password: "",
});
const showPassword = ref(false);
const loading = ref(false);
const error = ref("");
const success = ref("");

async function login() {
    error.value = "";
    success.value = "";
    loading.value = true;
    try {
        const response = await fetch("/api/login", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(form),
        });
        const data = await response.json();
        if (response.ok) {
            success.value = data.message || "Login realizado com sucesso!";
            // Salva login no localStorage
            localStorage.setItem("isLoggedIn", "true");
            // Redireciona para a home
            window.location.href = "/";
        } else {
            error.value = data.message || "Erro ao fazer login.";
        }
    } catch (e) {
        error.value = "Erro de conexão com o servidor.";
    }
    loading.value = false;
}
</script>

<style scoped>
/* Personalize se necessário */
</style>
