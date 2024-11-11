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

// Verificação de ação de adicionar dívida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_divida'])) {
    // Coletando dados do formulário
    $cliente_id = $_POST['cliente_id'];
    $valor_divida = $_POST['valor_divida'];

    // Inserção ou atualização da dívida no banco de dados
    if ($cliente_id && $valor_divida > 0) {
        // Verificar se o cliente já tem uma dívida
        $stmt_check = $conexao->prepare('SELECT valor_divida FROM divida WHERE cliente_id = ?');
        $stmt_check->bind_param('i', $cliente_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            // Se o cliente já tiver uma dívida, atualiza o valor
            $stmt_check->bind_result($valor_atual);
            $stmt_check->fetch();
            $novo_valor_divida = $valor_atual + $valor_divida;

            $stmt_update = $conexao->prepare('UPDATE divida SET valor_divida = ? WHERE cliente_id = ?');
            $stmt_update->bind_param('di', $novo_valor_divida, $cliente_id);

            if ($stmt_update->execute()) {
                $message = "Dívida do cliente atualizada com sucesso!";
            } else {
                $message = "Erro ao atualizar a dívida: " . $stmt_update->error;
            }
            $stmt_update->close();
        } else {
            // Se o cliente não tiver uma dívida, insere uma nova
            $stmt_insert = $conexao->prepare('INSERT INTO divida (cliente_id, valor_divida) VALUES (?, ?)');
            $stmt_insert->bind_param('id', $cliente_id, $valor_divida);

            if ($stmt_insert->execute()) {
                $message = "Dívida adicionada com sucesso!";
            } else {
                $message = "Erro ao adicionar dívida: " . $stmt_insert->error;
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    } else {
        $message = "Por favor, selecione um cliente e insira um valor de dívida válido!";
    }
}

// Consulta para listar os clientes para adicionar ou atualizar a dívida
$clientes_result = $conexao->query("SELECT cliente_id, nome FROM cliente");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/divida.css">
    <title>Adicionar ou Atualizar Dívida</title>
</head>
<body>
    <nav>
        <h1>Adicionar ou Atualizar Dívida</h1>
    </nav>

    <div class="message" style="text-align: center; color: <?= strpos($message, 'Erro') === false ? 'green' : 'red'; ?>;">
        <?= $message; ?>
    </div>

    <div class="btn-options">
        <a href="debitos.php"><button>Voltar ao Gerenciamento de Dívidas</button></a>
    </div>

    <h2>Adicionar ou Atualizar Dívida</h2>
    <form action="" method="post">
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" required>
            <option value="">Selecione um cliente</option>
            <?php while ($cliente = $clientes_result->fetch_assoc()): ?>
                <option value="<?= $cliente['cliente_id']; ?>"><?= $cliente['nome']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <label for="valor_divida">Valor da Dívida:</label>
        <input type="number" name="valor_divida" step="0.01" min="0.01" required>

        <button type="submit" name="add_divida">Adicionar ou Atualizar Dívida</button>
    </form>

</body>
</html>
