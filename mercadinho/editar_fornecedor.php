<?php 
    session_start();
    include('config.php');
    if ((!isset($_SESSION['id_usuario']) == true) and (!isset($_SESSION['senha_usuario']) == true)) {
        unset($_SESSION['id_usuario']);
        unset($_SESSION['senha_usuario']);
        header('Location: login.php');
        exit();
    }

    // Verificar se o ID do fornecedor foi passado na URL
    if (isset($_GET['id'])) {
        $id_fornecedor = $_GET['id'];

        // Consultar os dados do fornecedor
        $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $id_fornecedor);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $fornecedor = $result->fetch_assoc();
        } else {
            die("Fornecedor não encontrado.");
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        die("ID do fornecedor não fornecido.");
    }

    // Atualização dos dados do fornecedor
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome_fornecedor = $_POST['nome_fornecedor'];
        $endereco = $_POST['endereco'];
        $cnpj = $_POST['cnpj'];

        if ($nome_fornecedor && $endereco && $cnpj) {
            $sql = "UPDATE fornecedor SET nome = ?, endereco = ?, cnpj = ? WHERE id_fornecedor = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('sssi', $nome_fornecedor, $endereco, $cnpj, $id_fornecedor);

            if ($stmt->execute()) {
                $message = "Fornecedor atualizado com sucesso!";
            } else {
                $message = "Erro ao atualizar o fornecedor: " . $stmt->error;
            }

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
    <title>Editar Fornecedor</title>
    <link rel="stylesheet" href="assets/css/estoque.css">
</head>
<body>
    <h1>Editar Fornecedor</h1>
    <a href="fornecedores.php"><button>Voltar à lista de fornecedores</button></a>

    <form action="" method="post">
        <label for="nome_fornecedor">Nome:</label>
        <input type="text" id="nome_fornecedor" name="nome_fornecedor" value="<?= $fornecedor['nome']; ?>" required>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" value="<?= $fornecedor['endereco']; ?>" required>

        <label for="cnpj">CNPJ:</label>
        <input type="text" id="cnpj" name="cnpj" value="<?= $fornecedor['cnpj']; ?>" required>

        <button type="submit">Atualizar</button>
    </form>

    <?php if (isset($message)): ?>
        <div class="message" style="text-align: center; color: <?= strpos($message, 'Erro') === false ? 'green' : 'red'; ?>;">
            <?= $message; ?>
        </div>
    <?php endif; ?>
</body>
</html>
