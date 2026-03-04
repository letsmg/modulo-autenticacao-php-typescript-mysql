<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Sessão encerrada.']);
    exit;
}

// Configurações do Banco
$host = 'localhost';
$db   = 'ts';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados vazios.']);
    exit;
}

$id = isset($data['id']) ? intval($data['id']) : 0;

// 1. Cenário: Apenas Status (Botão da Lista)
if ($id > 0 && isset($data['ativo']) && !isset($data['nome'])) {
    $stmt = $pdo->prepare("UPDATE usuarios SET ativo = ? WHERE id = ?");
    $stmt->execute([$data['ativo'], $id]);
    echo json_encode(['sucesso' => true, 'mensagem' => 'Status atualizado!']);
    exit;
}

// 2. Mapeamento de Nível (ADM = 1, Padrão = 0)
// Se o JS enviar 'adm' ou 1, salva 1. Caso contrário, 0.
$nivelFinal = (isset($data['nivelAcesso']) && ($data['nivelAcesso'] === 'adm' || $data['nivelAcesso'] == 1)) ? 1 : 0;

// 3. Cenário: Edição ou Cadastro
if ($id > 0) {
    // UPDATE
    $sql = "UPDATE usuarios SET nome = :nome, email = :email, nivel_acesso = :nivel";
    $params = [
        ':nome'  => $data['nome'],
        ':email' => $data['email'],
        ':nivel' => $nivelFinal,
        ':id'    => $id
    ];

    if (!empty($data['senha'])) {
        $sql .= ", senha = :senha";
        $params[':senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
    }

    if (isset($data['ativo'])) {
        $sql .= ", ativo = :ativo";
        $params[':ativo'] = $data['ativo'];
    }

    $sql .= " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $msg = "Usuário atualizado!";
} else {
    // INSERT
    $sql = "INSERT INTO usuarios (nome, email, senha, nivel_acesso, ativo) VALUES (:nome, :email, :senha, :nivel, :ativo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome'  => $data['nome'],
        ':email' => $data['email'],
        ':senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
        ':nivel' => $nivelFinal,
        ':ativo' => 1
    ]);
    $msg = "Usuário cadastrado!";
}

echo json_encode(['sucesso' => true, 'mensagem' => $msg]);