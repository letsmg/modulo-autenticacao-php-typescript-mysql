<?php
// 1. Define o retorno como JSON
header('Content-Type: application/json; charset=utf-8');

// 2. Carrega as configurações e a conexão (Ajuste o caminho se necessário)
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';

// 3. Inicia sessão para validar se o usuário está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Pega o ID enviado pelo Fetch
$entrada = file_get_contents('php://input');
$dados = json_decode($entrada, true);
$id = isset($dados['id']) ? (int)$dados['id'] : 0;

if ($id <= 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID de usuário inválido.']);
    exit;
}

// 5. Impede que o usuário exclua a si mesmo
if ($id === (int)($_SESSION['usuario_id'] ?? 0)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Você não pode excluir sua própria conta.']);
    exit;
}

try {
    // Usamos o $pdo que vem do config.php -> conecta_mysql.php
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário excluído com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não encontrado ou já excluído.']);
    }
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no banco: ' . $e->getMessage()]);
}