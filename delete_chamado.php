<?php
require_once 'db/conexao.php';
iniciarSessao();

// Protege a página
if (!estaLogado()) {
    redirecionar('login.php');
}

$id = $_GET['id'] ?? null;

if (!$id) {
    setMensagem('Chamado não encontrado.', 'error');
    redirecionar('dashboard.php');
}

$pdo = conectar();

// Verifica se o chamado existe
$stmt = $pdo->prepare("SELECT id FROM chamados WHERE id = ?");
$stmt->execute([$id]);
$chamado = $stmt->fetch();

if (!$chamado) {
    setMensagem('Chamado não encontrado.', 'error');
    redirecionar('dashboard.php');
}

try {
    // Exclui o chamado
    $stmt = $pdo->prepare("DELETE FROM chamados WHERE id = ?");
    $stmt->execute([$id]);
    
    setMensagem('Chamado excluído com sucesso!', 'success');
} catch (PDOException $e) {
    setMensagem('Erro ao excluir chamado.', 'error');
}

redirecionar('dashboard.php');
?>
