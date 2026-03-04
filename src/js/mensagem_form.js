document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-mensagem");
  const resultado = document.getElementById("resultado");
  if (!form || !resultado) {
    console.error("Elementos do formulário não encontrados.");
    return;
  }
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    try {
      const response = await fetch("../logica/salvar-mensagem.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams(data).toString()
      });
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const json = await response.json();
      if (json.success) {
        resultado.innerHTML = `<div class="alert alert-success">${json.message}</div>`;
        form.reset();
      } else {
        resultado.innerHTML = `<div class="alert alert-danger">${json.message}</div>`;
      }
    } catch (error) {
      const errorMessage = error instanceof Error ? error.message : "Erro desconhecido";
      resultado.innerHTML = `<div class="alert alert-danger">Erro ao enviar: ${errorMessage}</div>`;
      console.error("Erro na requisição:", error);
    }
  });
});
