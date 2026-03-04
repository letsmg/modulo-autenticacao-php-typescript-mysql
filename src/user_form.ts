// Exemplos de TypeScript para formulário de cadastro

// 1. Tipo especial (union type) para nível de acesso
type AccessLevel = "adm" | "padrao";

// 2. Interface para os dados do formulário
interface UserFormData {
  id?: number;
  nome: string;
  email: string;
  senha: string;
  repetirSenha: string;
  nivelAcesso: AccessLevel;
  ativo?: boolean; // apenas para edição
}

// 3. Tipos para resultado de validação
interface ValidationError {
  campo: keyof UserFormData;
  mensagem: string;
}

interface ValidationResult {
  valido: boolean;
  erros: ValidationError[];
}

interface ApiResponse {
  sucesso: boolean;
  mensagem: string;
  erros?: string[];
  usuario?: {
    id: number;
    nome: string;
    email: string;
    nivel_acesso: number;
    ativo?: number;
  };
}

// resposta devolvida ao pedir um usuário existente
interface GetUserResponse {
  sucesso: boolean;
  mensagem?: string;
  usuario?: {
    id: number;
    nome: string;
    email: string;
    nivel_acesso: number;
    ativo: number;
  };
  canChangeAccess?: boolean;
  canDeactivate?: boolean;
}

function validarUsuario(data: UserFormData): ValidationResult {
  const erros: ValidationError[] = [];

  if (!data.nome.trim()) {
    erros.push({ campo: "nome", mensagem: "Nome é obrigatório." });
  }

  if (!data.email.trim()) {
    erros.push({ campo: "email", mensagem: "E-mail é obrigatório." });
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
    erros.push({ campo: "email", mensagem: "E-mail inválido." });
  }

  // senha só é obrigatória em cadastro; na edição pode ficar em branco para manter a antiga
  if (data.senha || data.repetirSenha) {
    if (data.senha.length < 6) {
      erros.push({
        campo: "senha",
        mensagem: "Senha deve ter pelo menos 6 caracteres.",
      });
    }

    if (data.senha !== data.repetirSenha) {
      erros.push({
        campo: "repetirSenha",
        mensagem: "As senhas não conferem.",
      });
    }
  }

  if (data.nivelAcesso !== "adm" && data.nivelAcesso !== "padrao") {
    erros.push({
      campo: "nivelAcesso",
      mensagem: "Nível de acesso inválido.",
    });
  }

  return {
    valido: erros.length === 0,
    erros,
  };
}

function formatarUsuario(data: UserFormData): string {
  return [
    `Nome: ${data.nome}`,
    `E-mail: ${data.email}`,
    `Nível de acesso: ${data.nivelAcesso === "adm" ? "Administrador" : "Padrão"}`,
  ].join("\n");
}

async function enviarParaServidor(data: UserFormData): Promise<ApiResponse> {
  const payload: any = {
    nome: data.nome,
    email: data.email,
    senha: data.senha,
    repetirSenha: data.repetirSenha,
    nivelAcesso: data.nivelAcesso,
  };
  if (data.id !== undefined) {
    payload.id = data.id;
  }
  if (data.ativo !== undefined) {
    // servidor espera 0/1
    payload.ativo = data.ativo ? 1 : 0;
  }

  const resposta = await fetch("../../usuarios/logica/salvar_usuario.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  });

  const json = (await resposta.json()) as ApiResponse;
  return json;
}

function setupPasswordToggles(): void {
  const buttons = document.querySelectorAll<HTMLButtonElement>(
    ".toggle-password"
  );

  buttons.forEach((btn) => {
    const targetId = btn.getAttribute("data-target");
    if (!targetId) return;

    const input = document.getElementById(targetId) as
      | HTMLInputElement
      | null;
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

function setupUserForm(formId: string, outputId: string): void {
  const form = document.getElementById(formId) as HTMLFormElement | null;
  const output = document.getElementById(outputId);

  if (!form || !output) {
    return;
  }

  const formEl = form as HTMLFormElement;
  const outputEl = output as HTMLElement;

  let canChangeAccess = true;
  let canDeactivate = true;

  // carregamento em caso de edição
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
            // impedir envio caso a consulta falhe
            formEl.querySelectorAll("input,select,button").forEach(el => {
              (el as HTMLInputElement).disabled = true;
            });
            return;
          }
          const u = resp.usuario;
          (formEl.elements.namedItem("nome") as HTMLInputElement).value = u.nome;
          (formEl.elements.namedItem("email") as HTMLInputElement).value = u.email;
          const nivelEl = formEl.elements.namedItem("nivelAcesso") as HTMLSelectElement;
          nivelEl.value = u.nivel_acesso === 1 ? "adm" : "padrao";

          let hidden = formEl.querySelector<HTMLInputElement>('input[name="id"]');
          if (!hidden) {
            hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "id";
            formEl.appendChild(hidden);
          }
          hidden.value = String(u.id);

          canChangeAccess = !!resp.canChangeAccess;
          canDeactivate = !!resp.canDeactivate;
          if (!canChangeAccess) {
            nivelEl.disabled = true;
          }

          // campo "ativo" já pode estar no HTML; apenas preenchemos
          const ativoEl = formEl.querySelector<HTMLInputElement>('input[name="ativo"]');
          if (ativoEl) {
            ativoEl.checked = u.ativo === 1;
            if (!canDeactivate) {
              ativoEl.disabled = true;
            }
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

    const data: UserFormData = {
      nome: String(formData.get("nome") ?? ""),
      email: String(formData.get("email") ?? ""),
      senha: String(formData.get("senha") ?? ""),
      repetirSenha: String(formData.get("repetirSenha") ?? ""),
      nivelAcesso: (formData.get("nivelAcesso") as AccessLevel) ?? "padrao",
    };

    const idVal = formData.get("id");
    if (idVal) {
      data.id = Number(idVal);
    }

    if (formData.get("ativo") !== null) {
      data.ativo = !!(formData.get("ativo") === "1" && (formEl.elements.namedItem("ativo") as HTMLInputElement).checked);
    }

    const resultado = validarUsuario(data);

    if (!resultado.valido) {
      outputEl.innerHTML =
        "<strong>Erros de validação (frontend):</strong><ul>" +
        resultado.erros
          .map((e) => `<li><strong>${e.campo}:</strong> ${e.mensagem}</li>`)
          .join("") +
        "</ul>";
      return;
    }

    // se não tiver permissão, não envie campos proibidos
    if (data.id !== undefined && !canChangeAccess) {
      delete (data as any).nivelAcesso;
    }
    if (data.id !== undefined && !canDeactivate) {
      delete (data as any).ativo;
    }

    outputEl.innerHTML = "Enviando...";

    enviarParaServidor(data)
      .then((resp) => {
        if (!resp.sucesso) {
          const errosServidor =
            resp.erros && resp.erros.length
              ? "<ul>" +
                resp.erros.map((m) => `<li>${m}</li>`).join("") +
                "</ul>"
              : "";
          outputEl.innerHTML =
            "<strong>Falha ao salvar:</strong> " +
            resp.mensagem +
            errosServidor;
          return;
        }

        const usuarioInfo = resp.usuario
          ? `ID: ${resp.usuario.id}
${formatarUsuario({
              nome: resp.usuario.nome,
              email: resp.usuario.email,
              senha: data.senha,
              repetirSenha: data.repetirSenha,
              nivelAcesso: data.nivelAcesso,
            })}`
          : formatarUsuario(data);

        outputEl.innerHTML =
          "<strong>Dados salvos com sucesso!</strong><pre>" +
          usuarioInfo +
          "</pre>";
      })
      .catch(() => {
        outputEl.innerHTML =
          "<strong>Erro inesperado ao comunicar com o servidor.</strong>";
      });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  setupPasswordToggles();
  setupUserForm("form-usuario", "resultado");
});

// faz requisição para buscar os dados de um usuário existente
async function fetchUser(id: number): Promise<GetUserResponse> {
  const resp = await fetch(`../logica/obter_usuario.php?id=${id}`);
  return (await resp.json()) as GetUserResponse;
}

export {};

