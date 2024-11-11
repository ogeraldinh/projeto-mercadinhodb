<?php
session_start();
include('config.php');

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $id_usuario = $_SESSION['id_usuario'];

    // Buscar o preço e o estoque do produto
    $sql_estoque = "SELECT estoque, preco FROM produto WHERE id_produto = $id_produto";
    $result = $conexao->query($sql_estoque);
    $produto = mysqli_fetch_assoc($result);

    // Verificar se há estoque suficiente
    if ($produto['estoque'] < $quantidade) {
        echo "Estoque insuficiente para essa venda.";
        exit();
    }

    // Calcular o valor total da compra usando o preço do banco de dados
    $preco_unitario = $produto['preco'];
    $valor_compra = $quantidade * $preco_unitario;

    // Registrar a compra
    $sql_compra = "INSERT INTO compra (valor_compra, valor_pago, data_compra, cliente_id) 
                   VALUES ($valor_compra, 0, NOW(), NULL)";
    $conexao->query($sql_compra);
    $id_compra = $conexao->insert_id;

    // Atualizar o estoque do produto
    $sql_update_estoque = "UPDATE produto SET estoque = estoque - $quantidade WHERE id_produto = $id_produto";
    $conexao->query($sql_update_estoque);

    // Registrar dívida se o pagamento for a prazo
    if ($forma_pagamento == 'aprazo') {
        $sql_divida = "INSERT INTO divida (cliente_id, valor_divida) VALUES (NULL, $valor_compra)";
        $conexao->query($sql_divida);
    }

    echo "Venda registrada com sucesso!";
    header("Location: vendas.php");
    exit();
}
?>
