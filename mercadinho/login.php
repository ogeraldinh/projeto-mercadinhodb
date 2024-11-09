<?php 
require_once('conex.php');
session_start();
$message = ''; // Variável para armazenar a mensagem de retorno

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Consulta ao banco de dados na tabela usuario
    $stmt = $conn->prepare('SELECT * FROM usuario WHERE email_usuario = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificação de senha e email
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($pass, $usuario['senha_usuario'])){
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['email_usuario'] = $usuario['email_usuario'];
            header("Location: index.php"); // Redireciona para a página inicial
            exit();
        } else {
            $message = 'Senha incorreta.';
        }
    } else {
        $message = 'Usuário não encontrado.';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <title>Login</title>
</head>
<body>
    <nav>
        <h1><a href="#">MERCADINHO</a></h1>
    </nav>
    <form action="" method="post">
        <h1>Login</h1>
        <div class="input-form">
            <label for="email">Informe o email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Informe a senha:</label>
            <input type="password" id="password" name="pass" required>
        </div>
        <input type="submit" id="submit" value="Entrar">

        <span>Não possui cadastro? <a href="cadastro.php">Cadastrar</a></span>
        <!-- Exibe a mensagem de erro caso o login falhe -->
        <?php if (!empty($message)): ?>
            <div class="message" style="text-align: center; color: red;margin-top: 15px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </form>

    
</body>
</html>
