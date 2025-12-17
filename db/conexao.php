<?php
/**
 * Arquivo de conexão com o banco de dados MySQL
 * Usando PDO para maior segurança e flexibilidade
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestao_chamados');
define('DB_USER', 'root');
define('DB_PASS', ''); // Altere conforme sua configuração

// Configurações do sistema
define('SITE_NAME', 'Gestão de Chamados');
define('SITE_URL', 'http://localhost/gestao');

/**
 * Cria e retorna uma conexão PDO com o banco de dados
 * @return PDO
 */
function conectar() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
        
    } catch (PDOException $e) {
        // Em produção, logar o erro ao invés de exibir
        die("Erro de conexão: " . $e->getMessage());
    }
}

/**
 * Inicia a sessão se ainda não estiver iniciada
 */
function iniciarSessao() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Verifica se o usuário está logado
 * @return bool
 */
function estaLogado() {
    iniciarSessao();
    return isset($_SESSION['usuario_id']);
}

/**
 * Redireciona para uma página
 * @param string $pagina
 */
function redirecionar($pagina) {
    header("Location: $pagina");
    exit;
}

/**
 * Exibe mensagem de erro/sucesso armazenada na sessão
 * @return string HTML da mensagem
 */
function exibirMensagem() {
    iniciarSessao();
    $html = '';
    
    if (isset($_SESSION['mensagem'])) {
        $tipo = $_SESSION['mensagem']['tipo'];
        $texto = $_SESSION['mensagem']['texto'];
        $html = "<div class='alert alert-{$tipo}'>{$texto}</div>";
        unset($_SESSION['mensagem']);
    }
    
    return $html;
}

/**
 * Define uma mensagem na sessão
 * @param string $texto
 * @param string $tipo (success, error, warning, info)
 */
function setMensagem($texto, $tipo = 'info') {
    iniciarSessao();
    $_SESSION['mensagem'] = [
        'texto' => $texto,
        'tipo' => $tipo
    ];
}

/**
 * Formata data para exibição
 * @param string $data
 * @return string
 */
function formatarData($data) {
    return date('d/m/Y H:i', strtotime($data));
}

/**
 * Retorna o nome do status formatado
 * @param string $status
 * @return string
 */
function formatarStatus($status) {
    $nomes = [
        'aberto' => 'Aberto',
        'em_andamento' => 'Em Andamento',
        'concluido' => 'Concluído'
    ];
    return $nomes[$status] ?? $status;
}

/**
 * Retorna a classe CSS do status
 * @param string $status
 * @return string
 */
function classStatus($status) {
    $classes = [
        'aberto' => 'status-aberto',
        'em_andamento' => 'status-andamento',
        'concluido' => 'status-concluido'
    ];
    return $classes[$status] ?? '';
}
?>
