<?php
// usuarios/logica/busca_usuarios.php


try {
    // 2. Executa a query usando a variável $pdo que veio do arquivo importado
    $stmt = $pdo->query('SELECT id, nome, email, ativo FROM usuarios ORDER BY id DESC');
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    // Se der erro na query, retornamos um array vazio para não quebrar o foreach na tela
    $users = [];
    // Opcional: erro_log($e->getMessage());
}