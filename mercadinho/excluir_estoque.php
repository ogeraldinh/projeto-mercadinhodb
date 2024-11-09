<?php
include('config.php'); // conexão está sendo feita corretamente

if (isset($_GET['id'])) {
    $id_produto = intval($_GET['id']);

    // Iniciar a exclusão do produto
    $stmt = $conexao->prepare("DELETE FROM produto WHERE id_produto = ?");
    $stmt->bind_param("i", $id_produto);

    // Verificar se a execução foi bem-sucedida
    if ($stmt->execute()) {
            // Redireciona para a página de estoque
            header("Location: estoque.php");
            exit();
    } else {
        echo "Erro ao excluir o produto.";
    }

    $stmt->close();
}
?>
