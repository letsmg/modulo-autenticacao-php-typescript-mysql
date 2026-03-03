<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
$nome = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário');
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home</title>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/custom.css" />
  </head>
  <body class="bg-body-tertiary">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-primary">
      <div class="container">
        <a class="navbar-brand" href="#">Painel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Usuários</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="usuarios/telas/cadastro.php">Novo</a></li>
                <li><a class="dropdown-item" href="usuarios/telas/listar_usuarios.php">Editar</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Produtos</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Novo</a></li>
                <li><a class="dropdown-item" href="#">Editar</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Mensagens</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Nova</a></li>
                <li><a class="dropdown-item" href="#">Editar</a></li>
              </ul>
            </li>
          </ul>
          <div class="d-flex align-items-center">
            <span class="me-3">Olá, <?= $nome ?></span>
            <a class="btn btn-outline-secondary btn-sm" href="index.php">Sair</a>
          </div>
        </div>
      </div>
    </nav>

    <div class="container py-4">
      <h2 class="h5">Bem-vindo</h2>
      <p class="text-muted">Use o menu para navegar entre as telas administrativas.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  </html>
