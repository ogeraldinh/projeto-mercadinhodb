CREATE DATABASE mercado;
USE mercado;

CREATE TABLE cliente(
	cliente_id INT PRIMARY KEY AUTO_INCREMENT,
	cpf VARCHAR(11) UNIQUE NOT NULL,
    nome VARCHAR(200),
    telefone VARCHAR(11)
    
); 
CREATE TABLE divida(
	id_divida INT PRIMARY KEY AUTO_INCREMENT,
   	cliente_id INT,
    valor_divida DECIMAL(20,2),
	FOREIGN KEY (cliente_id) REFERENCES cliente(cliente_id)
);
CREATE TABLE compra (
	id_compra INT PRIMARY KEY AUTO_INCREMENT,
   	valor_compra DECIMAL(5,2),
   	valor_pago DECIMAL(7,2),
   	data_compra DATE,
   	cliente_id INT,
   	FOREIGN KEY (cliente_id) REFERENCES cliente(cliente_id)
);

CREATE TABLE fornecedor(
	id_fornecedor INT PRIMARY KEY AUTO_INCREMENT,
 	nome VARCHAR(200),
  	endereco VARCHAR(500),
    cnpj VARCHAR(18) UNIQUE
);

CREATE TABLE produto(
    id_produto INT PRIMARY KEY AUTO_INCREMENT,
  	nome_produto VARCHAR(150),
  	estoque INT,
  	preco DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  	id_fornecedor INT,
   	FOREIGN KEY (id_fornecedor) REFERENCES fornecedor(id_fornecedor)
    
);

CREATE TABLE usuario(
	id_usuario INT PRIMARY KEY AUTO_INCREMENT,
   	nome_usuario VARCHAR(100) NOT NULL,
    senha_usuario VARCHAR(100) NOT NULL,
   	email_usuario VARCHAR(100) NOT NULL UNIQUE
    
);
