"use strict";
var UserForm = (() => {
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __commonJS = (cb, mod) => function __require() {
    return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
  };

  // dist/user-form.js (adaptado para nova estrutura)
  var require_user_form = __commonJS({
    "dist/user-form.js"(exports) {
      Object.defineProperty(exports, "__esModule", { value: true });
      function validarUsuario(data) {
        const erros = [];
        if (!data.nome.trim()) {
          erros.push({ campo: "nome", mensagem: "Nome é obrigatório." });
        }
        if (!data.email.trim()) {
          erros.push({ campo: "email", mensagem: "E-mail é obrigatório." });
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
          erros.push({ campo: "email", mensagem: "E-mail inválido." });
        }
        if (data.senha || data.repetirSenha) {
          if (data.senha.length < 6) {
            erros.push({ campo: "senha", mensagem: "Senha deve ter pelo menos 6 caracteres." });
          }
          if (data.senha !== data.repetirSenha) {
            erros.push({ campo: "repetirSenha", mensagem: "As senhas não conferem." });
          }
        }
        if (data.nivelAcesso !== "adm" && data.nivelAcesso !== "padrao") {
          erros.push({ campo: "nivelAcesso", mensagem: "Nível de acesso inválido." });
        }
        return { valido: erros.length === 0, erros };
      }
      function formatarUsuario(data) {
        return [
          `Nome: ${data.nome}`,
          `E-mail: ${data.email}`,
          `Nível de acesso: ${data.nivelAcesso === "adm" ? "Administrador" : "Padrão"}`
        ].join("\n");
      }
      async function enviarParaServidor(data) {
        const payload = {
          nome: data.nome,
          email: data.email,
          senha: data.senha,
          repetirSenha: data.repetirSenha,
          nivelAcesso: data.nivelAcesso
        };
        if (data.id !== void 0) {
          payload.id = data.id;
        }
        if (data.ativo !== void 0) {
          payload.ativo = data.ativo ? 1 : 0;
        }
        const resposta = await fetch("../logica/salvar_usuario.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        });
        const json = await resposta.json();
        return json;
      }
      function setupPasswordToggles() {
        const buttons = document.querySelectorAll(".toggle-password");
        buttons.forEach((btn) => {
          const targetId = btn.getAttribute("data-target");
          if (!targetId) return;
          const input = document.getElementById(targetId);
          if (!input) return;
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
      function setupUserForm(formId, outputId) {
        const form = document.getElementById(formId);
        const output = document.getElementById(outputId);
        if (!form || !output) return;
        const formEl = form;
        const outputEl = output;
        let canChangeAccess = true;
        let canDeactivate = true;
        async function tryLoad() {
          const params = new URLSearchParams(window.location.search);
          const idParam = params.get("id");
          if (idParam) {
            const id = parseInt(idParam, 10);
            if (!isNaN(id)) {
              outputEl.innerHTML = "Carregando dados...";
              try {
                const resp = await fetchUser(id);
                if (!resp.sucesso || !resp.usuario) {
                  outputEl.innerHTML = `<div class="alert alert-danger">${resp.mensagem || "Erro ao obter usuário."}</div>`;
                  formEl.querySelectorAll("input,select,button").forEach((el) => { el.disabled = true; });
                  return;
                }
                const u = resp.usuario;
                formEl.elements.namedItem("nome").value = u.nome;
                formEl.elements.namedItem("email").value = u.email;
                const nivelEl = formEl.elements.namedItem("nivelAcesso");
                nivelEl.value = u.nivel_acesso === 1 ? "adm" : "padrao";
                let hidden = formEl.querySelector('input[name="id"]');
                if (!hidden) {
                  hidden = document.createElement("input");
                  hidden.type = "hidden";
                  hidden.name = "id";
                  formEl.appendChild(hidden);
                }
                hidden.value = String(u.id);
                canChangeAccess = !!resp.canChangeAccess;
                canDeactivate = !!resp.canDeactivate;
                if (!canChangeAccess) nivelEl.disabled = true;
                const ativoEl = formEl.querySelector('input[name="ativo"]');
                if (ativoEl) {
                  ativoEl.checked = u.ativo === 1;
                  if (!canDeactivate) ativoEl.disabled = true;
                  ativoEl.closest(".form-check")?.classList.remove("d-none");
                }
                const submitBtn = formEl.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.textContent = "Salvar";
                outputEl.innerHTML = "";
              } catch (e) {
                outputEl.innerHTML = '<div class="alert alert-danger">Erro ao carregar dados.</div>';
              }
            }
          }
        }
        tryLoad();
        formEl.addEventListener("submit", (event) => {
          event.preventDefault();
          const formData = new FormData(formEl);
          const data = {
            nome: String(formData.get("nome") ?? ""),
            email: String(formData.get("email") ?? ""),
            senha: String(formData.get("senha") ?? ""),
            repetirSenha: String(formData.get("repetirSenha") ?? ""),
            nivelAcesso: formData.get("nivelAcesso") ?? "padrao"
          };
          const idVal = formData.get("id");
          if (idVal) data.id = Number(idVal);
          if (formData.get("ativo") !== null) {
            data.ativo = !!(formData.get("ativo") === "1" && formEl.elements.namedItem("ativo").checked);
          }
          const resultado = validarUsuario(data);
          if (!resultado.valido) {
            outputEl.innerHTML = "<strong>Erros de validação (frontend):</strong><ul>" + resultado.erros.map((e) => `<li><strong>${e.campo}:</strong> ${e.mensagem}</li>`).join("") + "</ul>";
            return;
          }
          if (data.id !== void 0 && !canChangeAccess) delete data.nivelAcesso;
          if (data.id !== void 0 && !canDeactivate) delete data.ativo;
          outputEl.innerHTML = "Enviando...";
          enviarParaServidor(data).then((resp) => {
            if (!resp.sucesso) {
              const errosServidor = resp.erros && resp.erros.length ? "<ul>" + resp.erros.map((m) => `<li>${m}</li>`).join("") + "</ul>" : "";
              outputEl.innerHTML = "<strong>Falha ao salvar:</strong> " + resp.mensagem + errosServidor;
              return;
            }
            const usuarioInfo = resp.usuario ? `ID: ${resp.usuario.id}\n${formatarUsuario({ nome: resp.usuario.nome, email: resp.usuario.email, senha: data.senha, repetirSenha: data.repetirSenha, nivelAcesso: data.nivelAcesso })}` : formatarUsuario(data);
            outputEl.innerHTML = "<strong>Dados salvos com sucesso!</strong><pre>" + usuarioInfo + "</pre>";
          }).catch(() => {
            outputEl.innerHTML = "<strong>Erro inesperado ao comunicar com o servidor.</strong>";
          });
        });
      }
      document.addEventListener("DOMContentLoaded", () => {
        setupPasswordToggles();
        setupUserForm("form-usuario", "resultado");
      });
      async function fetchUser(id) {
        const resp = await fetch(`../logica/obter_usuario.php?id=${id}`);
        return await resp.json();
      }
    }
  });
  return require_user_form();
})();
