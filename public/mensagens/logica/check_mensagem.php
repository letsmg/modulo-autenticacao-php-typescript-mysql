<?php
header('Content-Type: application/json; charset=utf-8');

// inclui gerenciador de sessão local
require_once __DIR__ . '/session_manager.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$id = (int) $_SESSION['usuario_id'];

// conexão inline (mesma estratégia usada em obter_usuario.php)
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

    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM mensagens WHERE destinatario_id = ? AND lida = 0");
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    echo json_encode(['count' => (int)($result['count'] ?? 0)]);
} catch (PDOException $e) {
    echo json_encode(['count' => 0]);
}

