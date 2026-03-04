const audio = new Audio("sounds/notificacao.mp3");
function showToast(message) {
  const toastEl = document.createElement("div");
  toastEl.className = "toast align-items-center text-bg-primary border-0 position-fixed top-0 end-0 m-3";
  toastEl.setAttribute("role", "alert");
  toastEl.setAttribute("aria-live", "assertive");
  toastEl.setAttribute("aria-atomic", "true");
  toastEl.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
    </div>
  `;
  document.body.appendChild(toastEl);
  const toast = new bootstrap.Toast(toastEl);
  toast.show();
  toastEl.addEventListener("hidden.bs.toast", () => {
    toastEl.remove();
  });
}
async function checkMensagens() {
  try {
    const response = await fetch("usuarios/logica/check-mensagens.php");
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    if (data.count > 0) {
      showToast(`Você tem ${data.count} nova(s) mensagem(ns)!`);
      audio.play().catch((err) => console.warn("Erro ao tocar som:", err));
    }
  } catch (error) {
    console.error("Erro ao checar mensagens:", error);
  }
}
setInterval(checkMensagens, 1e4);
checkMensagens();
