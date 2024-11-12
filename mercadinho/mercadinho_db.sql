CREATE DATABASE mercado;

USE mercado;
create table cliente(
	cliente_id int primary key auto_increment,
	cpf varchar (11) unique not null,
    	nome varchar(200),
    	telefone varchar(11)
    
); 
create table divida(
	id_divida int primary key auto_increment,
   	cliente_id int,
    	valor_divida decimal(20,2),
	foreign key (cliente_id) references cliente(cliente_id)
);
create table compra (
	 id_compra int primary key auto_increment,
   	 valor_compra decimal(5,2),
   	 valor_pago decimal(7,2),
   	 data_compra date,
   	 cliente_id int,
   	 foreign key (cliente_id) references cliente(cliente_id)
);

create table produto(
	id_produto int primary key auto_increment,
  	  nome_produto varchar(150),
  	  estoque int,
  	  preco DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  	  id_fornecedor int,
   	 foreign key (id_fornecedor) references fornecedor(id_fornecedor)
    
);

create table fornecedor(
	id_fornecedor int primary key auto_increment,
 	nome varchar(200),
  	endereco varchar(500),
    	cnpj varchar (18) unique
);


create table usuario(
	id_usuario int primary key auto_increment,
   	nome_usuario varchar(100) not null,
    	senha_usuario varchar(100) not null,
   	email_usuario varchar(100) not null unique
    
);

