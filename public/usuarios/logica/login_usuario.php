<?php
// 1. Define que o retorno será JSON
header('Content-Type: application/json; charset=utf-8');

// 2. Importa as configurações e a conexão (Obrigatório em scripts de lógica)
// Usamos o caminho físico para evitar o erro de "http wrapper"
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';

// 3. Inicia a sessão se ainda não existir
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Captura os dados enviados pelo JavaScript (Fetch API)
$entrada = file_get_contents('php://input');
$dados = json_decode($entrada, true);

if (!is_array($dados)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos.']);
    exit;
}

$email = trim($dados['email'] ?? '');
$senha = (string)($dados['senha'] ?? '');

if ($email === '' || $senha === '') {
    http_response_code(422);
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail e senha são obrigatórios.']);
    exit;
}

try {
    // Usamos a variável $pdo que foi criada dentro do conecta_mysql.php (chamado pelo config.php)
    $stmt = $pdo->prepare(
        'SELECT id, nome, email, senha, nivel_acesso, ativo
         FROM usuarios
         WHERE email = :email
         LIMIT 1'
    );
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    // Validação de Usuário
    if (!$usuario) {
        http_response_code(401);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Credenciais inválidas.']);
        exit;
    }

    // Validação de Status (Ativo/Inativo)
    if ((int)$usuario['ativo'] !== 1) {
        http_response_code(403);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário inativo ou bloqueado.']);
        exit;
    }

    // Validação de Senha (Hash)
    if (!password_verify($senha, $usuario['senha'])) {
        http_response_code(401);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Credenciais inválidas.']);
        exit;
    }

    // Login com Sucesso: Grava dados na Sessão
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int)$usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['nivel_acesso'] = (int)$usuario['nivel_acesso'];

    // Atualiza data do último login
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
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno no servidor de banco de dados.']);
}