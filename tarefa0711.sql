CREATE DATABASE tarefa0711;
USE tarefa0711;

CREATE TABLE funcionarios (
    funcionario_cod INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    funcionario_nome VARCHAR(50),
    funcionario_cargo VARCHAR(50)
);

CREATE TABLE registro (
    registro_cod INT PRIMARY KEY AUTO_INCREMENT,
    registro_data DATE,
    registro_hora TIME,
    funcionario_cod INT,
    FOREIGN KEY (funcionario_cod) REFERENCES funcionarios(funcionario_cod)
);
