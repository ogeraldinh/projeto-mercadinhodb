<?php
    session_start();
    include('config.php');
    
    // Verificar se o usuário está logado
    if ((!isset($_SESSION['id_usuario']) == true) and (!isset($_SESSION['senha_usuario']) == true)) {
        unset($_SESSION['id_usuario']);
        unset($_SESSION['senha_usuario']);
        header('Location: login.php');
        exit();
    }

    // Exibir a lista de fornecedores
    $sql = "SELECT id_fornecedor, nome, endereco, cnpj FROM fornecedor ORDER BY nome";
    $result = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/estoque.css">
    <title>Fornecedores</title>
    <script>
        // Função para confirmar a exclusão
        function confirmarExclusao(id) {
            if (confirm("Tem certeza que deseja excluir este fornecedor?")) {
                window.location.href = "excluir_fornecedor.php?id=" + id;
            }
        }
    </script>
</head>
<body>
    <h1>Fornecedores</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>CNPJ</th>
                <th>...</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Loop para exibir os fornecedores
                while ($fornecedor = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fornecedor['id_fornecedor'] . "</td>";
                    echo "<td>" . $fornecedor['nome'] . "</td>";
                    echo "<td>" . $fornecedor['endereco'] . "</td>";
                    echo "<td>" . $fornecedor['cnpj'] . "</td>";
                    echo "<td>
                            <a href='editar_fornecedor.php?id=" . $fornecedor['id_fornecedor'] . "'>Editar</a>
                            <a href='#' onclick='confirmarExclusao(" . $fornecedor['id_fornecedor'] . ")' style='color: red;'>Excluir</a>
                          </td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
    <a href="index.php"><button>Voltar ao Menu</button></a>
</body>
</html>
