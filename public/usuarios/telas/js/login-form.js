"use strict";
var LoginForm = (() => {
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __commonJS = (cb, mod) => function __require() {
    return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
  };

  // dist/login-form.js (adaptado)
  var require_login_form = __commonJS({
    "dist/login-form.js"(exports) {
      Object.defineProperty(exports, "__esModule", { value: true });
      function setupPasswordToggles() {
        const buttons = document.querySelectorAll(".toggle-password");
        buttons.forEach((btn) => {
          const targetId = btn.getAttribute("data-target");
          if (!targetId)
            return;
          const input = document.getElementById(targetId);
          if (!input)
            return;
          btn.addEventListener("click", () => {
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            const icon = btn.querySelector("i");
            if (icon) {
              icon.classList.toggle("bi-eye", !isPassword);
              icon.classList.toggle("bi-eye-slash", isPassword);
            }
          });
        });
      }
      async function login(payload) {
        const resposta = await fetch("usuarios/logica/login_usuario.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(payload)
        });
        return await resposta.json();
      }
      function setupLoginForm(formId, outputId) {
        const form = document.getElementById(formId);
        const output = document.getElementById(outputId);
        if (!form || !output)
          return;
        form.addEventListener("submit", (event) => {
          var _a, _b;
          event.preventDefault();
          const formData = new FormData(form);
          const email = String((_a = formData.get("email")) !== null && _a !== void 0 ? _a : "").trim();
          const senha = String((_b = formData.get("senha")) !== null && _b !== void 0 ? _b : "");
          if (!email || !senha) {
            output.innerHTML = '<div class="alert alert-warning mb-0">Preencha e-mail e senha.</div>';
            return;
          }
          output.innerHTML = '<div class="text-muted">Entrando...</div>';
          login({ email, senha }).then((resp) => {
            if (!resp.sucesso) {
              output.innerHTML = `<div class="alert alert-danger mb-0">${resp.mensagem}</div>`;
              return;
            }
            output.innerHTML = `<div class="alert alert-success mb-0">${resp.mensagem}</div>`;
            if (resp.sucesso && resp.usuario) {
              window.location.href = `home.php`;
            }
          }).catch(() => {
            output.innerHTML = '<div class="alert alert-danger mb-0">Erro inesperado ao comunicar com o servidor.</div>';
          });
        });
      }
      document.addEventListener("DOMContentLoaded", () => {
        setupPasswordToggles();
        setupLoginForm("form-login", "resultado-login");
      });
    }
  });
  return require_login_form();
})();
