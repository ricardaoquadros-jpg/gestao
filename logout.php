<?php
require_once 'db/conexao.php';
iniciarSessao();

// Destroi a sessão
$_SESSION = [];
session_destroy();

// Redireciona para login
header("Location: login.php");
exit;
?>
