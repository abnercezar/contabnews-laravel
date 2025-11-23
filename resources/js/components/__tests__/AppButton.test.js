import { describe, it, expect } from "vitest";
import { render, fireEvent } from "@testing-library/vue";
import AppButton from "../AppButton.vue";

describe("AppButton", () => {
    it("Renderiza o conteúdo do slot e emite um clique.", async () => {
        const { getByText, emitted } = render(AppButton, {
            slots: { default: "Click me" },
        });

        const btn = getByText("Click me");
        await fireEvent.click(btn);

        const e = emitted();
        expect(e).toHaveProperty("click");
    });
});
