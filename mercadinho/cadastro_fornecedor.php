<?php
    // Exibir erros para depuração
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    include('config.php');

    // Verificando se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Coletando os dados do formulário
        $nome_fornecedor = $_POST['nome_fornecedor'];
        $endereco = $_POST['endereco'];
        $cnpj = $_POST['cnpj'];

        // Inserindo o fornecedor no banco de dados
        if ($nome_fornecedor && $endereco && $cnpj) {
            $stmt = $conexao->prepare('INSERT INTO fornecedor (nome, endereco, cnpj) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $nome_fornecedor, $endereco, $cnpj);

            if ($stmt->execute()) {
                
                $id_fornecedor = $stmt->insert_id;  // Pega o ID do novo fornecedor
                // Redirecionar para a página de fornecedores após o cadastro
                header('Location: cadastro_fornecedor.php');
                $message = "Fornecedor cadastrado com sucesso!";
                exit();
            } else {
                $message = "Erro ao cadastrar o fornecedor: " . $stmt->error;
            }

            // Fecha a declaração
            $stmt->close();
        } else {
            $message = "Por favor, preencha todos os campos!";
        }
    }

    // Recebe o nome do fornecedor da URL, se disponível
    $nome_fornecedor = isset($_GET['nome_fornecedor']) ? $_GET['nome_fornecedor'] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/estoque.css">
    <title>Cadastro de Fornecedor</title>
</head>
<body>
    <form action="" method="post">
        <h1>Cadastro de Fornecedor</h1>

        <label for="nome_fornecedor">Nome do Fornecedor:</label>
        <input type="text" id="nome_fornecedor" name="nome_fornecedor" value="<?= $nome_fornecedor ?>" required>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>

        <label for="cnpj">CNPJ:</label>
        <input type="text" id="cnpj" name="cnpj" required>

        <div class="form-btn">
            <button type="submit">Cadastrar Fornecedor</button>
        </div>

        <?php if (isset($message)): ?>
            <div class="message" style="text-align: center; color: <?= strpos($message, 'Erro') === false ? 'green' : 'red'; ?>;">
                <?= $message; ?>
            </div>
        <?php endif; ?>
    </form>

    <a href="index.php"><button>Voltar ao Menu</button></a>
    <a href="estoque.php"><button>Voltar ao Estoque</button></a>
    <a href="fornecedores.php"><button>Voltar aos Fornecedores</button></a>
</body>
</html>
