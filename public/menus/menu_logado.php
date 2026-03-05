<?php
/**
 * Menu único para todas as telas autenticadas (home, cadastro, editar, listar, etc.)
 * Requer sessão ativa ($_SESSION['usuario_id'])
 */


?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-primary">
  <div class="container">
    <a class="navbar-brand" href="<?= $base_url ?>/home.php">Php + Boostrap + Typescript</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Usuários</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $base_url ?>/usuarios/telas/cad_usuario.php">Novo</a></li>
            <li><a class="dropdown-item" href="<?= $base_url ?>/usuarios/telas/list_usuarios.php">Editar</a></li>
          </ul>
        </li>
        <!-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Produtos</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Novo</a></li>
            <li><a class="dropdown-item" href="#">Editar</a></li>
          </ul>
        </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Mensagens</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= $base_url ?>/mensagens/telas/env_mensagem.php">Nova</a></li>
            <li><a class="dropdown-item" href="<?= $base_url ?>/mensagens/telas/list_mensagens.php">Listar</a></li>
          </ul>
        </li>
      </ul>
      <div class="d-flex align-items-center">
        <span class="me-3">Olá, <?= $nome ?></span>
        <a class="btn btn-outline-light btn-sm" href="<?= $base_url ?>/usuarios/logica/logout.php">Sair</a>
      </div>
    </div>
  </div>
</nav>
