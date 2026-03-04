interface MensagemFormData {
  id_destinatario: number;
  mensagem: string;
}

interface RespostaAPI {
  success: boolean;
  message: string;
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-mensagem') as HTMLFormElement | null;
  const resultado = document.getElementById('resultado') as HTMLElement | null;

  if (!form || !resultado) return;

  form.addEventListener('submit', async (e: Event) => {
    e.preventDefault();

    const btnSubmit = form.querySelector('button[type="submit"]') as HTMLButtonElement;
    const selectDest = document.getElementById('id_destinatario') as HTMLSelectElement;
    const campoMsg = document.getElementById('mensagem') as HTMLTextAreaElement;

    // 1. Limpa o alerta anterior imediatamente e desabilita o botão
    resultado.classList.remove('show'); // Esconde a msg anterior com fade do Bootstrap
    resultado.innerHTML = ''; 
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    const dados: MensagemFormData = {
      id_destinatario: parseInt(selectDest.value),
      mensagem: campoMsg.value.trim()
    };

    try {
      const response = await fetch('../logica/salvar-mensagem.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
      });

      const responseText = await response.text();
      const json: RespostaAPI = JSON.parse(responseText);

      // 2. Criando o alerta com as classes de animação do Bootstrap (fade e show)
      const tipoAlerta = json.success ? 'alert-success' : 'alert-danger';
      
      resultado.innerHTML = `
        <div class="alert ${tipoAlerta} alert-dismissible fade show" role="alert">
          ${json.message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;

      if (json.success) form.reset();

    } catch (error) {
      resultado.innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          Erro ao processar a requisição.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
    } finally {
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = 'Enviar';
    }
  });
});