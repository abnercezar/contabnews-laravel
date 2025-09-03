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
            <button type="button" @click="insert('---\n')">HR</button>
        </div>
        <textarea
            v-model="input"
            class="w-full min-h-[180px] border rounded p-2 focus:outline-none focus:ring-2 focus:ring-[#00244a] bg-gray-50 resize-none mb-4"
            :placeholder="placeholder"
        ></textarea>
        <div class="mt-4">
            <label class="block text-sm font-semibold mb-2 text-[#00244a]"
                >Preview</label
            >
            <div
                class="border rounded bg-gray-50 p-4 markdown-body"
                v-html="rendered"
            ></div>
        </div>
    </div>
</template>

<script>
import { marked } from "marked";
export default {
    name: "MarkdownEditorFull",
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
    data() {
        return {
            input: this.modelValue,
        };
    },
    watch: {
        input(val) {
            this.$emit("update:modelValue", val);
        },
        modelValue(val) {
            if (val !== this.input) this.input = val;
        },
    },
    computed: {
        rendered() {
            return marked.parse(this.input || "");
        },
    },
    methods: {
        insert(text) {
            this.input += text;
        },
        wrap(before, after = before) {
            const textarea = this.$el.querySelector("textarea");
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const value = this.input;
            const selected = value.slice(start, end);
            const newValue =
                value.slice(0, start) +
                before +
                selected +
                after +
                value.slice(end);
            this.input = newValue;
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
.markdown-body {
    font-size: 1rem;
    line-height: 1.7;
    color: #222;
}
.markdown-body h1,
.markdown-body h2,
.markdown-body h3 {
    font-weight: bold;
    margin-top: 1em;
}
.markdown-body code {
    background: #f3f4f6;
    border-radius: 4px;
    padding: 2px 4px;
}
.markdown-body pre {
    background: #f3f4f6;
    border-radius: 4px;
    padding: 8px;
    overflow-x: auto;
}
.markdown-body blockquote {
    border-left: 4px solid #00244a;
    padding-left: 1em;
    color: #555;
    margin: 1em 0;
}
</style>
