<?php
require_once 'db/conexao.php';
iniciarSessao();

// Protege a página
if (!estaLogado()) {
    redirecionar('login.php');
}

$pdo = conectar();

// Filtro de status
$filtro_status = $_GET['status'] ?? '';
$busca = $_GET['busca'] ?? '';

// Construir query com filtros
$sql = "SELECT c.*, 
        u1.nome as criador_nome, 
        u2.nome as responsavel_nome 
        FROM chamados c 
        LEFT JOIN usuarios u1 ON c.criado_por = u1.id 
        LEFT JOIN usuarios u2 ON c.responsavel_id = u2.id 
        WHERE 1=1";
$params = [];

if (!empty($filtro_status)) {
    $sql .= " AND c.status = ?";
    $params[] = $filtro_status;
}

if (!empty($busca)) {
    $sql .= " AND (c.titulo LIKE ? OR c.descricao LIKE ?)";
    $params[] = "%$busca%";
    $params[] = "%$busca%";
}

$sql .= " ORDER BY c.data_criacao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$chamados = $stmt->fetchAll();

// Estatísticas
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'aberto' THEN 1 ELSE 0 END) as abertos,
    SUM(CASE WHEN status = 'em_andamento' THEN 1 ELSE 0 END) as em_andamento,
    SUM(CASE WHEN status = 'concluido' THEN 1 ELSE 0 END) as concluidos
    FROM chamados";
$stats = $pdo->query($stats_sql)->fetch();

// Lista de usuários para filtro
$usuarios = $pdo->query("SELECT id, nome FROM usuarios ORDER BY nome")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= SITE_NAME ?></title>
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
                <a href="dashboard.php" class="nav-item active">
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
                <h1>Dashboard</h1>
                <a href="create_chamado.php" class="btn btn-primary">
                    <span>➕</span> Novo Chamado
                </a>
            </header>
            
            <?= exibirMensagem() ?>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
                    <div class="stat-label">Total de Chamados</div>
                </div>
                <div class="stat-card stat-aberto">
                    <div class="stat-value"><?= $stats['abertos'] ?? 0 ?></div>
                    <div class="stat-label">Abertos</div>
                </div>
                <div class="stat-card stat-andamento">
                    <div class="stat-value"><?= $stats['em_andamento'] ?? 0 ?></div>
                    <div class="stat-label">Em Andamento</div>
                </div>
                <div class="stat-card stat-concluido">
                    <div class="stat-value"><?= $stats['concluidos'] ?? 0 ?></div>
                    <div class="stat-label">Concluídos</div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="filters-bar">
                <form method="GET" class="filters-form">
                    <div class="filter-group">
                        <input 
                            type="text" 
                            name="busca" 
                            placeholder="Buscar chamados..."
                            value="<?= htmlspecialchars($busca) ?>"
                            class="filter-input"
                        >
                    </div>
                    <div class="filter-group">
                        <select name="status" class="filter-select">
                            <option value="">Todos os status</option>
                            <option value="aberto" <?= $filtro_status === 'aberto' ? 'selected' : '' ?>>Aberto</option>
                            <option value="em_andamento" <?= $filtro_status === 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                            <option value="concluido" <?= $filtro_status === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">Filtrar</button>
                    <?php if (!empty($filtro_status) || !empty($busca)): ?>
                        <a href="dashboard.php" class="btn btn-outline">Limpar</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Chamados List -->
            <div class="chamados-list">
                <?php if (empty($chamados)): ?>
                    <div class="empty-state">
                        <span class="empty-icon">📭</span>
                        <h3>Nenhum chamado encontrado</h3>
                        <p>Crie um novo chamado para começar a organizar suas tarefas.</p>
                        <a href="create_chamado.php" class="btn btn-primary">Criar Chamado</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($chamados as $chamado): ?>
                        <div class="chamado-card">
                            <div class="chamado-header">
                                <h3 class="chamado-title">
                                    <a href="view_chamado.php?id=<?= $chamado['id'] ?>">
                                        <?= htmlspecialchars($chamado['titulo']) ?>
                                    </a>
                                </h3>
                                <span class="status-badge <?= classStatus($chamado['status']) ?>">
                                    <?= formatarStatus($chamado['status']) ?>
                                </span>
                            </div>
                            
                            <p class="chamado-descricao">
                                <?= htmlspecialchars(substr($chamado['descricao'] ?? '', 0, 150)) ?>
                                <?= strlen($chamado['descricao'] ?? '') > 150 ? '...' : '' ?>
                            </p>
                            
                            <div class="chamado-meta">
                                <span class="meta-item">
                                    <span class="meta-icon">👤</span>
                                    <?= htmlspecialchars($chamado['responsavel_nome'] ?? 'Não atribuído') ?>
                                </span>
                                <span class="meta-item">
                                    <span class="meta-icon">📅</span>
                                    <?= formatarData($chamado['data_criacao']) ?>
                                </span>
                                <span class="meta-item">
                                    <span class="meta-icon">✍️</span>
                                    <?= htmlspecialchars($chamado['criador_nome']) ?>
                                </span>
                            </div>
                            
                            <div class="chamado-actions">
                                <a href="view_chamado.php?id=<?= $chamado['id'] ?>" class="btn btn-sm btn-outline">Ver</a>
                                <a href="edit_chamado.php?id=<?= $chamado['id'] ?>" class="btn btn-sm btn-secondary">Editar</a>
                                <a href="delete_chamado.php?id=<?= $chamado['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Tem certeza que deseja excluir este chamado?')">
                                    Excluir
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
