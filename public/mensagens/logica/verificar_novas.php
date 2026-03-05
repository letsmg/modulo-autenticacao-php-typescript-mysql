<?php
// Silenciar erros para não quebrar o JSON no AJAX
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

header('Content-Type: application/json');

try {
    // 1. O "Caminho de Ouro": Carrega o config que já traz tudo
    $caminho_config = realpath(__DIR__ . '/../../../config/config.php');

    if (!$caminho_config) {
        throw new Exception("Nao encontrou o arquivo config.php");
    }

    require_once $caminho_config;

    // 2. Garante que a sessão existe
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $id_logado = $_SESSION['usuario_id'] ?? 0;
    $total = 0;

    // 3. Usa a conexão que veio de dentro do config.php
    // Tenta identificar se o nome da variável é $conn ou $pdo
    $db = $conn ?? $pdo ?? null;

    if ($id_logado > 0 && $db) {
        $sql = "SELECT COUNT(*) FROM mensagens WHERE id_destinatario = ? AND lida = 0";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_logado]);
        $total = (int)$stmt->fetchColumn();
    }

    // Limpa qualquer resíduo de texto e entrega o JSON puro
    ob_clean();
    echo json_encode(['total' => $total]);

} catch (Exception $e) {
    ob_clean();
    echo json_encode(['total' => 0, 'erro' => 'Erro de configuracao']);
}
exit;