let totalAntigo = 0;

async function atualizarNotificacoes() {
    try {
        const response = await fetch('/ts/public/mensagens/logica/verificar_novas.php');
        const data = await response.json();
        
        const badge = document.getElementById('notificacao-badge');
        const totalAtual = parseInt(data.total);

        // 1. Atualiza o Badge do Menu
        if (badge) {
            badge.innerText = totalAtual > 0 ? totalAtual : "";
            badge.style.display = totalAtual > 0 ? 'inline-block' : 'none';
        }

        // 2. Verifica se houve mudança e se estamos na página de listagem
        const containerTabela = document.querySelector('#tabela-mensagens tbody');
        
        if (totalAtual !== totalAntigo) {
            // Se houver novas mensagens e a tabela existir, atualiza o HTML
            if (containerTabela) {
                const resTabela = await fetch('/ts/public/mensagens/logica/render_tabela.php');
                const htmlTabela = await resTabela.text();
                containerTabela.innerHTML = htmlTabela;
            }

            // Toca som se o número aumentou
            if (totalAtual > totalAntigo) {
                new Audio('/ts/storage/sons/alerta.mp3').play().catch(() => {});
            }
        }

        totalAntigo = totalAtual;
    } catch (e) {
        console.error("Erro na atualização automática");
    }
}

setInterval(atualizarNotificacoes, 10000);