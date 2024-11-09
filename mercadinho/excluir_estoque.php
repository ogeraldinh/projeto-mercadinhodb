<?php
require_once('config.php');
if((!isset($_SESSION['id_usuario']) == true) and (!isset($_SESSION['senha_usuario']) == true)) {
    unset($_SESSION['id_usuario']);
    unset($_SESSION['senha_usuario']);
    header('Location: login.php');
    exit();
}


if (isset($_GET['id'])) {
    $id_produto = intval($_GET['id']);

    // Excluir o produto pelo ID
    $stmt = $conexao->prepare("DELETE FROM produto WHERE id_produto = ?");
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $stmt->close();

    // Atualizar os IDs para manter a sequência contínua
    $sql = "SET @count = 0;";
    $conexao->query($sql);
    $sql = "UPDATE produto SET id_produto = @count:= @count + 1;";
    $conexao->query($sql);
    $sql = "ALTER TABLE produto AUTO_INCREMENT = 1;";
    $conexao->query($sql);

    // Redirecionar de volta para a página do estoque
    header("Location: estoque.php");
    exit();
}
?>
