type AccessLevel = "adm" | "padrao";

interface UserFormData {
  id?: number;
  nome?: string;
  email?: string;
  senha?: string;
  repetirSenha?: string;
  nivelAcesso?: AccessLevel;
  ativo?: boolean;
}

// --- Funções Auxiliares de UI ---
function exibirMensagem(container: HTMLElement, texto: string, tipo: 'success' | 'danger' | 'warning' | 'info') {
  container.innerHTML = `
    <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
      ${texto}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;
}

// --- Validação ---
function validarUsuario(data: UserFormData) {
  const erros: string[] = [];
  const isStatusOnly = data.id !== undefined && data.ativo !== undefined && !data.nome;
  
  if (isStatusOnly) return { valido: true, erros: [] };

  if (!data.nome?.trim()) erros.push("O <strong>Nome</strong> é obrigatório.");
  if (!data.email?.trim()) erros.push("O <strong>E-mail</strong> é obrigatório.");
  
  if (data.id === undefined && !data.senha) {
    erros.push("A <strong>Senha</strong> é obrigatória para novos cadastros.");
  }

  if (data.senha && data.senha.length < 6) {
    erros.push("A senha deve ter no mínimo <strong>6 caracteres</strong>.");
  }

  if (data.senha !== data.repetirSenha) {
    erros.push("As senhas digitadas <strong>não conferem</strong>.");
  }

  return { valido: erros.length === 0, erros };
}

// --- Envio ---
async function enviarParaServidor(data: UserFormData) {
  const payload: any = { ...data };
  payload.nivelAcesso = data.nivelAcesso === "adm" ? 1 : 0;
  if (data.ativo !== undefined) payload.ativo = data.ativo ? 1 : 0;

  const resp = await fetch("../logica/salvar_usuario.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
  });
  return await resp.json();
}

// --- Lógica Principal ---
function setupUserForm(formId: string, outputId: string) {
  const form = document.getElementById(formId) as HTMLFormElement;
  const output = document.getElementById(outputId) as HTMLElement;
  if (!form || !output) return;

  async function tryLoad() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("id");
    if (!id) return;

    output.innerHTML = `
      <div class="d-flex align-items-center text-primary mb-3">
        <div class="spinner-border spinner-border-sm me-2"></div>
        <strong>Carregando dados do usuário...</strong>
      </div>`;

    try {
      // Nome do arquivo corrigido para obter_usuario.php
      const res = await fetch(`../logica/obter_usuario.php?id=${id}`);
      
      if (!res.ok) throw new Error(`Erro ${res.status}: Não foi possível contatar o servidor.`);
      
      const json = await res.json();

      if (json.sucesso && json.usuario) {
        const u = json.usuario;
        (form.elements.namedItem("nome") as HTMLInputElement).value = u.nome || "";
        (form.elements.namedItem("email") as HTMLInputElement).value = u.email || "";
        
        const nivelField = form.elements.namedItem("nivelAcesso") as HTMLSelectElement;
        if (nivelField) {
            nivelField.value = (u.nivel_acesso == 1) ? "adm" : "padrao";
        }

        let hidden = form.querySelector('input[name="id"]') as HTMLInputElement;
        if (!hidden) {
          hidden = document.createElement("input");
          hidden.type = "hidden";
          hidden.name = "id";
          form.appendChild(hidden);
        }
        hidden.value = String(u.id);

        const ativoEl = form.querySelector('input[name="ativo"]') as HTMLInputElement;
        if (ativoEl) {
          ativoEl.checked = (u.ativo == 1);
          ativoEl.closest(".form-check")?.classList.remove("d-none");
        }
        output.innerHTML = ""; 
      } else {
        exibirMensagem(output, json.mensagem || "Usuário não encontrado.", "danger");
      }
    } catch (e: any) {
      exibirMensagem(output, `<strong>Erro de Conexão:</strong> ${e.message}`, "danger");
    }
  }

  tryLoad();

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    const data: UserFormData = {
      nome: fd.get("nome")?.toString(),
      email: fd.get("email")?.toString(),
      senha: fd.get("senha")?.toString(),
      repetirSenha: fd.get("repetirSenha")?.toString(),
      nivelAcesso: (fd.get("nivelAcesso") as AccessLevel),
      id: fd.get("id") ? Number(fd.get("id")) : undefined,
      ativo: form.elements.namedItem("ativo") ? (form.elements.namedItem("ativo") as HTMLInputElement).checked : undefined
    };

    const v = validarUsuario(data);
    if (!v.valido) {
      exibirMensagem(output, v.erros.join("<br>"), "warning");
      return;
    }

    output.innerHTML = `
      <div class="d-flex align-items-center text-info mb-3">
        <div class="spinner-border spinner-border-sm me-2"></div>
        Salvando alterações...
      </div>`;

    try {
      const res = await enviarParaServidor(data);
      if (res.sucesso) {
        exibirMensagem(output, `<strong>Sucesso!</strong> ${res.mensagem}`, "success");
        if (!data.id) form.reset();
      } else {
        exibirMensagem(output, `<strong>Erro:</strong> ${res.mensagem}`, "danger");
      }
    } catch (err) {
      exibirMensagem(output, "<strong>Erro fatal:</strong> Não foi possível salvar os dados.", "danger");
    }
  });
}

document.addEventListener("DOMContentLoaded", () => {
  setupUserForm("form-usuario", "resultado");
});

export {};