DROP DATABASE IF EXISTS controle_op;

CREATE DATABASE controle_op
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE controle_op;


CREATE TABLE usuarios(

    id int AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil ENUM('admin' , 'almoxarife', 'producao') NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1


);

CREATE TABLE maquinas(

    id int AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    setor VARCHAR(50) NOT NULL

);

CREATE TABLE ops(

    id int AUTO_INCREMENT PRIMARY KEY,
    numero_op VARCHAR(20) NOT NULL UNIQUE,
    status ENUM('aguardando', 'separada', 'retirada', 'interrompida') NOT NULL DEFAULT 'aguardando',
    obs_aguardando VARCHAR(255) NULL,
    cadastrada_por INT NOT NULL,
    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    caixa_tipo ENUM('transparente', 'marrom') NULL,
    caixa_numero INT NULL,
    localizacao VARCHAR(100) NULL,
    anexo_link VARCHAR(255) NULL,
    obs_separacao VARCHAR(255) NULL,
    separada_por INT NULL,
    data_separacao DATETIME NULL,


    Foreign Key (cadastrada_por) REFERENCES usuarios(id),
    Foreign Key (separada_por) REFERENCES usuarios(id)

);

CREATE TABLE movimentacoes(

    id INT AUTO_INCREMENT PRIMARY KEY,
    op_id INT NOT NULL,
    tipo ENUM('retirada', 'interrupcao') NOT NULL,
    turno ENUM('primeiro', 'segundo', 'terceiro') NOT NULL,
    maquina_id INT NULL,
    observacao VARCHAR(255) NULL,
    data_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    Foreign Key (op_id) REFERENCES ops(id),
    Foreign Key (maquina_id) REFERENCES maquinas(id)

);

INSERT INTO maquinas(nome, setor) VALUES

('Injetora 01', 'Injeção'),
('Injetora 02', 'Injeção'),
('Injetora 03', 'Injeção'),
('Injetora 04', 'Injeção'),
('Injetora 05', 'Injeção'),
('Injetora 06', 'Injeção'),
('Injetora 07', 'Injeção'),
('Injetora 08', 'Injeção'),
('Injetora 09', 'Injeção'),
('Injetora 10', 'Injeção'),
('Injetora 11', 'Injeção'),
('Injetora 12', 'Injeção'),
('Injetora 13', 'Injeção'),
('Injetora 14', 'Injeção'),
('Injetora 15', 'Injeção'),
('Injetora 16', 'Injeção'),
('Injetora 17', 'Injeção'),
('Injetora 18', 'Injeção'),
('Injetora 19', 'Injeção'),
('Injetora 20', 'Injeção'),
('Injetora 21', 'Injeção'),
('Injetora 22', 'Injeção'),
('Injetora 23', 'Injeção'),
('Injetora 24', 'Injeção'),
('Injetora 25', 'Injeção'),
('Injetora 26', 'Injeção'),
('Injetora 27', 'Injeção'),
('Injetora 28', 'Injeção'),
('Injetora 29', 'Injeção'),
('Injetora 30', 'Injeção'),
('Injetora 31', 'Injeção'),
('Injetora 32', 'Injeção'),
('Injetora 33', 'Injeção'),
('Injetora 34', 'Injeção'),
('Injetora 35', 'Injeção'),
('Injetora 36', 'Injeção'),
('Injetora 37', 'Injeção'),
('Injetora 38', 'Injeção'),
('Injetora 39', 'Injeção'),
('Injetora 40', 'Injeção');

