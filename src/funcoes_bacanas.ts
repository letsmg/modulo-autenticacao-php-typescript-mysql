/**
 * Preenche todos os formulários da página automaticamente
 */
export function auto_preencher_todos(): void {
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    const elementos = form.querySelectorAll("input, select, textarea");

    elementos.forEach((el) => {
      if (el instanceof HTMLInputElement) {
        switch (el.type) {
          case "email":
            el.value = `teste${Math.floor(Math.random() * 1000)}@email.com`;
            break;
          case "number":
            el.value = String(Math.floor(Math.random() * 9999));
            break;
          case "date":
            // `split` returns a string[] which might be empty, so the index access
            // has type `string | undefined`. coalesce to a safe string to satisfy
            // the `HTMLInputElement.value` type.
            el.value = new Date().toISOString().split("T")[0] ?? "";
            break;
          case "password":
            el.value = "123456";
            break;
          case "checkbox":
            el.checked = Math.random() > 0.5;
            break;
          case "radio":
            if (!el.name) return;
            const radios = document.querySelectorAll(
              `input[type="radio"][name="${el.name}"]`
            ) as NodeListOf<HTMLInputElement>;
            if (radios.length) {
              // index access can still produce `undefined` according to TS, so
              // store result and guard before using.
              const randomIndex = Math.floor(Math.random() * radios.length);
              const chosen = radios[randomIndex];
              if (chosen) {
                chosen.checked = true;
              }
            }
            break;
          case "hidden":
            break;
          default:
            el.value = Math.random().toString(36).substring(2, 10);
        }
      }

      if (el instanceof HTMLSelectElement) {
        if (el.options.length > 0) {
          el.selectedIndex = 0;
        }
      }

      if (el instanceof HTMLTextAreaElement) {
        el.value = Math.random().toString(36).substring(2, 20);
      }
    });
  });

  console.log("Formulários preenchidos.");
}

/**
 * Limpa todos os formulários da página
 */
export function auto_limpar_todos(): void {
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    const elementos = form.querySelectorAll("input, select, textarea");

    elementos.forEach((el) => {
      if (el instanceof HTMLInputElement) {
        if (el.type === "checkbox" || el.type === "radio") {
          el.checked = false;
        } else {
          el.value = "";
        }
      }

      if (el instanceof HTMLSelectElement) {
        if (el.options.length > 0) {
          el.selectedIndex = 0;
        }
      }

      if (el instanceof HTMLTextAreaElement) {
        el.value = "";
      }
    });
  });

  console.log("Formulários limpos.");
}

/**
 * 🔥 Registra eventos automaticamente quando o DOM carregar
 */
document.addEventListener("click", (event) => {
  const target = event.target as HTMLElement;

  if (target.matches("[data-seeder]")) {
    auto_preencher_todos();
  }

  if (target.matches("[data-limpar]")) {
    auto_limpar_todos();
  }
});

export function setupPasswordToggles(): void {
  const toggleButtons = document.querySelectorAll<HTMLElement>(".toggle-password");

  toggleButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const container = btn.closest(".input-group") || btn.parentElement;
      const input = container?.querySelector('input[type="password"], input[type="text"]');

      if (input instanceof HTMLInputElement) {
        const isPassword = input.type === "password";
        input.type = isPassword ? "text" : "password";

        const icon = btn.querySelector("i") || btn; 
        if (icon.classList.contains("bi") || icon.classList.contains("fa")) {
          icon.classList.toggle("bi-eye", !isPassword);
          icon.classList.toggle("bi-eye-slash", isPassword);
        }
      }
    });
  }); 
}

document.addEventListener("DOMContentLoaded", () => {
    setupPasswordToggles(); // Ativa a visibilidade de senha em todas as telas
    console.log("Funções bacanas inicializadas.");
});