CREATE DATABASE bd_maison_royale
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE bd_maison_royale;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE quartos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria VARCHAR(80) NOT NULL,
    descricao TEXT,
    capacidade INT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    imagem VARCHAR(255),
    ativo TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    quarto_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    hospedes INT NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    status ENUM('Confirmada', 'Cancelada') NOT NULL DEFAULT 'Confirmada',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
    FOREIGN KEY (quarto_id) REFERENCES quartos(id) ON DELETE RESTRICT
);

-- Indice para acelerar a verificacao de sobreposicao de datas (RN01/RN07).
CREATE INDEX idx_reservas_disponibilidade
    ON reservas (quarto_id, status, check_in, check_out);
