<?php
// Start session only if not already started to avoid PHP Notice when included
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// When this file is included from the "logged" header we purposely skip the
// redirect.  cabecalho_logado.php will signal that by defining a constant.
if (!defined('SKIP_INDEX_REDIRECT')) {
    // Se usuário já está logado, redireciona para home
    if (isset($_SESSION['usuario_id'])) {
        header('Location: home.php');
        exit;
    }
}

// Base URL para links absolutos (aponta para a pasta public)
if (!isset($base_url)) {
  $base_url = 'http://localhost/ts/public';
}


?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Login | Plataforma de Criação de Sites e Desenvolvimento Web</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="description" content="Acesse sua conta na plataforma Closer. Soluções completas para desenvolvedor web full stack, front-end e back-end focadas em criação de sites profissionais." />

    <meta name="keywords" content="desenvolvedor web full stack, desenvolvedor back-end, desenvolvedor front-end, criação de sites, sistemas web, programador php" />

    <meta property="og:title" content="Desenvolvimento Web Full Stack" />
    <meta property="og:description" content="Portal de gerenciamento para criação de sites e sistemas web." />
    <meta property="og:type" content="website" />
    
    <link rel="icon" type="image/jpeg" href="<?= BASE_URL ?>/storage/imgs/icon.jpg">
    
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
  </head>
  <body class="bg-body-tertiary">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/js/bootstrap.bundle.min.js"></script>