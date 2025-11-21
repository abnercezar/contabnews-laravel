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
            class="w-full min-h-[180px] border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-700 bg-gray-50 resize-none mb-4"
            :placeholder="placeholder"
        ></textarea>
    </div>
</template>

<script>
// lightweight renderer reused from MarkdownRenderer (no external deps)
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/\"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

function renderMarkdownSimple(md) {
    if (!md) return "";
    const codeBlocks = [];
    md = md.replace(/```([\s\S]*?)```/g, function (_, code) {
        const idx = codeBlocks.push(code) - 1;
        return `@@CODEBLOCK${idx}@@`;
    });
    const inlineCodes = [];
    md = md.replace(/`([^`]+)`/g, function (_, code) {
        const idx = inlineCodes.push(code) - 1;
        return `@@INLINECODE${idx}@@`;
    });
    md = escapeHtml(md);
    md = md.replace(/^######\s*(.*)$/gm, "<h6>$1</h6>");
    md = md.replace(/^#####\s*(.*)$/gm, "<h5>$1</h5>");
    md = md.replace(/^####\s*(.*)$/gm, "<h4>$1</h4>");
    md = md.replace(/^###\s*(.*)$/gm, "<h3>$1</h3>");
    md = md.replace(/^##\s*(.*)$/gm, "<h2>$1</h2>");
    md = md.replace(/^#\s*(.*)$/gm, "<h1>$1</h1>");
    md = md.replace(/^>\s?(.*)$/gm, "<blockquote>$1</blockquote>");
    md = md.replace(/^(?:\s*[-*]\s+)(.*)$/gm, "<li>$1</li>");
    md = md.replace(/(?:<li>[\s\S]*?<\/li>\s*)+/g, function (grp) {
        if (/^\s*<ul>/.test(grp) || /^\s*<ol>/.test(grp)) return grp;
        return `<ul>${grp}</ul>`;
    });
    md = md.replace(/^\s*\d+\.\s+(.*)$/gm, "<li>$1</li>");
    md = md.replace(/(?:<li>[\s\S]*?<\/li>\s*)+/g, function (grp) {
        if (/^\s*<li>\d+/m.test(grp)) return `<ol>${grp}</ol>`;
        return grp;
    });
    md = md.replace(/\[([^\]]+)\]\(([^)]+)\)/g, function (_, text, url) {
        const safe = /^\s*javascript:/i.test(url) ? "#" : url;
        return `<a href="${safe}">${text}</a>`;
    });
    const parts = md.split(/\n{2,}/g);
    const processed = parts
        .map((p) => {
            p = p.trim();
            if (!p) return "";
            if (
                /^<h\d+>/.test(p) ||
                /^<ul>/.test(p) ||
                /^<ol>/.test(p) ||
                /^<blockquote>/.test(p) ||
                /^@@CODEBLOCK\d+@@/.test(p) ||
                /^<pre>/.test(p)
            )
                return p;
            return `<p>${p.replace(/\n/g, "<br />")}</p>`;
        })
        .join("\n");
    let result = processed;
    result = result.replace(/@@INLINECODE(\d+)@@/g, function (_, i) {
        const c = inlineCodes[Number(i)] || "";
        return `<code>${escapeHtml(c)}</code>`;
    });
    result = result.replace(/@@CODEBLOCK(\d+)@@/g, function (_, i) {
        const c = codeBlocks[Number(i)] || "";
        return `<pre><code>${escapeHtml(c)}</code></pre>`;
    });
    return result;
}

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
            return renderMarkdownSimple(this.input || "");
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
    font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto,
        "Helvetica Neue", Arial;
    font-size: 1rem;
    line-height: 1.7;
    color: #111827;
}
.markdown-body h1 {
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0.67em 0;
}
.markdown-body h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0.75em 0;
}
.markdown-body h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0.75em 0;
}
.markdown-body code {
    background: #f3f4f6;
    border-radius: 4px;
    padding: 2px 4px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono",
        "Courier New", monospace;
}
.markdown-body pre {
    background: #f3f4f6;
    border-radius: 6px;
    padding: 8px;
    overflow-x: auto;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono",
        "Courier New", monospace;
}
.markdown-body blockquote {
    border-left: 4px solid #374151;
    padding-left: 1em;
    color: #374151;
    background: #f8fafc;
    margin: 1em 0;
}
</style>
