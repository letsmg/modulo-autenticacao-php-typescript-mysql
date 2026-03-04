<?php
/**
 * Cabeçalho para páginas que exigem sessão ativa.
 * Inclui verificação de sessão e configura valores padrões de CSS.
 * Páginas podem definir $titulo_pagina antes de incluir este arquivo.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';

// checa sessão e timeout
require_once __DIR__ . '/../usuarios/logica/session_manager.php';

// informar ao cabeçalho de índice que não queremos a lógica de redirecionamento
// (isso evita um loop quando a página atual já é home.php ou qualquer outra
// que inclua este arquivo)
define('SKIP_INDEX_REDIRECT', true);

// Se usuário **não** está logado, manda para a página de login. A verificação
// pode usar $_SESSION agora porque session_manager já chamou session_start().
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
} else {
    // nome será exibido no menu
    $nome = htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário');
}

// Base URL para links absolutos (aponta para a pasta public)
if (!isset($base_url)) {
  $base_url = 'http://localhost/ts/public';
}


// valores de CSS padrão (substituíveis pela página antes de incluir)
// if (!isset($url_css_bootstrap)) {
//     $url_css_bootstrap = $base_url . '/css/bootstrap.css';
// }
// if (!isset($url_css_custom)) {
//     $url_css_custom = $base_url . '/css/custom.css';
// }

// // título padrão se página não definir
// if (!isset($titulo_pagina)) {
//     $titulo_pagina = 'Sistema';
// }


?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= $base_url ?>/css/bootstrap.css" />
    <link rel="stylesheet" href="<?= $base_url ?>/css/custom.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
  </head>
  <body class="bg-body-tertiary">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/js/bootstrap.bundle.min.js"></script>