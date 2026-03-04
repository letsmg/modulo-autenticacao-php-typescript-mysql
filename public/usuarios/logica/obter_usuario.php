<?php
// 1. Define o cabeçalho para JSON
header('Content-Type: application/json; charset=utf-8');

// 2. Importa as configurações globais e a conexão $pdo
// Usamos o caminho absoluto para evitar problemas de diretório
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';

// 3. Inicia a sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.']);
    exit;
}

// 5. Valida o ID recebido via GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
    exit;
}

try {
    // 6. Busca os dados do usuário no banco
    $stmt = $pdo->prepare(
        'SELECT id, nome, email, nivel_acesso, ativo
         FROM usuarios
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $id]);
    $usuario_alvo = $stmt->fetch();

    if (!$usuario_alvo) {
        http_response_code(404);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não encontrado.']);
        exit;
    }

    // 7. Definição de permissões baseada no nível de acesso
    $loggedId    = (int)$_SESSION['usuario_id'];
    $loggedNivel = (int)$_SESSION['nivel_acesso']; // 1 = Adm, 0 = Padrão
    $alvoNivel   = (int)$usuario_alvo['nivel_acesso'];

    $canChangeAccess = false;
    $canDeactivate   = false;

    // LÓGICA DE PROTEÇÃO
    if ($loggedNivel === 1) {
        // ADMINISTRADOR: Pode ver qualquer um
        // Só não pode mudar o próprio nível ou se desativar nesta tela
        if ($id !== $loggedId) {
            $canChangeAccess = true;
            $canDeactivate   = true;
        }
    } else {
        // USUÁRIO PADRÃO (0):
        // Se o alvo for um ADM (1), negamos o acesso (Administradores são "invisíveis")
        if ($alvoNivel === 1) {
            http_response_code(403);
            echo json_encode([
                'sucesso' => false, 
                'mensagem' => 'Acesso negado: Administradores não podem ser visualizados por usuários padrão.'
            ]);
            exit;
        }

        // Se o alvo for outro usuário PADRÃO, ele pode ver, mas não pode editar permissões
        $canChangeAccess = false;
        $canDeactivate   = false;
    }

    // 8. Retorno dos dados
    echo json_encode([
        'sucesso' => true,
        'usuario' => $usuario_alvo,
        'permissoes' => [
            'podeAlterarNivel' => $canChangeAccess,
            'podeDesativar'    => $canDeactivate
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false, 
        'mensagem' => 'Erro interno ao buscar usuário.'
    ]);
}