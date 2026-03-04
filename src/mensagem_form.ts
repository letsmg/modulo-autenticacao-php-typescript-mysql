// src/mensagem-form.ts

interface MensagemFormData {
  destinatario_id: string;
  mensagem: string;
}

interface RespostaAPI {
  success: boolean;
  message: string;
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-mensagem') as HTMLFormElement | null;
  const resultado = document.getElementById('resultado') as HTMLElement | null;

  if (!form || !resultado) {
    console.error('Elementos do formulário não encontrados.');
    return;
  }

  form.addEventListener('submit', async (e: Event) => {
    e.preventDefault();

    const formData = new FormData(form);
    const data: MensagemFormData = Object.fromEntries(formData.entries()) as unknown as MensagemFormData;

    try {
      const response = await fetch('../logica/salvar-mensagem.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(data as any).toString(),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const json: RespostaAPI = await response.json();

      if (json.success) {
        resultado.innerHTML = `<div class="alert alert-success">${json.message}</div>`;
        form.reset();
      } else {
        resultado.innerHTML = `<div class="alert alert-danger">${json.message}</div>`;
      }
    } catch (error: unknown) {
      const errorMessage = error instanceof Error ? error.message : 'Erro desconhecido';
      resultado.innerHTML = `<div class="alert alert-danger">Erro ao enviar: ${errorMessage}</div>`;
      console.error('Erro na requisição:', error);
    }
  });
});