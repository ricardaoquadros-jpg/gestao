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

// Busca o chamado
$stmt = $pdo->prepare("
    SELECT c.*, 
           u1.nome as criador_nome, 
           u1.email as criador_email,
           u2.nome as responsavel_nome,
           u2.email as responsavel_email
    FROM chamados c 
    LEFT JOIN usuarios u1 ON c.criado_por = u1.id 
    LEFT JOIN usuarios u2 ON c.responsavel_id = u2.id 
    WHERE c.id = ?
");
$stmt->execute([$id]);
$chamado = $stmt->fetch();

if (!$chamado) {
    setMensagem('Chamado não encontrado.', 'error');
    redirecionar('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($chamado['titulo']) ?> - <?= SITE_NAME ?></title>
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
                    <a href="dashboard.php" class="btn btn-outline btn-back">← Voltar</a>
                    <h1>Detalhes do Chamado</h1>
                </div>
                <div class="page-header-actions">
                    <a href="edit_chamado.php?id=<?= $chamado['id'] ?>" class="btn btn-secondary">Editar</a>
                    <a href="delete_chamado.php?id=<?= $chamado['id'] ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Tem certeza que deseja excluir este chamado?')">
                        Excluir
                    </a>
                </div>
            </header>
            
            <?= exibirMensagem() ?>
            
            <div class="chamado-detail">
                <div class="detail-header">
                    <h2 class="detail-title"><?= htmlspecialchars($chamado['titulo']) ?></h2>
                    <span class="status-badge <?= classStatus($chamado['status']) ?>">
                        <?= formatarStatus($chamado['status']) ?>
                    </span>
                </div>
                
                <div class="detail-body">
                    <div class="detail-section">
                        <h3>Descrição</h3>
                        <div class="detail-content">
                            <?php if (!empty($chamado['descricao'])): ?>
                                <?= nl2br(htmlspecialchars($chamado['descricao'])) ?>
                            <?php else: ?>
                                <span class="text-muted">Nenhuma descrição fornecida.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Responsável</span>
                            <span class="detail-value">
                                <?php if ($chamado['responsavel_nome']): ?>
                                    <span class="avatar-small">👤</span>
                                    <?= htmlspecialchars($chamado['responsavel_nome']) ?>
                                <?php else: ?>
                                    <span class="text-muted">Não atribuído</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Criado por</span>
                            <span class="detail-value">
                                <span class="avatar-small">👤</span>
                                <?= htmlspecialchars($chamado['criador_nome']) ?>
                            </span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Data de Criação</span>
                            <span class="detail-value">
                                <span class="icon-small">📅</span>
                                <?= formatarData($chamado['data_criacao']) ?>
                            </span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Última Atualização</span>
                            <span class="detail-value">
                                <span class="icon-small">🔄</span>
                                <?= formatarData($chamado['data_atualizacao']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-footer">
                    <span class="chamado-id">ID: #<?= $chamado['id'] ?></span>
                </div>
            </div>
        </main>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
