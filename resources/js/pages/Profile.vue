<script>
import ConTabNewsIcon from "../components/ConTabNewsIcon.vue";
import ProfileEdit from "../components/ProfileEdit.vue";
import { useAuthStore } from "../stores/auth";
import { ref, onMounted } from "vue";

export default {
    name: "Profile",
    components: { ConTabNewsIcon, ProfileEdit },
    setup() {
        const auth = useAuthStore();
        const editMode = ref(false);

        onMounted(() => {
            try {
                const params = new URLSearchParams(window.location.search);
                if (params.get("edit") === "1") {
                    editMode.value = true;
                }
            } catch (e) {}
        });

        const handleSaved = (user) => {
            try {
                // atualiza store (ProfileEdit também atualiza, mas mantém por segurança)
                if (auth) auth.user = user;
                // remove param edit da URL
                try {
                    const url = new URL(window.location.href);
                    url.searchParams.delete("edit");
                    window.history.replaceState({}, "", url.toString());
                } catch (e) {}
            } finally {
                editMode.value = false;
            }
        };

        return { auth, editMode, handleSaved };
    },
};
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col">
        <div class="flex-1 flex flex-col items-center mt-8">
            <div class="w-full max-w-4xl p-8 mx-4 sm:mx-auto">
                <ProfileEdit
                    v-if="editMode"
                    :initial="auth.user"
                    @saved="handleSaved"
                    @cancel="editMode = false"
                />
            </div>
        </div>
    </div>
</template>
