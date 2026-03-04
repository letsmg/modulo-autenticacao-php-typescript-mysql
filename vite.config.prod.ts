// vite.config.prod.ts

import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  publicDir: false,

  build: {
    outDir: 'public/js',
    emptyOutDir: false,
    assetsDir: '',
    minify: 'terser',
    sourcemap: false,

    rollupOptions: {
      input: {
        'user_form': resolve(__dirname, 'src/user_form.ts'),
        'login_form': resolve(__dirname, 'src/login_form.ts'),
        'funcoes_bacanas': resolve(__dirname, 'src/funcoes_bacanas.ts'),
        'mensagem_form': resolve(__dirname, 'src/mensagem_form.ts'),
        'notificacoes': resolve(__dirname, 'src/notificacoes.ts'),
      },
      output: {
        entryFileNames: '[name].min.js'
        // 🔥 NÃO colocar inlineDynamicImports aqui
      },
    },
  },
});