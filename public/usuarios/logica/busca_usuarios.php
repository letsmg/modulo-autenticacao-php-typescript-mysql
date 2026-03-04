<?php
// usuarios/logica/busca_usuarios.php

// 1. Identifica o nível de quem está visualizando a tela
// 0 = Padrão, 1 = Administrador
$nivel_logado = $_SESSION['nivel_acesso'] ?? 0; 

try {
    if ($nivel_logado == 1) {
        // Se for ADM: Vê todo mundo (Adm e Padrão)
        $sql = 'SELECT id, nome, email, ativo, nivel_acesso FROM usuarios ORDER BY nome ASC';
        $stmt = $pdo->query($sql);
    } else {
        // Se for PADRÃO: Filtra para NÃO ver nenhum Administrador (nível 1)
        // Ele verá apenas usuários de nível 0 (outros padrões)
        $sql = 'SELECT id, nome, email, ativo, nivel_acesso 
                FROM usuarios 
                WHERE nivel_acesso = 0 
                ORDER BY nome ASC';
        $stmt = $pdo->query($sql);
    }

    $users = $stmt->fetchAll();

} catch (PDOException $e) {
    $users = [];
}