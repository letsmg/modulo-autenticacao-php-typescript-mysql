<?php
/**
 * Gerenciador de sessão com timeout de inatividade de 2 minutos
 * Deve ser incluído no início de toda página que necessite autenticação
 */

ini_set('session.gc_maxlifetime', 120); // 2 minutos
session_set_cookie_params(['lifetime' => 120, 'path' => '/']);

session_start();

// Verificar timeout de inatividade
$timeout = 120; // 2 minutos em segundos
$tempo_atual = time();

if (isset($_SESSION['usuario_id'])) {
    // Usuário está logado
    if (!isset($_SESSION['ultimo_ativo'])) {
        $_SESSION['ultimo_ativo'] = $tempo_atual;
    }
    
    $ultimo_ativo = (int)$_SESSION['ultimo_ativo'];
    $tempo_inativo = $tempo_atual - $ultimo_ativo;
    
    if ($tempo_inativo > $timeout) {
        // Sessão expirada por inatividade
        session_destroy();
+        // redireciona para a página anterior, ou index se não houver referer
+        $redirect = $_SERVER['HTTP_REFERER'] ?? '/ts/index.php';
+        header('Location: ' . $redirect);
+        exit;
    }
    
    // Atualiza tempo de última atividade
    $_SESSION['ultimo_ativo'] = $tempo_atual;
} else {
    // Não autenticado, não faz nada
}
?>
