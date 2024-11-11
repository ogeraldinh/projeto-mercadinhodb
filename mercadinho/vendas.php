<?php
session_start();
include('config.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

// Inicializando as variáveis de mensagens de erro ou sucesso
$message = "";

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $id_usuario = $_SESSION['id_usuario'];

    // Se o cliente for novo, registrar o cliente no banco de dados
    if ($_POST['cliente_id'] == 'novo_cliente') {
        // Captura os dados do cliente novo
        $nome_cliente = $_POST['nome_cliente'];
        $cpf_cliente = $_POST['cpf_cliente'];
        $telefone_cliente = $_POST['telefone_cliente'];
        
        // Validando o CPF (exemplo simples)
        if (strlen($cpf_cliente) != 11) {
            $message = "O CPF deve ter 11 dígitos.";
        } else {
            // Inserir o novo cliente no banco de dados
            $sql_cliente = "INSERT INTO cliente (cpf, nome, telefone) VALUES (?, ?, ?)";
            $stmt_cliente = $conexao->prepare($sql_cliente);
            $stmt_cliente->bind_param('sss', $cpf_cliente, $nome_cliente, $telefone_cliente);
            if ($stmt_cliente->execute()) {
                // Pega o ID do cliente inserido
                $cliente_id = $conexao->insert_id;
                $stmt_cliente->close();
            } else {
                $message = "Erro ao cadastrar o cliente: " . $stmt_cliente->error;
            }
        }
    } else {
        // Se o cliente já existir, pega o ID do cliente selecionado
        $cliente_id = $_POST['cliente_id'];
    }

    // Buscar o preço e o estoque do produto
    $sql_estoque = "SELECT estoque, preco FROM produto WHERE id_produto = $id_produto";
    $result = $conexao->query($sql_estoque);
    $produto = mysqli_fetch_assoc($result);

    // Verificar se há estoque suficiente
    if ($produto['estoque'] < $quantidade) {
        $message = "Estoque insuficiente para essa venda.";
    } else {
        // Calcular o valor total da compra usando o preço do banco de dados
        $preco_unitario = $produto['preco'];
        $valor_compra = $quantidade * $preco_unitario;

        // Registrar a compra com o cliente associado
        $sql_compra = "INSERT INTO compra (valor_compra, valor_pago, data_compra, cliente_id) 
                    VALUES ($valor_compra, 0, NOW(), $cliente_id)";
        $conexao->query($sql_compra);
        $id_compra = $conexao->insert_id;

        // Atualizar o estoque do produto
        $sql_update_estoque = "UPDATE produto SET estoque = estoque - $quantidade WHERE id_produto = $id_produto";
        $conexao->query($sql_update_estoque);

        // Registrar dívida se o pagamento for a prazo
        if ($forma_pagamento == 'aprazo') {
            $sql_divida = "INSERT INTO divida (cliente_id, valor_divida) VALUES ($cliente_id, $valor_compra)";
            $conexao->query($sql_divida);
        }

        $message = "Venda registrada com sucesso!";
        header("Location: vendas.php");
        exit();
    }
}

// Buscar produtos do estoque para a seleção de vendas
$sql_produtos = "SELECT id_produto, nome_produto, estoque, preco FROM produto WHERE estoque > 0";
$result_produtos = $conexao->query($sql_produtos);

// Buscar clientes cadastrados
$sql_clientes = "SELECT cliente_id, nome FROM cliente";
$result_clientes = $conexao->query($sql_clientes);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/vendas.css">
    <title>Registrar Venda</title>
</head>
<body>
    <nav>
        <h1>Registrar Venda</h1>
    </nav>

    <!-- Mensagem de erro ou sucesso -->
    <?php if ($message): ?>
        <p style="color: red;"><?= $message; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="id_produto">Produto:</label>
        <select name="id_produto" required>
            <option value="">Selecione um produto</option>
            <?php 
            while ($produto = mysqli_fetch_assoc($result_produtos)) {
                echo "<option value='".$produto['id_produto']."'>".$produto['nome_produto']." - R$ ".$produto['preco']." (Estoque: ".$produto['estoque'].")</option>";
            }
            ?>
        </select><br>

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" min="1" required><br>

        <label for="forma_pagamento">Forma de Pagamento:</label>
        <select name="forma_pagamento" required>
            <option value="avista">À Vista</option>
            <option value="aprazo">A Prazo</option>
        </select><br>

        <hr>

        <!-- Seleção de cliente ou cadastro de novo cliente -->
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" id="cliente_id" onchange="toggleNovoCliente(this)" required>
            <option value="">Selecione um cliente</option>
            <option value="novo_cliente">Novo Cliente</option>
            <?php 
            while ($cliente = mysqli_fetch_assoc($result_clientes)) {
                echo "<option value='{$cliente['cliente_id']}'>{$cliente['nome']}</option>";
            }
            ?>
        </select><br>

        <!-- Formulário para cadastro de novo cliente -->
        <div id="novo_cliente_form" style="display:none;">
            <h3>Cadastro de Novo Cliente</h3>
            <label for="nome_cliente">Nome:</label>
            <input type="text" name="nome_cliente" id="nome_cliente" required><br>

            <label for="cpf_cliente">CPF:</label>
            <input type="text" name="cpf_cliente" id="cpf_cliente" maxlength="11" required><br>

            <label for="telefone_cliente">Telefone:</label>
            <input type="text" name="telefone_cliente" id="telefone_cliente" required><br>
        </div><br>

        <button type="submit">Registrar Venda</button>
    </form>

    <a href="index.php"><button>Voltar ao Menu</button></a>

    <script>
        function toggleNovoCliente(select) {
            var form = document.getElementById('novo_cliente_form');
            if (select.value === 'novo_cliente') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>
