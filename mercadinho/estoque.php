<?php
    session_start();
    include('config.php');

    // Verificar se o usuário está logado
    if (!isset($_SESSION['id_usuario'])) {
        header('Location: login.php');
        exit();
    }

    $logado = $_SESSION['id_usuario'];
    
    // Alteração da consulta para buscar também o preço dos produtos
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
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/estoque.css">
    <title>Estoque</title>
    <script>
        function confirmarExclusao(id) {
            if (confirm("Tem certeza que deseja excluir este produto?")) {
                window.location.href = "excluir_estoque.php?id=" + id;
            }
        }
    </script>
</head>
<body>
    <nav>
        <h1>Estoque</h1>
    </nav>

    <div class="btn-options">
        <a href="index.php"><button>Voltar ao Menu</button></a>
        <a href="adicionar_produto.php"><button>Adicionar Produto</button></a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Fornecedor</th>
                <th>Preço</th>
                <th>...</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Loop para exibir os produtos com nome do fornecedor e preço
                while ($user_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$user_data['id_produto']."</td>";
                    echo "<td>".$user_data['nome_produto']."</td>";
                    echo "<td>".$user_data['estoque']."</td>";
                    echo "<td>".$user_data['fornecedor_nome']."</td>";  // Exibe o nome do fornecedor
                    echo "<td>R$ ".number_format($user_data['preco'], 2, ',', '.')."</td>"; // Exibe o preço formatado
                    echo "<td>
                        <a href='editar_estoque.php?id=".$user_data['id_produto']."'><button>Editar</button></a>
                        <a href='#' onclick='confirmarExclusao(".$user_data['id_produto'].")' style='color: red;'>Excluir</a>
                    </td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
