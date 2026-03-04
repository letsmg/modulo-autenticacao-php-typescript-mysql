<?php
// --- PARA O NAVEGADOR (Links, CSS, JS, Fetch) ---
define('BASE_URL', 'http://localhost/ts/public/');
define('STORAGE', 'http://localhost/ts/storage/');

// --- PARA O PHP (require_once, include) ---
// O __DIR__ pega a pasta onde este arquivo config.php está
define('DIR', dirname(__DIR__)); 
define('DIR_PUB', DIR . '/public'); 

// Configurações do Banco
define('DB_HOST', 'localhost');
define('DB_NAME', 'ts');
define('DB_USER', 'root');
define('DB_PASS', '');

// Agora o PHP carrega o arquivo localmente, sem usar http
require_once DIR . '/config/conecta_mysql.php';