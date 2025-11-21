<script>
import { MusicalNoteIcon } from "@heroicons/vue/24/outline";

export default {
    name: "Footer",
    components: {
        MusicalNoteIcon,
    },
};
</script>

<template>
    <footer
        class="bg-[#3c3c3c] text-white text-center py-3 sm:py-4 mt-8 w-full"
    >
        <div class="container mx-auto px-2 sm:px-0">
            <p
                class="text-xs sm:text-base flex items-center justify-center gap-2"
            >
                <span class="inline-flex items-center pointer-events-none">
                    <MusicalNoteIcon
                        class="w-4 h-4 text-[#daa520] mr-1 pointer-events-none"
                        aria-hidden="true"
                    />
                </span>
                &copy; 2025
                <span class="text-[#daa520] font-bold">Support</span> - Todos os
                direitos reservados
            </p>
        </div>
    </footer>
</template>
