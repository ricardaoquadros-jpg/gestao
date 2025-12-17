-- =====================================================
-- Sistema de Gestão de Chamados
-- Script de criação do banco de dados
-- =====================================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS gestao_chamados
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE gestao_chamados;

-- =====================================================
-- Tabela: usuarios
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabela: chamados
-- =====================================================
CREATE TABLE IF NOT EXISTS chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    status ENUM('aberto', 'em_andamento', 'concluido') DEFAULT 'aberto',
    responsavel_id INT,
    criado_por INT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (criado_por) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Índices para melhor performance
-- =====================================================
CREATE INDEX idx_chamados_status ON chamados(status);
CREATE INDEX idx_chamados_responsavel ON chamados(responsavel_id);
CREATE INDEX idx_chamados_criado_por ON chamados(criado_por);
CREATE INDEX idx_chamados_data ON chamados(data_criacao);
