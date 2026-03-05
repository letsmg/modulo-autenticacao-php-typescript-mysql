<?php
header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';
require_once '../classes/Mensagem.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$data = json_decode(file_get_contents('php://input'), true);
$id_msg = (int)($data['id'] ?? 0);
$id_usuario = $_SESSION['usuario_id'];

// Instancia e executa
$msgService = new Mensagem($pdo);
$sucesso = $msgService->excluir($id_msg, $id_usuario);

echo json_encode([
    'sucesso' => $sucesso,
    'mensagem' => $sucesso ? 'Mensagem removida.' : 'Erro ao remover mensagem.'
]);