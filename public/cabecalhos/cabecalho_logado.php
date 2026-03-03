<?php
/**
 * Cabeçalho para páginas que exigem sessão ativa.
 * Inclui verificação de sessão e configura valores padrões de CSS.
 * Páginas podem definir $titulo_pagina antes de incluir este arquivo.
 */

// checa sessão e timeout
require_once __DIR__ . '/../usuarios/logica/session_manager.php';

// valores de CSS padrão (substituíveis pela página antes de incluir)
if (!isset($url_css_bootstrap)) {
    $url_css_bootstrap = '../../css/bootstrap.css';
}
if (!isset($url_css_custom)) {
    $url_css_custom = '../../css/custom.css';
}

// título padrão se página não definir
if (!isset($titulo_pagina)) {
    $titulo_pagina = 'Sistema';
}

// reutiliza o markup do cabeçalho independente (login/index)
require_once __DIR__ . '/cabecalho_index.php';
