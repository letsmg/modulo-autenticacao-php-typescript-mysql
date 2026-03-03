/**
 * Funcionalidades úteis reutilizáveis para o projeto
 */

interface DadosFormulario {
  [chave: string]: string | number | boolean | null | undefined;
}

/**
 * Preenche todos os campos de um formulário com dados válidos.
 * 
 * Funcionalidades:
 * - Popula inputs de texto, email, password, number, etc.
 * - Seleciona o primeiro item (option) em selects (combobox) caso não haja valor
 * - Marca/desmarca checkboxes conforme booleano
 * - Preenche textareas
 * - Ignora campos ocultos (hidden) sem valor
 * 
 * @param formId ID do formulário a preencher
 * @param dados Objeto com dados { nome_campo: valor }
 * @returns true se preencheu com sucesso, false se formulário não encontrado
 */
export function preenche_campos(formId: string, dados: DadosFormulario): boolean {
  const form = document.getElementById(formId) as HTMLFormElement | null;

  if (!form) {
    console.warn(`Formulário com ID "${formId}" não encontrado.`);
    return false;
  }

  // Itera sobre todos os elementos do formulário
  Array.from(form.elements).forEach((el) => {
    const input = el as HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement;
    const name = input.name;

    if (!name) return; // Ignora elementos sem name

    // Se há valor nos dados para este campo, usa-o
    const valor = dados[name];

    // INPUT
    if (input instanceof HTMLInputElement) {
      if (input.type === "checkbox") {
        // Checkbox: marca se valor for true
        input.checked = valor === true || valor === "1" || valor === 1;
      } else if (input.type === "radio") {
        // Radio: marca se valor corresponder ao value do radio
        input.checked = String(valor) === input.value;
      } else {
        // Text, email, password, number, etc.
        input.value = valor !== undefined && valor !== null ? String(valor) : "";
      }
    }

    // SELECT (COMBOBOX)
    else if (input instanceof HTMLSelectElement) {
      if (valor !== undefined && valor !== null) {
        // Se há valor, seleciona a opção que corresponde
        input.value = String(valor);
      } else {
        // Caso não haja valor, seleciona o primeiro item (se existir)
        if (input.options.length > 0) {
          input.selectedIndex = 0;
        }
      }
    }

    // TEXTAREA
    else if (input instanceof HTMLTextAreaElement) {
      input.value = valor !== undefined && valor !== null ? String(valor) : "";
    }
  });

  return true;
}

/**
 * Limpa todos os campos de um formulário.
 * 
 * @param formId ID do formulário a limpar
 * @returns true se limpou com sucesso, false se formulário não encontrado
 */
export function limpa_campos(formId: string): boolean {
  const form = document.getElementById(formId) as HTMLFormElement | null;

  if (!form) {
    console.warn(`Formulário com ID "${formId}" não encontrado.`);
    return false;
  }

  Array.from(form.elements).forEach((el) => {
    const input = el as HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement;

    if (input instanceof HTMLInputElement) {
      if (input.type === "checkbox" || input.type === "radio") {
        input.checked = false;
      } else {
        input.value = "";
      }
    } else if (input instanceof HTMLSelectElement) {
      if (input.options.length > 0) {
        input.selectedIndex = 0;
      }
    } else if (input instanceof HTMLTextAreaElement) {
      input.value = "";
    }
  });

  return true;
}

/**
 * Coleta todos os dados de um formulário em um objeto.
 * 
 * @param formId ID do formulário
 * @returns Objeto com { nome_campo: valor }
 */
export function coleta_campos(formId: string): DadosFormulario {
  const form = document.getElementById(formId) as HTMLFormElement | null;
  const dados: DadosFormulario = {};

  if (!form) {
    console.warn(`Formulário com ID "${formId}" não encontrado.`);
    return dados;
  }

  Array.from(form.elements).forEach((el) => {
    const input = el as HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement;
    const name = input.name;

    if (!name) return;

    if (input instanceof HTMLInputElement) {
      if (input.type === "checkbox") {
        dados[name] = input.checked;
      } else if (input.type === "radio") {
        if (input.checked) {
          dados[name] = input.value;
        }
      } else {
        dados[name] = input.value;
      }
    } else if (input instanceof HTMLSelectElement) {
      dados[name] = input.value;
    } else if (input instanceof HTMLTextAreaElement) {
      dados[name] = input.value;
    }
  });

  return dados;
}

export {};
