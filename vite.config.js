import { defineConfig } from "vite";
import { resolve } from "path";

export default defineConfig({
  publicDir: false,
  build: {
    outDir: "public",
    emptyOutDir: false,
    assetsDir: "",
    rollupOptions: {
      input: {
        "user-form": resolve(__dirname, "dist/user-form.js"),
        bootstrap: resolve(
          __dirname,
          "node_modules/bootstrap/dist/css/bootstrap.min.css"
        ),
      },
      output: {
        entryFileNames: "js/[name].js",
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith(".css")) {
            return "css/[name][extname]";
          }
          return "[name][extname]";
        },
      },
    },
    minify: "esbuild",
  },
});