<?php
    
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
    echo "<p class=\"text-danger\">Erro ao conectar ao banco.</p>";
    exit;
}

try {
    $stmt = $pdo->query('SELECT id, nome, email FROM usuarios ORDER BY id DESC');
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
}