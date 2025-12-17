<?php
require_once 'db/conexao.php';
iniciarSessao();

// Se já está logado, redireciona para dashboard
if (estaLogado()) {
    redirecionar('dashboard.php');
}

$erro = '';

// Processar formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos.';
    } else {
        try {
            $pdo = conectar();
            $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                setMensagem('Bem-vindo, ' . $usuario['nome'] . '!', 'success');
                redirecionar('dashboard.php');
            } else {
                $erro = 'Email ou senha incorretos.';
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao processar login. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= SITE_NAME ?></title>
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
                <p>Faça login para acessar o sistema</p>
            </div>
            
            <?php if ($erro): ?>
                <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
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
                        placeholder="Sua senha"
                        required
                    >
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    Entrar
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>
        </div>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
