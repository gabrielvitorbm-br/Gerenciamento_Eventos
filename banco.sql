CREATE DATABASE IF NOT EXISTS gerenciador_eventos DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE gerenciador_eventos;

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS evento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    data_evento DATE NOT NULL,
    local_evento VARCHAR(150) NOT NULL,
    descricao TEXT
);

CREATE TABLE IF NOT EXISTS participante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL, 
    cpf VARCHAR(14) UNIQUE NOT NULL,
    descricao TEXT NULL      
);

CREATE TABLE IF NOT EXISTS palestra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    data_palestra DATE NOT NULL,
    horario TIME NOT NULL,
    palestrante VARCHAR(100) NOT NULL,
    id_evento INT NOT NULL,
    FOREIGN KEY (id_evento) REFERENCES evento(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS inscricao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_participante INT NOT NULL,
    id_palestra INT NOT NULL,
    data_inscricao DATETIME DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (id_participante) REFERENCES participante(id) ON DELETE CASCADE,
    FOREIGN KEY (id_palestra) REFERENCES palestra(id) ON DELETE CASCADE,
    UNIQUE KEY unica_inscricao_palestra (id_participante, id_palestra)
);