<?php
session_start();
include('config.php');

// Verificação de login do usuário
if ((!isset($_SESSION['id_usuario']) == true) and (!isset($_SESSION['senha_usuario']) == true)) {
    unset($_SESSION['id_usuario']);
    unset($_SESSION['senha_usuario']);
    header('Location: login.php');
    exit();
}

$logado = $_SESSION['id_usuario'];

// Atualização do estoque e preço ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produto = $_POST['id_produto'];

    // Atualização da quantidade
    if (isset($_POST['quantidade']) && $_POST['quantidade'] > 0) {
        $quantidade = $_POST['quantidade'];
        $operacao = $_POST['operacao']; // Operação de adicionar ou subtrair

        if ($operacao == 'adicionar') {
            // Adicionar quantidade ao estoque
            $sql = "UPDATE produto SET estoque = estoque + ? WHERE id_produto = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('ii', $quantidade, $id_produto);
            $stmt->execute();
            $stmt->close();
        } elseif ($operacao == 'subtrair') {
            // Verificar estoque atual para evitar valores negativos
            $sql = "SELECT estoque FROM produto WHERE id_produto = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('i', $id_produto);
            $stmt->execute();
            $stmt->bind_result($estoque_atual);
            $stmt->fetch();
            $stmt->close();

            if ($estoque_atual >= $quantidade) {
                // Subtrair quantidade do estoque
                $sql = "UPDATE produto SET estoque = estoque - ? WHERE id_produto = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param('ii', $quantidade, $id_produto);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Não há estoque suficiente para essa operação!";
            }
        }
    }

    // Atualização do preço do produto
    if (isset($_POST['preco']) && $_POST['preco'] > 0) {
        $preco = $_POST['preco'];
        // Atualizar o preço do produto
        $sql = "UPDATE produto SET preco = ? WHERE id_produto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('di', $preco, $id_produto);
        $stmt->execute();
        $stmt->close();
    }
}

// Exibir produtos com o preço incluído
$sql = "SELECT produto.id_produto, produto.nome_produto, produto.estoque, produto.preco, fornecedor.nome AS fornecedor_nome 
        FROM produto
        JOIN fornecedor ON produto.id_fornecedor = fornecedor.id_fornecedor
        ORDER BY produto.nome_produto";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/estoque.css">
    <title>Estoque</title>
</head>
<body>
    <nav>
        <h1>Estoque</h1>
    </nav>

    <div class="btn-options">
        <a href="index.php"><button>Voltar ao Menu</button></a>
        <a href="estoque.php"><button>Voltar ao estoque</button></a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Fornecedor</th>
                <th>Preço</th>
                <th>Modificar quantidade</th>
                <th>Modificar preço</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Exibir cada produto, incluindo o preço
                while($user_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$user_data['id_produto']."</td>";
                    echo "<td>".$user_data['nome_produto']."</td>";
                    echo "<td>".$user_data['estoque']."</td>";
                    echo "<td>".$user_data['fornecedor_nome']."</td>";
                    echo "<td>R$ ".number_format($user_data['preco'], 2, ',', '.')."</td>"; // Formato de preço
                    echo "<td>
                        <form action='' method='POST'>
                            <input type='hidden' name='id_produto' value='".$user_data['id_produto']."'>
                            
                            <!-- Campo para modificar a quantidade -->
                            <label for='quantidade'>Quantidade:</label>
                            <input type='number' name='quantidade' min='1' required>
                            <button type='submit' name='operacao' value='adicionar'>Adicionar</button>
                            <button type='submit' name='operacao' value='subtrair'>Subtrair</button>
                        </form>
                    </td>";

                    // Formulário para atualizar o preço, separado do formulário de quantidade
                    echo "<td>
                        <form action='' method='POST'>
                            <input type='hidden' name='id_produto' value='".$user_data['id_produto']."'>
                            <label for='preco'>Preço:</label>
                            <input type='number' name='preco' step='0.01' min='0.01' value='".$user_data['preco']."' required>
                            <button type='submit'>Alterar Preço</button>
                        </form>
                    </td>";

                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
