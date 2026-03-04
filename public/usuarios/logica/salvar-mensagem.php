<?php
header('Content-Type: application/json; charset=utf-8');

// inclui gerenciador de sessão local
require_once __DIR__ . '/session_manager.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remetente_id = (int) $_SESSION['usuario_id'];
    $destinatario_id = intval($_POST['destinatario_id'] ?? 0);
    $mensagem = trim($_POST['mensagem'] ?? '');

    if ($destinatario_id <= 0 || empty($mensagem)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
        exit;
    }

    // conexão inline
    $host = 'localhost';
    $db   = 'ts';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $stmt = $pdo->prepare("INSERT INTO mensagens (remetente_id, destinatario_id, mensagem) VALUES (?, ?, ?)");
        $stmt->execute([$remetente_id, $destinatario_id, $mensagem]);
        echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao enviar mensagem: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
}