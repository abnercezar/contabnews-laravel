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
                <div>
                    <label class="block text-sm font-medium mb-1">Senha</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#00244a]"
                        placeholder="Digite sua senha"
                        required
                    />
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
            // Aqui você pode salvar o token, redirecionar, etc.
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
