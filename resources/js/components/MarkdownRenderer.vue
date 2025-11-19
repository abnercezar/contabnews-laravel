<template>
    <div class="markdown-renderer prose" v-html="sanitizedHtml"></div>
</template>

<script>
// Small built-in Markdown renderer + sanitizer (no external deps)
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/\"/g, "&quot;")
        .replace(/'/g, "&#39;");
}

function renderMarkdown(md) {
    if (!md) return "";

    // extract fenced code blocks
    const codeBlocks = [];
    md = md.replace(/```([\s\S]*?)```/g, function (_, code) {
        const idx = codeBlocks.push(code) - 1;
        return `@@CODEBLOCK${idx}@@`;
    });

    // extract inline code
    const inlineCodes = [];
    md = md.replace(/`([^`]+)`/g, function (_, code) {
        const idx = inlineCodes.push(code) - 1;
        return `@@INLINECODE${idx}@@`;
    });

    // escape remaining HTML
    md = escapeHtml(md);

    // headings
    md = md.replace(/^######\s*(.*)$/gm, "<h6>$1</h6>");
    md = md.replace(/^#####\s*(.*)$/gm, "<h5>$1</h5>");
    md = md.replace(/^####\s*(.*)$/gm, "<h4>$1</h4>");
    md = md.replace(/^###\s*(.*)$/gm, "<h3>$1</h3>");
    md = md.replace(/^##\s*(.*)$/gm, "<h2>$1</h2>");
    md = md.replace(/^#\s*(.*)$/gm, "<h1>$1</h1>");

    // blockquote
    md = md.replace(/^>\s?(.*)$/gm, "<blockquote>$1</blockquote>");

    // unordered list items -> <li>
    md = md.replace(/^(?:\s*[-*]\s+)(.*)$/gm, "<li>$1</li>");
    // wrap consecutive <li> into <ul>
    md = md.replace(/(?:<li>[\s\S]*?<\/li>\s*)+/g, function (grp) {
        // if group already wrapped, return as-is
        if (/^\s*<ul>/.test(grp) || /^\s*<ol>/.test(grp)) return grp;
        return `<ul>${grp}</ul>`;
    });

    // ordered list items
    md = md.replace(/^\s*\d+\.\s+(.*)$/gm, "<li>$1</li>");
    md = md.replace(/(?:<li>[\s\S]*?<\/li>\s*)+/g, function (grp) {
        // naive detection: if first li starts with a number, wrap in ol
        if (/^\s*<li>\d+/m.test(grp)) return `<ol>${grp}</ol>`;
        return grp;
    });

    // links
    md = md.replace(/\[([^\]]+)\]\(([^)]+)\)/g, function (_, text, url) {
        const safe = /^\s*javascript:/i.test(url) ? "#" : url;
        return `<a href="${safe}">${text}</a>`;
    });

    // paragraphs: split by double newlines
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

    // restore inline code
    result = result.replace(/@@INLINECODE(\d+)@@/g, function (_, i) {
        const c = inlineCodes[Number(i)] || "";
        return `<code>${escapeHtml(c)}</code>`;
    });

    // restore code blocks
    result = result.replace(/@@CODEBLOCK(\d+)@@/g, function (_, i) {
        const c = codeBlocks[Number(i)] || "";
        return `<pre><code>${escapeHtml(c)}</code></pre>`;
    });

    return result;
}

function sanitizeHtml(html) {
    try {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, "text/html");
        const forbidden = doc.querySelectorAll(
            "script, style, iframe, object, embed"
        );
        forbidden.forEach((n) => n.remove());
        const all = doc.querySelectorAll("*");
        all.forEach((el) => {
            const attrs = Array.from(el.attributes || []);
            attrs.forEach((a) => {
                const name = a.name.toLowerCase();
                const val = a.value || "";
                if (name.startsWith("on")) el.removeAttribute(a.name);
                if (
                    (name === "href" || name === "src") &&
                    /^\s*javascript:/i.test(val)
                )
                    el.removeAttribute(a.name);
            });
        });
        return doc.body.innerHTML;
    } catch (e) {
        return html.replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, "");
    }
}

export default {
    name: "MarkdownRenderer",
    props: {
        source: { type: String, default: "" },
    },
    computed: {
        sanitizedHtml() {
            const raw = renderMarkdown(this.source || "");
            return sanitizeHtml(raw);
        },
    },
};
</script>

<style scoped>
.markdown-renderer {
    font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto,
        "Helvetica Neue", Arial;
    color: #111827;
    line-height: 1.7;
}
.markdown-renderer h1 {
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0.67em 0;
}
.markdown-renderer h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0.75em 0;
}
.markdown-renderer h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0.75em 0;
}
.markdown-renderer p {
    margin: 0.6em 0;
}
.markdown-renderer blockquote {
    border-left: 4px solid #374151;
    padding-left: 1rem;
    color: #374151;
    background: #f8fafc;
    margin: 0.75em 0;
}
.markdown-renderer code {
    background: #f3f4f6;
    padding: 0.15rem 0.3rem;
    border-radius: 4px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono",
        "Courier New", monospace;
}
.markdown-renderer pre {
    background: #f3f4f6;
    padding: 0.75rem;
    border-radius: 6px;
    overflow-x: auto;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono",
        "Courier New", monospace;
}
.markdown-renderer a {
    color: #0366d6;
    text-decoration: underline;
}
.markdown-renderer img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
}
.markdown-renderer ul,
.markdown-renderer ol {
    margin: 0.5em 0 0.5em 1.25em;
}
.markdown-renderer li {
    margin: 0.25em 0;
}
</style>
