<?php
require_once('conex.php');
$message = ''; // Variável para armazenar a mensagem de retorno

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_user = $_POST['nome_usuario'];
    $senha_user = $_POST['senha_usuario'];
    $email_user = $_POST['email_usuario'];

    // Verifica se o email já está cadastrado
    $stmt = $conn->prepare('SELECT * FROM usuario WHERE email_usuario = ?');
    $stmt->bind_param('s', $email_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Se o email já existir, define a mensagem de erro
        $message = "Este email já está cadastrado. Tente outro ou faça login.";
    } else {
        // Criptografa a senha
        $senha_hash = password_hash($senha_user, PASSWORD_DEFAULT);

        // Prepara e executa a inserção no banco de dados
        $stmt = $conn->prepare('INSERT INTO usuario (nome_usuario, senha_usuario, email_usuario) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $nome_user, $senha_hash, $email_user);

        if ($stmt->execute()) {
            $message = "Usuário cadastrado com sucesso!";
        } else {
            $message = "Erro ao cadastrar o usuário: " . $stmt->error;
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <title>Cadastro</title>
</head>
<body>
    <nav>
        <h1><a href="#">MERCADINHO</a></h1>
    </nav>
    <form action="" method="post">
        <h1>Cadastro</h1>
        <div class="input-form">
            <label for="email">Informe o e-mail:</label>
            <input type="email" name="email_usuario" id="email" required>

            <label for="user">Informe o usuário:</label>
            <input type="text" id="user" name="nome_usuario" required>

            <label for="password">Informe a senha:</label>
            <input type="password" id="password" name="senha_usuario" required>
        </div>
        <input type="submit" id="submit" value="Cadastrar">
        
        <!-- Exibe a mensagem de sucesso ou erro -->
        <?php if ($message): ?>
            <div class="message" style="text-align: center; color: <?= strpos($message, 'Erro') === false ? 'red' : 'red'; ?>;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <span>Já possui conta? <a href="login.php">Entrar</a></span>
    </form>
</body>
</html>
