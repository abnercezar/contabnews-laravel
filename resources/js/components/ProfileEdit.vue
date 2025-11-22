<template>
    <div class="w-full max-w-3xl mx-auto p-6 bg-white rounded shadow">
        <h3 class="text-2xl font-bold mb-4">Editar Perfil</h3>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700"
                    >Nome de usuário <span class="text-red-600">*</span></label
                >
                <input
                    v-model="form.name"
                    class="mt-1 block w-full border rounded px-3 py-3 bg-gray-50"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700"
                    >Email <span class="text-red-600">*</span></label
                >
                <input
                    v-model="form.email"
                    class="mt-1 block w-full border rounded px-3 py-3"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700"
                    >Descrição</label
                >
                <MarkdownEditor v-model="form.description" placeholder="">
                    <template #header-right>
                        {{ (form.description || "").length }}/5000
                    </template>
                </MarkdownEditor>
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="notify"
                    type="checkbox"
                    v-model="form.receive_notifications"
                    class="w-4 h-4"
                />
                <label for="notify" class="text-sm text-gray-700"
                    >Receber notificações por email</label
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700"
                    >Senha</label
                >
                <div class="mt-1 text-sm text-blue-600">
                    <a href="/password/reset"
                        >Utilize o fluxo de recuperação de senha →</a
                    >
                </div>
            </div>

            <div class="text-sm text-gray-600">
                Os campos marcados com um asterisco (*) são obrigatórios.
            </div>

            <div>
                <button
                    @click="save"
                    :disabled="saving"
                    class="w-full py-3 bg-green-600 text-white rounded font-semibold"
                >
                    Salvar
                </button>
            </div>
        </div>
    </div>

    <Modal
        :visible="showErrorModal"
        title="Erro ao salvar perfil"
        :message="errorMessage"
        @confirm="closeError"
        @cancel="closeError"
        :confirmText="'Fechar'"
        :cancelText="'Cancelar'"
        :loading="false"
    />
</template>

<script>
import { ref, watch } from "vue";
import axios from "axios";
import { useAuthStore } from "../stores/auth";
import MarkdownEditor from "./MarkdownEditor.vue";
import Modal from "./Modal.vue";

export default {
    name: "ProfileEdit",
    components: { MarkdownEditor, Modal },
    props: {
        initial: {
            type: Object,
            default: () => ({}),
        },
    },
    emits: ["saved", "cancel"],
    setup(props, { emit }) {
        const auth = useAuthStore();
        const saving = ref(false);
        const showErrorModal = ref(false);
        const errorMessage = ref("");
        const form = ref({
            name: props.initial?.name || "",
            email: props.initial?.email || "",
            password: "",
            password_confirmation: "",
            avatar: null,
            description: props.initial?.description || "",
            receive_notifications:
                props.initial?.receive_notifications || false,
        });

        watch(
            () => props.initial,
            (v) => {
                form.value.name = v?.name || "";
                form.value.email = v?.email || "";
            },
            { immediate: true }
        );

        const onFileChange = (e) => {
            form.value.avatar =
                e.target.files && e.target.files[0] ? e.target.files[0] : null;
        };

        // MarkdownEditor handles editing; we only watch props to set initial values

        const save = async () => {
            saving.value = true;
            try {
                const fd = new FormData();
                fd.append("name", form.value.name);
                fd.append("email", form.value.email);
                if (form.value.password) {
                    fd.append("password", form.value.password);
                    fd.append(
                        "password_confirmation",
                        form.value.password_confirmation
                    );
                }
                if (form.value.avatar) fd.append("avatar", form.value.avatar);
                if (typeof form.value.description !== "undefined")
                    fd.append("description", form.value.description);
                if (typeof form.value.receive_notifications !== "undefined")
                    fd.append(
                        "receive_notifications",
                        form.value.receive_notifications ? 1 : 0
                    );

                const res = await axios.put("/api/user", fd, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                if (auth) auth.user = res.data;
                emit("saved", res.data);
            } catch (e) {
                console.error("Erro ao salvar perfil", e);
                errorMessage.value = "Erro ao salvar perfil";
                showErrorModal.value = true;
            } finally {
                saving.value = false;
            }
        };

        const closeError = () => {
            showErrorModal.value = false;
            errorMessage.value = "";
        };

        const cancel = () => emit("cancel");

        return {
            form,
            onFileChange,
            save,
            cancel,
            saving,
            showErrorModal,
            errorMessage,
            closeError,
        };
    },
};
</script>
