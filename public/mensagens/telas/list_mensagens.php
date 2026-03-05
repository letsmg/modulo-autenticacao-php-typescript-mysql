<?php
$titulo_pagina = 'Caixa de Entrada';
require_once '../../cabecalhos/cabecalho_logado.php';

// Importa a classe Mensagem
require_once DIR_PUB . '/mensagens/classes/Mensagem.php';

// Instancia o objeto passando a conexão $pdo
$msgService = new Mensagem($pdo);

// Busca as mensagens usando o método da classe
$id_logado = $_SESSION['usuario_id'];
$mensagens = $msgService->listarRecebidas($id_logado);
?>

<?php require_once '../../menus/menu_logado.php'; ?>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h5 mb-0"><i class="bi bi-inbox-fill text-primary"></i> Mensagens Recebidas</h2>
                <div>
                    <a href="env_mensagem.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Nova Mensagem
                    </a>
                    <a href="<?= BASE_URL ?>home.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Remetente</th>
                            <th>Prévia</th>
                            <th>Data</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mensagens as $m): ?>
                            <tr class="<?= $m['lida'] ? '' : 'table-info border-start border-info border-4' ?>">
                                <td>
                                    <strong><?= htmlspecialchars($m['remetente_nome']) ?></strong>
                                </td>
                                <td class="text-muted">
                                    <?= mb_strimwidth(htmlspecialchars($m['texto']), 0, 50, "...") ?>
                                </td>
                                <td class="small">
                                    <?= date('d/m/Y H:i', strtotime($m['data_envio'])) ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="lerMensagem(<?= $m['id'] ?>, '<?= htmlspecialchars($m['remetente_nome']) ?>', '<?= htmlspecialchars(addslashes($m['texto'])) ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="excluirMensagem(<?= $m['id'] ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($mensagens)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="bi bi-chat-left-dots text-muted h1 d-block"></i>
                                    <p class="text-muted">Nenhuma mensagem por aqui.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMsg" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">De: <span id="viewRemetente"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="viewTexto" style="white-space: pre-wrap;"></p>
            </div>
        </div>
    </div>
</div>

<script>
function lerMensagem(id, remetente, texto) {
    document.getElementById('viewRemetente').innerText = remetente;
    document.getElementById('viewTexto').innerText = texto;
    new bootstrap.Modal(document.getElementById('modalMsg')).show();
    
    // Opcional: Aqui você pode disparar um fetch para marcar como lida
}

async function excluirMensagem(id) {
    if(!confirm("Excluir esta mensagem?")) return;

    try {
        const response = await fetch('../logica/excluir_mensagem.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        const res = await response.json();
        if(res.sucesso) location.reload();
        else alert(res.mensagem);
    } catch (e) {
        alert("Erro ao excluir.");
    }
}
</script>