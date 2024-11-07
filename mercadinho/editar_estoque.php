<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/cadastro.css">
    <title>Document</title>
</head>
<body>
<nav>
        <h1><a href="#">MERCADINHO</a></h1>
    </nav>
    <form action="" method="post">
        <h1>Cadastro</h1>
        <div class="input-form">
            <label for="email" >Informe o e-mail:</label>
            <input type="email" name="email_usuario" id="email">

            <label for="user">Informe o usuário:</label>
            <input type="text" id="user" name="nome_usuario" required>
            <label for="password">Informe a senha:</label>
            <input type="password" id="password" name="senha_usuario" required>
        </div>
        <input type="submit" id="submit" value="Entrar">

    
        
    </form>

    <a href="estoque.php"><button>Voltar ao Estoque</button></a>
    
</body>
</html>