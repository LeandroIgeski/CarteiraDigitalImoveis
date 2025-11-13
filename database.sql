-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS administracao;
USE administracao;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(100) NOT NULL,
    nome VARCHAR(150),
    email VARCHAR(150),
    cpf VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de imóveis
CREATE TABLE IF NOT EXISTS imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    valor_imovel DECIMAL(12, 2) NOT NULL,
    valor_aluguel DECIMAL(12, 2) NOT NULL,
    localizacao VARCHAR(255) NOT NULL,
    iptu DECIMAL(10, 2),
    custos_mensais DECIMAL(10, 2),
    lucro_mensal DECIMAL(10, 2),
    lucro_anual DECIMAL(10, 2),
    payback DECIMAL(10, 2),
    analise LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Criar índices para melhor performance
CREATE INDEX idx_usuario_id ON imoveis(usuario_id);
CREATE INDEX idx_login ON usuarios(login);

-- Inserir usuário de teste (opcional)
INSERT IGNORE INTO usuarios (login, senha, nome, email, cpf) VALUES 
('teste', 'teste123', 'Usuário Teste', 'teste@example.com', '12345678901');
