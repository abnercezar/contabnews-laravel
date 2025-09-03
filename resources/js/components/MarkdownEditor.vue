<template>
    <div class="w-full">
        <div class="flex gap-2 mb-2 flex-wrap">
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
        <textarea
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="w-full min-h-[180px] border rounded p-2 focus:outline-none focus:ring-2 focus:ring-[#00244a] bg-gray-50 resize-none"
            :placeholder="placeholder"
        ></textarea>
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
