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
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-gray-700"
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
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-gray-700"
                        placeholder="Digite seu email"
                        required
                    />
                </div>
                <div class="relative">
                    <label class="block text-sm font-medium mb-1">Senha</label>
                    <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-gray-700 pr-10"
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
                <div class="relative">
                    <label class="block text-sm font-medium mb-1"
                        >Confirme a senha</label
                    >
                    <input
                        v-model="form.password_confirmation"
                        :type="showPasswordConfirm ? 'text' : 'password'"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-gray-700 pr-10"
                        placeholder="Confirme sua senha"
                        required
                    />
                    <span
                        class="absolute right-3 top-9 cursor-pointer text-gray-500"
                        @click="showPasswordConfirm = !showPasswordConfirm"
                    >
                        <svg
                            v-if="showPasswordConfirm"
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
                <div class="flex items-center">
                    <input
                        v-model="acceptedTerms"
                        type="checkbox"
                        id="terms"
                        class="mr-2"
                    />
                    <label for="terms" class="text-sm"
                        >Li e estou de acordo com os
                        <a href="#" class="underline text-gray-700"
                            >Termos de Uso</a
                        >.</label
                    >
                </div>
                <button
                    type="submit"
                    :disabled="!acceptedTerms || loading"
                    class="w-full bg-[#daa520] text-white py-2 rounded font-bold hover:bg-[#d3ad71] transition disabled:opacity-50 disabled:cursor-not-allowed"
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
            showPassword: false,
            showPasswordConfirm: false,
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
