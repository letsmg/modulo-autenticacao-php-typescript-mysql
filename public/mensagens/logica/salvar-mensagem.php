<?php
header('Content-Type: application/json; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';
require_once '../classes/Mensagem.php';

if (session_status() === PHP_SESSION_NONE) session_start();


$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id_destinatario = (int)($data['id_destinatario'] ?? 0);
$texto = trim($data['mensagem'] ?? '');

if ($id_destinatario > 0 && !empty($texto)) {
    $msgService = new Mensagem($pdo);
    $id_remetente = $_SESSION['usuario_id'];
    
    $sucesso = $msgService->enviar($id_remetente, $id_destinatario, $texto);
    
    echo json_encode([
        'success' => $sucesso, 
        'message' => $sucesso ? 'Mensagem enviada!' : 'Erro ao salvar no banco.'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
}