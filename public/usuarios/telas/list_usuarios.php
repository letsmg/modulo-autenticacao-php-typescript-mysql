<?php
// Título e inclusões
$titulo_pagina = 'Listar usuários';
require_once '../../cabecalhos/cabecalho_logado.php';

// A busca_usuarios.php já deve filtrar para que usuários comuns não vejam ADMs
require_once DIR_PUB.'/usuarios/logica/busca_usuarios.php';

// Pegamos dados da sessão para controle de interface
$id_logado = $_SESSION['usuario_id'] ?? 0;
$nivel_logado = (int)($_SESSION['nivel_acesso'] ?? 0); // 1 = ADM, 0 = PADRÃO
?>

<?php require_once '../../menus/menu_logado.php'; ?>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h5 mb-0"><i class="bi bi-people-fill"></i> Gerenciamento de Usuários</h2>
                <div>
                    <?php if ($nivel_logado === 1): ?>
                        <a href="<?= BASE_URL ?>usuarios/telas/cad_usuario.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Novo Usuário
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>home.php" class="btn btn-secondary btn-sm">Voltar</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><span class="badge text-bg-light border">#<?= $u['id'] ?></span></td>
                                <td><?= htmlspecialchars($u['nome']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        
                                        <?php if ($nivel_logado === 1): ?>
                                            <a class="btn btn-sm btn-outline-warning" href="edit_usuario.php?id=<?= $u['id'] ?>" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <?php if ($u['id'] != $id_logado): ?>
                                                <?php if ($u['ativo']): ?>
                                                    <button class="btn btn-sm btn-outline-secondary btn-toggle-status" data-id="<?= $u['id'] ?>" data-acao="desativar" title="Desativar">
                                                        <i class="bi bi-person-x"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-outline-success btn-toggle-status" data-id="<?= $u['id'] ?>" data-acao="ativar" title="Ativar">
                                                        <i class="bi bi-person-check"></i>
                                                    </button>
                                                <?php endif; ?>

                                                <button class="btn btn-sm btn-outline-danger btn-excluir" data-id="<?= $u['id'] ?>" data-nome="<?= htmlspecialchars($u['nome']) ?>" title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="badge text-bg-primary ms-2">Você</span>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <?php if ($u['id'] == $id_logado): ?>
                                                <a class="btn btn-sm btn-primary" href="edit_usuario.php?id=<?= $u['id'] ?>">
                                                    <i class="bi bi-person-circle"></i> Meu Perfil
                                                </a>
                                            <?php else: ?>
                                                <span class="badge text-bg-light border text-secondary">Somente leitura</span>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Nenhum usuário disponível para seu nível de acesso.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Lógica para Ativar/Desativar (Apenas ADM conseguirá processar no PHP)
document.querySelectorAll('.btn-toggle-status').forEach(button => {
    button.addEventListener('click', async function() {
        const id = this.dataset.id;
        const acao = this.dataset.acao;
        const novoStatus = (acao === 'ativar' ? 1 : 0);

        if (confirm(`Deseja realmente ${acao} este usuário?`)) {
            try {
                const response = await fetch('../logica/salvar_usuario.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id, ativo: novoStatus })
                });
                const res = await response.json();
                if (res.sucesso) {
                    location.reload();
                } else {
                    alert('Erro: ' + res.mensagem);
                }
            } catch (e) {
                alert('Erro ao processar requisição.');
            }
        }
    });
});

// Lógica para Excluir (Apenas ADM conseguirá processar no PHP)
document.querySelectorAll('.btn-excluir').forEach(button => {
    button.addEventListener('click', async function() {
        const id = this.dataset.id;
        const nome = this.dataset.nome;

        if (confirm(`ATENÇÃO: Deseja realmente EXCLUIR o usuário "${nome}"? Esta ação é permanente.`)) {
            try {
                const response = await fetch('../logica/excluir_usuario.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const res = await response.json();
                if (res.sucesso) {
                    alert(res.mensagem);
                    location.reload();
                } else {
                    alert('Erro: ' + res.mensagem);
                }
            } catch (e) {
                alert('Erro ao processar a exclusão.');
            }
        }
    });
});
</script>
<script type="module" src="<?= BASE_URL ?>/js/funcoes_bacanas.min.js"></script>
<script type="module" src="<?= BASE_URL ?>/js/notificacoes.min.js"></script>
</body>
</html>