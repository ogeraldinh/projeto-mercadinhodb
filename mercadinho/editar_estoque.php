<?php
    session_start();
    include('config.php');
    if((!isset($_SESSION['id_usuario']) == true) and (!isset($_SESSION['senha_usuario']) == true)) {
        unset($_SESSION['id_usuario']);
        unset($_SESSION['senha_usuario']);
        header('Location: login.php');
        exit();
    }

    $logado = $_SESSION['id_usuario'];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_produto = $_POST['id_produto'];
        $quantidade = $_POST['quantidade'];
        $operacao = $_POST['operacao']; // adicionar ou subtrair
        
        // Verificar se a quantidade é válida
        if ($quantidade > 0) {
            if ($operacao == 'adicionar') {
                // Adicionar ao estoque
                $sql = "UPDATE produto SET estoque = estoque + ? WHERE id_produto = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param('ii', $quantidade, $id_produto);
                $stmt->execute();
                $stmt->close();
            } elseif ($operacao == 'subtrair') {
                // Subtrair do estoque, mas garantir que não fique negativo
                $sql = "SELECT estoque FROM produto WHERE id_produto = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param('i', $id_produto);
                $stmt->execute();
                $stmt->bind_result($estoque_atual);
                $stmt->fetch();
                $stmt->close();
                
                if ($estoque_atual >= $quantidade) {
                    // Subtrair do estoque
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
    }

    // Exibição dos produtos
    $sql = "SELECT produto.id_produto, produto.nome_produto, produto.estoque, fornecedor.nome 
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
                <th>...</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while($user_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$user_data['id_produto']."</td>";
                    echo "<td>".$user_data['nome_produto']."</td>";
                    echo "<td>".$user_data['estoque']."</td>";
                    echo "<td>".$user_data['nome']."</td>";
                    echo "<td>
                        <form action='' method='POST'>
                            <input type='hidden' name='id_produto' value='".$user_data['id_produto']."'>
                            <input type='number' name='quantidade' min='1' required>
                            <button type='submit' name='operacao' value='adicionar'>Adicionar</button>
                            <button type='submit' name='operacao' value='subtrair'>Subtrair</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>

</body>
</html>
