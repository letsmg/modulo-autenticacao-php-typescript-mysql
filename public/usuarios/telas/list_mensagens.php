<?php
$titulo_pagina = 'Minhas Mensagens';
require_once '../../cabecalhos/cabecalho_logado.php';

// Caminho físico para a lógica
require_once PATH_RAIZ . '/public/mensagens/logica/busca_mensagens.php';
?>

<?php require_once '../../menus/menu_logado.php'; ?>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h5 mb-0"><i class="bi bi-envelope-fill"></i> Caixa de Entrada</h2>
                <a href="<?= BASE_URL ?>/home.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Remetente</th>
                            <th>Mensagem</th>
                            <th>Data</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mensagens as $m): ?>
                            <tr class="<?= $m['lida'] ? '' : 'table-info fw-bold' ?>">
                                <td><?= htmlspecialchars($m['remetente_nome']) ?></td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 300px;">
                                        <?= htmlspecialchars($m['texto']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($m['data_envio'])) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary btn-ler" 
                                                data-id="<?= $m['id'] ?>" 
                                                data-texto="<?= htmlspecialchars($m['texto']) ?>"
                                                data-nome="<?= htmlspecialchars($m['remetente_nome']) ?>">
                                            <i class="bi bi-eye"></i> Ler
                                        </button>
                                        
                                        <button class="btn btn-sm btn-outline-danger btn-excluir-msg" data-id="<?= $m['id'] ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($mensagens)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Sua caixa de entrada está vazia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMensagem" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">De: <span id="modalRemetente"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalTexto"></p>
            </div>
        </div>
    </div>
</div>

<script>
// Lógica para abrir o modal de leitura
document.querySelectorAll('.btn-ler').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('modalRemetente').innerText = this.dataset.nome;
        document.getElementById('modalTexto').innerText = this.dataset.texto;
        
        const modal = new bootstrap.Modal(document.getElementById('modalMensagem'));
        modal.show();
        
        // Opcional: Aqui você pode disparar um fetch para marcar como lida no banco
    });
});

// Lógica para excluir mensagem
document.querySelectorAll('.btn-excluir-msg').forEach(btn => {
    btn.addEventListener('click', async function() {
        if (confirm('Deseja apagar esta mensagem?')) {
            const id = this.dataset.id;
            try {
                const response = await fetch('<?= BASE_URL ?>/mensagens/logica/excluir_mensagem.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const res = await response.json();
                if (res.sucesso) location.reload();
            } catch (e) {
                alert('Erro ao excluir mensagem.');
            }
        }
    });
});
</script>

</body>
</html>