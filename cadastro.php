<?php
require_once 'db/conexao.php';
iniciarSessao();

// Se já está logado, redireciona para dashboard
if (estaLogado()) {
    redirecionar('dashboard.php');
}

$erro = '';
$sucesso = '';

// Processar formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    // Validações
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = 'Preencha todos os campos.';
    } elseif (strlen($nome) < 3) {
        $erro = 'O nome deve ter pelo menos 3 caracteres.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem.';
    } else {
        try {
            $pdo = conectar();
            
            // Verifica se email já existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $erro = 'Este email já está cadastrado.';
            } else {
                // Cria o usuário
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
                $stmt->execute([$nome, $email, $senha_hash]);
                
                setMensagem('Cadastro realizado com sucesso! Faça login para continuar.', 'success');
                redirecionar('login.php');
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao processar cadastro. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">
                    <span class="logo-icon">📋</span>
                    <h1><?= SITE_NAME ?></h1>
                </div>
                <p>Crie sua conta para começar</p>
            </div>
            
            <?php if ($erro): ?>
                <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="nome">Nome completo</label>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                        placeholder="Seu nome"
                        value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="seu@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input 
                        type="password" 
                        id="senha" 
                        name="senha" 
                        placeholder="Mínimo 6 caracteres"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input 
                        type="password" 
                        id="confirmar_senha" 
                        name="confirmar_senha" 
                        placeholder="Digite a senha novamente"
                        required
                    >
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    Criar conta
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
