<?php
// Título e inclusões
$titulo_pagina = 'Listar usuários';
require_once '../../cabecalhos/cabecalho_logado.php';
require_once '../logica/busca_usuarios.php';

// Pegamos o ID do usuário logado para a comparação
$id_logado = $_SESSION['usuario_id'] ?? 0;
?>
    <?php require_once '../../menus/menu_logado.php'; ?>

    <div class="container py-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Usuários</h2>
            <a href="<?= $base_url ?>/home.php" class="btn btn-secondary btn-sm">Voltar</a>
          </div>
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th class="text-center">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
                <tr>
                  <td><?= htmlspecialchars($u['id']) ?></td>
                  <td><?= htmlspecialchars($u['nome']) ?></td>
                  <td><?= htmlspecialchars($u['email']) ?></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a class="btn btn-sm btn-primary" href="edit_usuario.php?id=<?= urlencode($u['id']) ?>">
                        <i class="bi bi-pencil"></i> Editar
                      </a>
                      
                      <?php if ($u['id'] != $id_logado): ?>
                        <?php if ($u['ativo']): ?>
                          <button class="btn btn-sm btn-danger btn-toggle-status" data-id="<?= $u['id'] ?>" data-acao="desativar">
                            <i class="bi bi-person-x"></i> Desativar
                          </button>
                        <?php else: ?>
                          <button class="btn btn-sm btn-success btn-toggle-status" data-id="<?= $u['id'] ?>" data-acao="ativar">
                            <i class="bi bi-person-check"></i> Ativar
                          </button>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="badge text-bg-light border ms-2 d-flex align-items-center">Você</span>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>

              <?php if (empty($users)): ?>
                <tr><td colspan="4" class="text-center">Nenhum usuário encontrado.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <script>
    document.querySelectorAll('.btn-toggle-status').forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.dataset.id;
            const acao = this.dataset.acao;
            const novoStatus = (acao === 'ativar' ? 1 : 0);

            if (confirm(`Tem certeza que deseja ${acao} este usuário?`)) {
                try {
                    const response = await fetch('../logica/salvar_usuario.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id, ativo: novoStatus })
                    });
                    const res = await response.json();
                    if (res.sucesso) {
                        location.reload(); // Recarrega para atualizar o botão
                    } else {
                        alert('Erro: ' + res.mensagem);
                    }
                } catch (e) {
                    alert('Erro ao processar requisição.');
                }
            }
        });
    });
    </script>
  </body>
</html>