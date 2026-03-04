<?php
// cuidados com sessão realizados pelo cabeçalho logado

$titulo_pagina = 'Listar usuários';
require_once '../../cabecalhos/cabecalho_logado.php';
require_once '../logica/busca_usuarios.php';
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
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
                <tr>
                  <td><?= htmlspecialchars($u['id']) ?></td>
                  <td><?= htmlspecialchars($u['nome']) ?></td>
                  <td><?= htmlspecialchars($u['email']) ?></td>
                  <td><a class="btn btn-sm btn-primary" href="edit_usuario.php?id=<?= urlencode($u['id']) ?>">Editar</a></td>
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

  </body>
  </html>
