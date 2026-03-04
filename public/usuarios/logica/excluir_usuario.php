<?php
// 1. Define o retorno como JSON
header('Content-Type: application/json; charset=utf-8');

// 2. Carrega as configurações (O $pdo já vem incluso aqui pelo seu config.php)
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';

// 3. Inicia sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. SEGURANÇA DE NÍVEL: Verifica se quem está logado é ADM (1)
// Se não for ADM, ele não tem permissão nem de tentar excluir.
$nivel_operador = (int)($_SESSION['nivel_acesso'] ?? 0);

if ($nivel_operador !== 1) {
    http_response_code(403); // Proibido
    echo json_encode([
        'sucesso' => false, 
        'mensagem' => 'Acesso negado: Apenas administradores podem excluir usuários.'
    ]);
    exit;
}

// 5. Pega o ID enviado pelo Fetch
$entrada = file_get_contents('php://input');
$dados = json_decode($entrada, true);
$id_alvo = isset($dados['id']) ? (int)$dados['id'] : 0;

// 6. Validações básicas de ID
if ($id_alvo <= 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID de usuário inválido.']);
    exit;
}

// 7. Impede que o usuário (mesmo sendo ADM) exclua a si próprio
if ($id_alvo === (int)$_SESSION['usuario_id']) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Você não pode excluir sua própria conta por aqui.']);
    exit;
}

try {
    // 8. Executa a exclusão
    // Como o operador é ADM, ele pode excluir qualquer um (Padrão ou outro ADM)
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id_alvo]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário excluído com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não encontrado ou já removido.']);
    }

} catch (PDOException $e) {
    // Erros de banco (ex: chave estrangeira) caem aqui
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro técnico: Não foi possível excluir o registro.']);
}