<?php
    session_start();
    include('config.php');
    if((!isset($_SESSION['id_usuario']) == true) and (!isset($_SESSION['senha_usuario']) == true)) {
        unset($_SESSION['id_usuario']);
        unset($_SESSION['senha_usuario']);
        header('Location: login.php');
    }

    $logado = $_SESSION['id_usuario'];
    $sql = "SELECT * FROM produto ORDER BY nome_produto";
    $result = $conexao -> query($sql);

    // print_r($result);
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
    <h1>Estoque</h1>

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
                    echo "<td>".$user_data['id_fornecedor']."</td>";
                    echo "<td>
                        <a href='editar_estoque.php?id=$user_data[id_produto]'><buttom>Editar</button></a>
                        <a href='#'><buttom>Excluir</button></a>
                    </td>";
                }
            
            ?>
        </tbody>
    </table>



    <a href="index.php"><button>Voltar ao Menu</button></a>
</body>
</html>