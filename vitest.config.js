import { defineConfig } from "vitest/config";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [vue()],
    test: {
        environment: "jsdom",
        setupFiles: "resources/js/tests/setupTests.js",
        globals: true,
        include: ["resources/js/**/__tests__/**/*.test.{js,ts}"],
        transformMode: {
            web: [/\.vue$/],
        },
    },
});
