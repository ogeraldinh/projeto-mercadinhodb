<?php
    session_start();
    include('config.php');
    if ((!isset($_SESSION['id_usuario']) == true) and (!isset($_SESSION['senha_usuario']) == true)) {
        unset($_SESSION['id_usuario']);
        unset($_SESSION['senha_usuario']);
        header('Location: login.php');
        exit();
    }

    $logado = $_SESSION['id_usuario'];
    
    // Alteração da consulta para fazer o JOIN entre produto e fornecedor
    $sql = "SELECT produto.id_produto, produto.nome_produto, produto.estoque, fornecedor.nome 
            FROM produto
            JOIN fornecedor ON produto.id_fornecedor = fornecedor.id_fornecedor
            ORDER BY produto.nome_produto";
    $result = $conexao->query($sql);

    // print_r($result);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/estoque.css">
    <title>Estoque</title>
    <script>
        function confirmarExclusao(id) {
            if (confirm("Tem certeza que deseja excluir este produto?")) {
                window.location.href = "excluir_estoque.php?id=" + id;0
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
                <th>...</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Loop para exibir os produtos com o nome do fornecedor
                while ($user_data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$user_data['id_produto']."</td>";
                    echo "<td>".$user_data['nome_produto']."</td>";
                    echo "<td>".$user_data['estoque']."</td>";
                    echo "<td>".$user_data['nome']."</td>";  // Exibe o nome do fornecedor
                    echo "<td>
                        <a href='editar_estoque.php?id=$user_data[id_produto]'><buttom>Editar</button></a>
                         <a href='#' onclick='confirmarExclusao(".$user_data['id_produto'].")' style='color: red;'>Excluir</a>
                    </td>";
                    
                }
            
            ?>
        </tbody>
    </table>




    
</body>
</html>
