<?php
    session_start();
    include('config.php');
    if ((!isset($_SESSION['id_usuario']) == true) and (!isset($_SESSION['senha_usuario']) == true)) {
        unset($_SESSION['id_usuario']);
        unset($_SESSION['senha_usuario']);
        header('Location: login.php');
        exit();
    }

    // Verificando se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Coletando os dados do formulário
        $nome_produto = $_POST['nome_produto'];
        $estoque = $_POST['estoque'];
        $nome_fornecedor = strtolower($_POST['nome_fornecedor']);  // Convertendo para caixa-baixa

        // Verificar se o fornecedor já existe na tabela fornecedor
        $sql = "SELECT id_fornecedor FROM fornecedor WHERE LOWER(nome) = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('s', $nome_fornecedor);
        $stmt->execute();
        $stmt->bind_result($id_fornecedor);
        $stmt->fetch();
        $stmt->close();

        // Se o fornecedor não existe, redireciona para a página de cadastro de fornecedor
        if (!$id_fornecedor) {
            // Redireciona para a página de cadastro de fornecedor com o nome do fornecedor
            header('Location: cadastro_fornecedor.php?nome_fornecedor=' . urlencode($nome_fornecedor));
            exit();
        }

        //Depois de obter o id_fornecedor, podemos adicionar o produto
        if ($nome_produto && $estoque && $id_fornecedor) {
            // Inserindo o produto no banco de dados
            $stmt = $conexao->prepare('INSERT INTO produto (nome_produto, estoque, id_fornecedor) VALUES (?, ?, ?)');
            $stmt->bind_param('sii', $nome_produto, $estoque, $id_fornecedor);

            // Executa a consulta e verifica se a inserção foi bem-sucedida
            if ($stmt->execute()) {
                $message = "Produto cadastrado com sucesso!";
            } else {
                $message = "Erro ao cadastrar o produto: " . $stmt->error;
            }

            // Fecha a declaração
            $stmt->close();
        } else {
            $message = "Por favor, preencha todos os campos!";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <title>Adicionar Produto</title>
</head>
<body>

    <nav>
        <h1>Adicionar Produto</h1>
    </nav>

    <form action="" method="post">
        <div class="input-form">
            <label for="nome_produto">Nome do Produto:</label>
            <input type="text" id="nome_produto" name="nome_produto" required>

            <label for="estoque">Quantidade do Produto:</label>
            <input type="number" id="estoque" name="estoque" required min="1">

            <label for="nome_fornecedor">Fornecedor:</label>
            <input type="text" id="nome_fornecedor" name="nome_fornecedor" required>
        </div>

        <div class="submit">
            <button type="submit">Enviar</button>
        </div>

        <?php if (isset($message)): ?>
            <div class="message" style="text-align: center; color: <?= strpos($message, 'Erro') === false ? 'green' : 'red'; ?>;">
                <?= $message; ?>
            </div>
        <?php endif; ?>
    </form>

    <a href="estoque.php" class="btn-back"><button >Voltar ao estoque</button></a>
</body>
</html>
