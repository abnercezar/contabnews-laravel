import { h } from "vue";

const baseAttrs = { fill: "none", stroke: "#daa520", viewBox: "0 0 24 24" };

const attrsFor = (props, ctx) => {
    const passed = (ctx && ctx.attrs) || {};
    return {
        ...baseAttrs,
        ...passed,
        class: passed.class || props.class || "inline w-5 h-5 mr-2",
    };
};

export const IconACA = (props, ctx) =>
    h(
        "svg",
        attrsFor(props, ctx),
        h("path", {
            d: "M3 12l9-9 9 9v8a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-4H9v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z",
        })
    );

export const IconNovoConteudo = (props, ctx) =>
    h(
        "svg",
        attrsFor(props, ctx),
        h("path", {
            d: "M12 5v14m7-7H5",
            "stroke-width": 2,
            "stroke-linecap": "round",
        })
    );

export const IconMeusConteudos = (props, ctx) =>
    h("svg", attrsFor(props, ctx), [
        h("rect", { x: 4, y: 4, width: 16, height: 16, rx: 2 }),
        h("path", {
            d: "M8 9h8M8 13h6",
            "stroke-width": 2,
            "stroke-linecap": "round",
        }),
    ]);

export const IconEditarPerfil = (props, ctx) =>
    h("svg", attrsFor(props, ctx), [
        h("circle", { cx: 12, cy: 8, r: 4 }),
        h("path", {
            d: "M4 20v-2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v2",
            "stroke-width": 2,
        }),
    ]);

export const IconAuto = (props, ctx) =>
    h("svg", attrsFor(props, ctx), [
        h("circle", { cx: 12, cy: 12, r: 3 }),
        h("path", {
            d: "M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09A1.65 1.65 0 0 0 12 3a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09c0 .66.26 1.3.73 1.77z",
            "stroke-width": 2,
        }),
    ]);

export const IconDeslogar = (props, ctx) =>
    h(
        "svg",
        attrsFor(props, ctx),
        h("path", {
            d: "M17 16l4-4m0 0l-4-4m4 4H7",
            "stroke-width": 2,
            "stroke-linecap": "round",
        })
    );

export default {
    ACA: IconACA,
    "Novo conteúdo": IconNovoConteudo,
    "Meus conteúdos": IconMeusConteudos,
    "Editar perfil": IconEditarPerfil,
    Auto: IconAuto,
    Deslogar: IconDeslogar,
};
