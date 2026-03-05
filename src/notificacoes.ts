// src/notificacoes.ts

interface RespostaCheckMensagens {
  count: number;
}

const audio = new Audio('sounds/notificacao.mp3'); // Ajuste o caminho se necessário

function showToast(message: string): void {
  const toastEl = document.createElement('div');
  toastEl.className = 'toast align-items-center text-bg-primary border-0 position-fixed top-0 end-0 m-3';
  toastEl.setAttribute('role', 'alert');
  toastEl.setAttribute('aria-live', 'assertive');
  toastEl.setAttribute('aria-atomic', 'true');
  toastEl.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
    </div>
  `;

  document.body.appendChild(toastEl);
  const toast = new bootstrap.Toast(toastEl);
  toast.show();

  // Remove após esconder (opcional, para não acumular DOM)
  toastEl.addEventListener('hidden.bs.toast', () => {
    toastEl.remove();
  });
}

async function checkMensagens(): Promise<void> {
  try {
    const response = await fetch('logica/check_mensagens.php');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data: RespostaCheckMensagens = await response.json();

    if (data.count > 0) {
      showToast(`Você tem ${data.count} nova(s) mensagem(ns)!`);
      audio.play().catch(err => console.warn('Erro ao tocar som:', err));
    }
  } catch (error: unknown) {
    console.error('Erro ao checar mensagens:', error);
  }
}

// Inicia o polling a cada 10 segundos
setInterval(checkMensagens, 10000);

// Primeira verificação imediata ao carregar a página
checkMensagens();