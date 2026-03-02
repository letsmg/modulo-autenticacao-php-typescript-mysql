// Exemplos de TypeScript para formulário de cadastro

// 1. Tipo especial (union type) para nível de acesso
type AccessLevel = "adm" | "padrao";

// 2. Interface para os dados do formulário
interface UserFormData {
  nome: string;
  email: string;
  senha: string;
  repetirSenha: string;
  nivelAcesso: AccessLevel;
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
  };
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
  const payload = {
    nome: data.nome,
    email: data.email,
    senha: data.senha,
    nivelAcesso: data.nivelAcesso,
  };

  const resposta = await fetch("salvar_usuario.php", {
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

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const formData = new FormData(form);

    const data: UserFormData = {
      nome: String(formData.get("nome") ?? ""),
      email: String(formData.get("email") ?? ""),
      senha: String(formData.get("senha") ?? ""),
      repetirSenha: String(formData.get("repetirSenha") ?? ""),
      nivelAcesso: (formData.get("nivelAcesso") as AccessLevel) ?? "padrao",
    };

    const resultado = validarUsuario(data);

    if (!resultado.valido) {
      output.innerHTML =
        "<strong>Erros de validação (frontend):</strong><ul>" +
        resultado.erros
          .map((e) => `<li><strong>${e.campo}:</strong> ${e.mensagem}</li>`)
          .join("") +
        "</ul>";
      return;
    }

    output.innerHTML = "Enviando...";

    enviarParaServidor(data)
      .then((resp) => {
        if (!resp.sucesso) {
          const errosServidor =
            resp.erros && resp.erros.length
              ? "<ul>" +
                resp.erros.map((m) => `<li>${m}</li>`).join("") +
                "</ul>"
              : "";
          output.innerHTML =
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

        output.innerHTML =
          "<strong>Cadastro realizado com sucesso!</strong><pre>" +
          usuarioInfo +
          "</pre>";
      })
      .catch(() => {
        output.innerHTML =
          "<strong>Erro inesperado ao comunicar com o servidor.</strong>";
      });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  setupPasswordToggles();
  setupUserForm("form-usuario", "resultado");
});

