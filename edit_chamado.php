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
$erro = '';

// Busca o chamado
$stmt = $pdo->prepare("SELECT * FROM chamados WHERE id = ?");
$stmt->execute([$id]);
$chamado = $stmt->fetch();

if (!$chamado) {
    setMensagem('Chamado não encontrado.', 'error');
    redirecionar('dashboard.php');
}

// Busca usuários para o select de responsável
$usuarios = $pdo->query("SELECT id, nome FROM usuarios ORDER BY nome")->fetchAll();

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $responsavel_id = $_POST['responsavel_id'] ?? null;
    $status = $_POST['status'] ?? 'aberto';
    
    if (empty($titulo)) {
        $erro = 'O título é obrigatório.';
    } else {
        try {
            $responsavel_id = !empty($responsavel_id) ? $responsavel_id : null;
            
            $stmt = $pdo->prepare("
                UPDATE chamados 
                SET titulo = ?, descricao = ?, status = ?, responsavel_id = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $titulo,
                $descricao,
                $status,
                $responsavel_id,
                $id
            ]);
            
            setMensagem('Chamado atualizado com sucesso!', 'success');
            redirecionar("view_chamado.php?id=$id");
            
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar chamado. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Chamado - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <span class="logo-icon">📋</span>
                    <span class="logo-text"><?= SITE_NAME ?></span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <span class="nav-icon">🏠</span>
                    <span>Dashboard</span>
                </a>
                <a href="create_chamado.php" class="nav-item">
                    <span class="nav-icon">➕</span>
                    <span>Novo Chamado</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <span class="user-avatar">👤</span>
                    <span class="user-name"><?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
                </div>
                <a href="logout.php" class="logout-btn">Sair</a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <header class="page-header">
                <div class="page-header-left">
                    <a href="view_chamado.php?id=<?= $id ?>" class="btn btn-outline btn-back">← Voltar</a>
                    <h1>Editar Chamado</h1>
                </div>
            </header>
            
            <?php if ($erro): ?>
                <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST" class="chamado-form">
                    <div class="form-group">
                        <label for="titulo">Título *</label>
                        <input 
                            type="text" 
                            id="titulo" 
                            name="titulo" 
                            placeholder="Digite o título do chamado"
                            value="<?= htmlspecialchars($_POST['titulo'] ?? $chamado['titulo']) ?>"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea 
                            id="descricao" 
                            name="descricao" 
                            rows="5"
                            placeholder="Descreva os detalhes do chamado..."
                        ><?= htmlspecialchars($_POST['descricao'] ?? $chamado['descricao']) ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <?php 
                                $status_atual = $_POST['status'] ?? $chamado['status'];
                                ?>
                                <option value="aberto" <?= $status_atual === 'aberto' ? 'selected' : '' ?>>Aberto</option>
                                <option value="em_andamento" <?= $status_atual === 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="concluido" <?= $status_atual === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="responsavel_id">Responsável</label>
                            <select id="responsavel_id" name="responsavel_id">
                                <option value="">Selecione um responsável</option>
                                <?php 
                                $responsavel_atual = $_POST['responsavel_id'] ?? $chamado['responsavel_id'];
                                foreach ($usuarios as $usuario): 
                                ?>
                                    <option value="<?= $usuario['id'] ?>" 
                                        <?= $responsavel_atual == $usuario['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($usuario['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="view_chamado.php?id=<?= $id ?>" class="btn btn-outline">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
