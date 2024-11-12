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

    // Função para exibir uma mensagem de sucesso ou erro
    $message = "";

    // Verificação de ações de quitar dívida
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quitar_divida'])) {
        // Ação para quitar dívida
        $id_divida = $_POST['id_divida'];
        $stmt = $conexao->prepare("DELETE FROM divida WHERE id_divida = ?");
        $stmt->bind_param('i', $id_divida);
        if ($stmt->execute()) {
            $message = "Dívida quitada com sucesso!";
        } else {
            $message = "Erro ao quitar dívida: " . $stmt->error;
        }
        $stmt->close();
    }

    // Consulta para exibir as dívidas e o nome do cliente
    $sql = "SELECT divida.id_divida, divida.valor_divida, cliente.nome AS nome_cliente 
            FROM divida 
            JOIN cliente ON divida.cliente_id = cliente.cliente_id
            ORDER BY cliente.nome";
    $result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/estoque.css">
    <title>Gerenciamento de Dívidas</title>
</head>
<body>
    <nav>
        <h1>Gerenciamento de Dívidas</h1>
    </nav>

    <div class="message" style="text-align: center; color: <?= strpos($message, 'Erro') === false ? 'green' : 'red'; ?>;">
        <?= $message; ?>
    </div>

    <div class="btn-options">
        <a href="index.php"><button>Voltar ao Menu</button></a>
        <a href="adicionar_divida.php"><button>Adicionar Nova Dívida</button></a>
    </div>

    <h2>Lista de Dívidas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Valor da Dívida</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($divida = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $divida['id_divida']; ?></td>
                    <td><?= $divida['nome_cliente']; ?></td>
                    <td>R$ <?= number_format($divida['valor_divida'], 2, ',', '.'); ?></td>
                    <td>
                        <form action="" method="post" style="display:inline-block;">
                            <input type="hidden" name="id_divida" value="<?= $divida['id_divida']; ?>">
                            <button type="submit" name="quitar_divida">Quitar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
