<?php
header('Content-Type: application/json; charset=utf-8');

// Sessão para "login"
session_start();

// Configuração de conexão
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
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao conectar no banco de dados.',
    ]);
    exit;
}

$entrada = file_get_contents('php://input');
$dados = json_decode($entrada, true);

if (!is_array($dados)) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Dados inválidos.',
    ]);
    exit;
}

$email = trim($dados['email'] ?? '');
$senha = (string)($dados['senha'] ?? '');

if ($email === '' || $senha === '') {
    http_response_code(422);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'E-mail e senha são obrigatórios.',
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'SELECT id, nome, email, senha, nivel_acesso, ativo
         FROM usuarios
         WHERE email = :email
         LIMIT 1'
    );
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        http_response_code(401);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Credenciais inválidas.',
        ]);
        exit;
    }

    if ((int)$usuario['ativo'] !== 1) {
        http_response_code(403);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Usuário inativo/bloqueado.',
        ]);
        exit;
    }

    if (!password_verify($senha, $usuario['senha'])) {
        http_response_code(401);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Credenciais inválidas.',
        ]);
        exit;
    }

    // Login ok
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int)$usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['nivel_acesso'] = (int)$usuario['nivel_acesso'];

    // Atualiza último login
    $upd = $pdo->prepare('UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id');
    $upd->execute([':id' => (int)$usuario['id']]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Login realizado com sucesso.',
        'usuario' => [
            'id' => (int)$usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'nivel_acesso' => (int)$usuario['nivel_acesso'],
        ],
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao realizar login.',
    ]);
}

