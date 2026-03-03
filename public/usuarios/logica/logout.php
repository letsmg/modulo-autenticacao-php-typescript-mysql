<?php
/**
 * Endpoint de logout
 */

session_start();
session_destroy();

// Redireciona para login
header('Location: ../../index.php');
exit;
?>
