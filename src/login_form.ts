type LoginPayload = {
  email: string;
  senha: string;
};

type LoginResponse =
  | {
      sucesso: true;
      mensagem: string;
      usuario: {
        id: number;
        nome: string;
        email: string;
        nivel_acesso: number;
      };
    }
  | {
      sucesso: false;
      mensagem: string;
    };

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

async function login(payload: LoginPayload): Promise<LoginResponse> {
  const resposta = await fetch("usuarios/logica/login_usuario.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  });

  return (await resposta.json()) as LoginResponse;
}

function setupLoginForm(formId: string, outputId: string): void {
  const form = document.getElementById(formId) as HTMLFormElement | null;
  const output = document.getElementById(outputId);

  if (!form || !output) return;

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const formData = new FormData(form);
    const email = String(formData.get("email") ?? "").trim();
    const senha = String(formData.get("senha") ?? "");

    if (!email || !senha) {
      output.innerHTML =
        '<div class="alert alert-warning mb-0">Preencha e-mail e senha.</div>';
      return;
    }

    output.innerHTML =
      '<div class="text-muted">Entrando...</div>';

    login({ email, senha })
      .then((resp) => {
        if (!resp.sucesso) {
          output.innerHTML = `<div class="alert alert-danger mb-0">${resp.mensagem}</div>`;
          return;
        }

        output.innerHTML = `<div class="alert alert-success mb-0">${resp.mensagem}</div>`;
        // após login redireciona para edição do próprio usuário
        if (resp.sucesso && resp.usuario) {
          window.location.href = 'home.php';
        }
      })
      .catch(() => {
        output.innerHTML =
          '<div class="alert alert-danger mb-0">Erro inesperado ao comunicar com o servidor.</div>';
      });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  setupPasswordToggles();
  setupLoginForm("form-login", "resultado-login");
});

export {};

