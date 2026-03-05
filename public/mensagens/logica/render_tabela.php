<?php
require_once '../../../config/config.php'; // Ajuste conforme seu projeto
require_once '../classes/Mensagem.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$msgService = new Mensagem($pdo);
$id_logado = $_SESSION['usuario_id'];
$mensagens = $msgService->listarRecebidas($id_logado);

if (empty($mensagens)): ?>
    <tr>
        <td colspan="4" class="text-center py-5">
            <i class="bi bi-chat-left-dots text-muted h1 d-block"></i>
            <p class="text-muted">Nenhuma mensagem por aqui.</p>
        </td>
    </tr>
<?php else: 
    foreach ($mensagens as $m): ?>
    <tr class="<?= $m['lida'] ? '' : 'table-info border-start border-info border-4' ?>">
        <td><strong><?= htmlspecialchars($m['remetente_nome']) ?></strong></td>
        <td class="text-muted"><?= mb_strimwidth(htmlspecialchars($m['texto']), 0, 50, "...") ?></td>
        <td class="small"><?= date('d/m/Y H:i', strtotime($m['data_envio'])) ?></td>
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
<?php endforeach; 
endif; ?>