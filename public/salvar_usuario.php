<?php
header('Content-Type: application/json; charset=utf-8');

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
// Lê JSON enviado pelo fetch
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

$nome         = trim($dados['nome'] ?? '');
$email        = trim($dados['email'] ?? '');
$senha        = (string)($dados['senha'] ?? '');
$nivelAcesso  = $dados['nivelAcesso'] ?? 'padrao';

// Validações básicas (a lógica principal já foi feita no TS)
$erros = [];

if ($nome === '') {
    $erros[] = 'Nome é obrigatório.';
}

if ($email === '') {
    $erros[] = 'E-mail é obrigatório.';
}

if (strlen($senha) < 6) {
    $erros[] = 'Senha deve ter pelo menos 6 caracteres.';
}

if (!in_array($nivelAcesso, ['adm', 'padrao'], true)) {
    $erros[] = 'Nível de acesso inválido.';
}

if ($erros) {
    http_response_code(422);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erros de validação no servidor.',
        'erros' => $erros,
    ]);
    exit;
}

// Mapeia nível de acesso para número
$nivelNumero = $nivelAcesso === 'adm' ? 1 : 0;

// Hash da senha (nunca salve senha em texto puro)
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (nome, email, senha, nivel_acesso)
         VALUES (:nome, :email, :senha, :nivel_acesso)'
    );

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':nivel_acesso' => $nivelNumero,
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Usuário cadastrado com sucesso.',
        'usuario' => [
            'id' => $pdo->lastInsertId(),
            'nome' => $nome,
            'email' => $email,
            'nivel_acesso' => $nivelNumero,
        ],
    ]);
} catch (PDOException $e) {
    // Tratamento simples para e-mail duplicado
    if ($e->getCode() === '23000') {
        http_response_code(409);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Já existe um usuário com este e-mail.',
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Erro ao salvar usuário.',
        ]);
    }
}

