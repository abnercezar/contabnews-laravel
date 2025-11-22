<template>
    <div class="mt-2 border rounded bg-white">
        <div
            class="px-3 py-2 border-b bg-gray-50 flex items-center gap-2 text-gray-600"
        >
            <div class="flex gap-2 flex-wrap">
                <button type="button" @click="insert('# ')">
                    <strong>H</strong>
                </button>
                <button type="button" @click="wrap('**')">
                    <strong>B</strong>
                </button>
                <button type="button" @click="wrap('*')"><em>I</em></button>
                <button type="button" @click="wrap('> ')">❝</button>
                <button type="button" @click="wrap('[', '](url)')">🔗</button>
                <button type="button" @click="wrap('`')">&lt;/&gt;</button>
                <button type="button" @click="wrap('```\n', '\n```')">
                    [code]
                </button>
                <button type="button" @click="insert('- ')">•</button>
                <button type="button" @click="insert('1. ')">1.</button>
            </div>

            <div class="ml-auto text-xs text-gray-500">
                <slot name="header-right"></slot>
            </div>
        </div>

        <div class="p-3">
            <textarea
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                class="w-full min-h-[180px] p-2 focus:outline-none focus:ring-2 focus:ring-gray-700 bg-white resize-none"
                :placeholder="placeholder"
            ></textarea>
        </div>
    </div>
</template>

<script>
export default {
    name: "MarkdownEditor",
    props: {
        modelValue: {
            type: String,
            default: "",
        },
        placeholder: {
            type: String,
            default: "Digite o conteúdo...",
        },
    },
    emits: ["update:modelValue"],
    methods: {
        insert(text) {
            this.$emit("update:modelValue", this.modelValue + text);
        },
        wrap(before, after = before) {
            const textarea = this.$el.querySelector("textarea");
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const value = this.modelValue;
            const selected = value.slice(start, end);
            const newValue =
                value.slice(0, start) +
                before +
                selected +
                after +
                value.slice(end);
            this.$emit("update:modelValue", newValue);
            this.$nextTick(() => {
                textarea.focus();
                textarea.setSelectionRange(
                    start + before.length,
                    start + before.length + selected.length
                );
            });
        },
    },
};
</script>

<style scoped>
button {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s;
}
button:hover {
    background: #e5e7eb;
}
</style>
