<?php 
    require_once('conex.php');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <title>Formulário</title>
</head>
<body>
    <nav>
        <h1><a href="#">MERCADINHO</a></h1>
    </nav>
    <form action="" method="post">
        <h1>Login</h1>
        <div class="input-form">
            <label for="user">Informe o usuário:</label>
            <input type="text" id="user" name='user'>
            <label for="password">Informe a senha:</label>
            <input type="password" id="password" name="pass">
        </div>
        <input type="submit" id="submit" value="Entrar">

        <span>Não possui cadastro? <a href="cadastro.php">Cadastrar</a></span>
    </form>

    <?php
    // Validando o login do usuário
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $user=$_POST['user'];
            $pass=$_POST['pass'];
    //Consultar o banco de dados  na tabela usuario
            $stmt=$conn->prepare('SELECT * FROM usuario WHERE nome_usuario = ?');
            $stmt ->bind_param('s',$user);
            $stmt->execute();
           $result=$stmt->get_result();
    //verifição de senha e user
           if ($result->num_rows>0) {
                $usuario=$result->fetch_assoc();
                if (password_verify($pass,$usuario['senha_usuario'])){
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                    header("Location: index.php"); //direcionar para a página inicial do site
                    exit();
                }
                else{
                    echo('Senha inválida');
                }
           }
           $stmt->close();
            
        } 
    ?>
</body>
</html>