<?php
require_once 'db/conexao.php';
iniciarSessao();

// Redireciona baseado no status do login
if (estaLogado()) {
    redirecionar('dashboard.php');
} else {
    redirecionar('login.php');
}
?>
