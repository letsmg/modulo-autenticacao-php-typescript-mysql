<?php
header('Content-Type: application/json; charset=utf-8');

// para identificar o usuário logado e seus direitos
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não autenticado.',
    ]);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID inválido.',
    ]);
    exit;
}

// configuração do banco (mesma que os outros scripts)
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

try {
    $stmt = $pdo->prepare(
        'SELECT id, nome, email, nivel_acesso, ativo
         FROM usuarios
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(404);
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Usuário não encontrado.',
        ]);
        exit;
    }

    $loggedId = $_SESSION['usuario_id'];
    $loggedNivel = $_SESSION['nivel_acesso'];

    $canChangeAccess = false;
    $canDeactivate = false;

    if ($loggedNivel === 1) {
        // admin pode alterar/ativar outros
        if ($id !== $loggedId) {
            $canChangeAccess = true;
            $canDeactivate = true;
        }
    } else {
        // usuário comum só pode ver os próprios dados e sem permissões extras
        if ($id !== $loggedId) {
            http_response_code(403);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Sem permissão para ver dados de outro usuário.',
            ]);
            exit;
        }
    }

    echo json_encode([
        'sucesso' => true,
        'usuario' => $user,
        'canChangeAccess' => $canChangeAccess,
        'canDeactivate' => $canDeactivate,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao buscar usuário.',
    ]);
}
