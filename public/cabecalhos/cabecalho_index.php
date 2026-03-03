<?php
/**
 * Cabeçalho padrão para a página de login (index) e outras não autenticadas.
 * Deve ser incluído após a definição de $titulo_pagina e URLs de CSS.
 */

if (!isset($titulo_pagina)) {
    $titulo_pagina = 'Sistema';
}
// Base URL para links absolutos (aponta para a pasta public)
if (!isset($base_url)) {
  $base_url = 'http://localhost/ts/public';
}

// URL raiz do projeto (acesso fora de public, ex: storage)
if (!isset($raiz)) {
  $raiz = 'http://localhost/ts';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($titulo_pagina) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= $base_url ?>/css/bootstrap.css" />
    <link rel="stylesheet" href="<?= $base_url ?>/css/custom.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
  </head>
  <body class="bg-body-tertiary">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      console.log('bootstrap loaded', typeof bootstrap);
      if (typeof bootstrap === 'undefined') {
        // tentamos carregar uma cópia local, caso o CDN não esteja acessível
        var s = document.createElement('script');
        s.src = '/ts/js/bootstrap.bundle.min.js';
        document.head.appendChild(s);
      }
    </script>