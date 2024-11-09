<?php
    include('config.php');

    // Verifica se o id do fornecedor foi passado na URL
    if (isset($_GET['id'])) {
        $id_fornecedor = $_GET['id'];

        // Verifica se o fornecedor existe antes de tentar excluir
        $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $id_fornecedor);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // O fornecedor existe, então excluímos
            $stmt->close();

            // Excluindo o fornecedor
            $sql = "DELETE FROM fornecedor WHERE id_fornecedor = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('i', $id_fornecedor);

            if ($stmt->execute()) {
                header('Location: fornecedores.php'); // Redireciona de volta à página de fornecedores
                exit();
            } else {
                echo "Erro ao excluir o fornecedor: " . $stmt->error;
            }
        } else {
            echo "Fornecedor não encontrado!";
        }

        $stmt->close();
    } else {
        echo "ID de fornecedor não especificado.";
    }
?>
