<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center bg-gray-50"
    >
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Cadastro</h2>
            <form @submit.prevent="register" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Nome de usuário</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#00244a]"
                        placeholder="Nome público"
                        required
                    />
                </div>
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
                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Confirme a senha</label
                    >
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#00244a]"
                        placeholder="Confirme sua senha"
                        required
                    />
                </div>
                <div class="flex items-center">
                    <input
                        v-model="acceptedTerms"
                        type="checkbox"
                        id="terms"
                        class="mr-2"
                    />
                    <label for="terms" class="text-sm"
                        >Li e estou de acordo com os
                        <a href="#" class="underline text-[#00244a]"
                            >Termos de Uso</a
                        >.</label
                    >
                </div>
                <button
                    type="submit"
                    :disabled="!acceptedTerms || loading"
                    class="w-full bg-[#00244a] text-white py-2 rounded font-bold hover:bg-[#001a33] transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ loading ? "Cadastrando..." : "Cadastrar" }}
                </button>
                <div v-if="error" class="text-red-600 text-sm mt-2">
                    {{ error }}
                </div>
                <div v-if="success" class="text-green-600 text-sm mt-2">
                    {{ success }}
                </div>
            </form>
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
<script>
export default {
    name: "Register",
    data() {
        return {
            form: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
            acceptedTerms: false,
            loading: false,
            error: "",
            success: "",
        };
    },
    methods: {
        async register() {
            this.error = "";
            this.success = "";
            this.loading = true;
            try {
                const response = await fetch("/api/register", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(this.form),
                });
                const data = await response.json();
                if (response.ok) {
                    this.success =
                        data.message || "Cadastro realizado com sucesso!";
                    this.form = {
                        name: "",
                        email: "",
                        password: "",
                        password_confirmation: "",
                    };
                    this.acceptedTerms = false;
                } else {
                    this.error = data.message || "Erro ao cadastrar usuário.";
                }
            } catch (e) {
                this.error = "Erro de conexão com o servidor.";
            }
            this.loading = false;
        },
    },
};
</script>
