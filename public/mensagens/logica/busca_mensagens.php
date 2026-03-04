<?php
// O $pdo já deve estar disponível via config.php
$id_logado = $_SESSION['usuario_id'] ?? 0;

try {
    // Busca mensagens recebidas e o nome de quem enviou (JOIN com a tabela usuarios)
    $sql = "SELECT m.*, u.nome AS remetente_nome 
            FROM mensagens m
            JOIN usuarios u ON m.id_remetente = u.id
            WHERE m.id_destinatario = :id_logado
            ORDER BY m.data_envio DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_logado' => $id_logado]);
    $mensagens = $stmt->fetchAll();
} catch (PDOException $e) {
    $mensagens = [];
}