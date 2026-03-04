<?php
header('Content-Type: application/json; charset=utf-8');

// 1. Importa configurações e conexão (Evita repetir host/db/pass)
require_once $_SERVER['DOCUMENT_ROOT'] . '/ts/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Verifica autenticação
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Sessão encerrada.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados vazios.']);
    exit;
}

// 3. Identifica quem está operando
$id_operador = (int)$_SESSION['usuario_id'];
$nivel_operador = (int)$_SESSION['nivel_acesso']; // 1 = ADM, 0 = Padrão
$id_alvo = isset($data['id']) ? intval($data['id']) : 0;

// 4. Tratamento do Nível de Acesso (AQUI ESTÁ A SEGURANÇA)
$nivelEnviado = (isset($data['nivelAcesso']) && ($data['nivelAcesso'] === 'adm' || $data['nivelAcesso'] == 1)) ? 1 : 0;

if ($nivel_operador !== 1) {
    // Se NÃO for ADM, ele nunca pode definir nível 1 para ninguém (nem para si mesmo)
    $nivelFinal = 0;
} else {
    // Se FOR ADM, ele pode definir o nível que quiser
    $nivelFinal = $nivelEnviado;
}

// --- CENÁRIO A: ATUALIZAÇÃO RÁPIDA DE STATUS (BOTÃO NA LISTA) ---
if ($id_alvo > 0 && isset($data['ativo']) && !isset($data['nome'])) {
    
    // REGRA: Apenas ADM (1) pode alterar status de terceiros.
    // O usuário padrão (0) não pode ativar/bloquear ninguém.
    if ($nivel_operador !== 1) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Apenas administradores podem ativar ou bloquear usuários.']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET ativo = ? WHERE id = ?");
    $stmt->execute([$data['ativo'], $id_alvo]);
    echo json_encode(['sucesso' => true, 'mensagem' => 'Status atualizado com sucesso!']);
    exit;
}

// --- CENÁRIO B: EDICAO OU CADASTRO COMPLETO ---
try {
    if ($id_alvo > 0) {
        // UPDATE
        // Segurança: usuário comum não pode editar um ADM
        if ($nivel_operador !== 1) {
            $check = $pdo->prepare("SELECT nivel_acesso FROM usuarios WHERE id = ?");
            $check->execute([$id_alvo]);
            $alvo = $check->fetch();
            if ($alvo && (int)$alvo['nivel_acesso'] === 1) {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Usuários padrão não podem editar administradores.']);
                exit;
            }
        }

        $sql = "UPDATE usuarios SET nome = :nome, email = :email, nivel_acesso = :nivel";
        $params = [
            ':nome'  => $data['nome'],
            ':email' => $data['email'],
            ':nivel' => $nivelFinal,
            ':id'    => $id_alvo
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
        // INSERT (Cadastro)
        // Se usuário padrão está cadastrando, o $nivelFinal já foi forçado a 0 lá em cima
        $sql = "INSERT INTO usuarios (nome, email, senha, nivel_acesso, ativo) 
                VALUES (:nome, :email, :senha, :nivel, :ativo)";
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

} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao processar: ' . $e->getMessage()]);
}