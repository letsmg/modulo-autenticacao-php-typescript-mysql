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

// identificar se estamos atualizando um usuário existente
$isUpdate = isset($dados['id']) && is_numeric($dados['id']);

$nome         = trim($dados['nome'] ?? '');
$email        = trim($dados['email'] ?? '');
$senha        = (string)($dados['senha'] ?? '');
$repetir      = (string)($dados['repetirSenha'] ?? '');
$nivelAcesso  = $dados['nivelAcesso'] ?? 'padrao';
$ativo        = isset($dados['ativo']) ? (int)$dados['ativo'] : null;

// Validações básicas (a lógica principal já foi feita no TS)
$erros = [];

if ($nome === '') {
    $erros[] = 'Nome é obrigatório.';
}

if ($email === '') {
    $erros[] = 'E-mail é obrigatório.';
}

if (!$isUpdate || $senha !== '') {
    if (strlen($senha) < 6) {
        $erros[] = 'Senha deve ter pelo menos 6 caracteres.';
    }
    if ($senha !== $repetir) {
        $erros[] = 'Senhas não conferem.';
    }
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

// configuração de sessão para checar permissões em caso de edição
session_start();
$loggedId = $_SESSION['usuario_id'] ?? null;
$loggedNivel = $_SESSION['nivel_acesso'] ?? 0;

// mapear nível para número (sem levar em conta possíveis restrições ainda)
$nivelNumero = $nivelAcesso === 'adm' ? 1 : 0;

// começamos a montar o hash somente se houver senha (cadastro ou alteração)
$senhaHash = $senha !== '' ? password_hash($senha, PASSWORD_DEFAULT) : null;

try {
    if ($isUpdate) {
        // edição existente
        $id = intval($dados['id']);

        if (!$loggedId) {
            http_response_code(401);
            echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.']);
            exit;
        }

        // regra: apenas admin pode alterar outro usuário; ninguém pode alterar nivel ou ativo de si mesmo
        if ($loggedNivel !== 1 && $id !== $loggedId) {
            http_response_code(403);
            echo json_encode(['sucesso' => false, 'mensagem' => 'Sem permissão para editar este usuário.']);
            exit;
        }

        $permitirNivel = ($loggedNivel === 1 && $id !== $loggedId);
        $permitirAtivo = ($loggedNivel === 1 && $id !== $loggedId);

        $fields = [];
        $params = [':id' => $id];
        if ($nome !== '') {
            $fields[] = 'nome = :nome';
            $params[':nome'] = $nome;
        }
        if ($email !== '') {
            $fields[] = 'email = :email';
            $params[':email'] = $email;
        }
        if ($senhaHash) {
            $fields[] = 'senha = :senha';
            $params[':senha'] = $senhaHash;
        }
        if ($permitirNivel) {
            $fields[] = 'nivel_acesso = :nivel_acesso';
            $params[':nivel_acesso'] = $nivelNumero;
        }
        if ($permitirAtivo && $ativo !== null) {
            $fields[] = 'ativo = :ativo';
            $params[':ativo'] = $ativo ? 1 : 0;
        }

        if (empty($fields)) {
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Nenhuma alteração realizada.',
            ]);
            exit;
        }

        $sql = 'UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // recuperar dados atualizados para resposta
        $stmt2 = $pdo->prepare('SELECT id, nome, email, nivel_acesso, ativo FROM usuarios WHERE id = :id');
        $stmt2->execute([':id' => $id]);
        $usuarioAtual = $stmt2->fetch();

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Usuário atualizado com sucesso.',
            'usuario' => $usuarioAtual,
        ]);
    } else {
        // criação nova
        if ($senhaHash === null) {
            throw new Exception('Senha obrigatória.');
        }

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
    }
} catch (PDOException $e) {
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
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => $e->getMessage(),
    ]);
}

