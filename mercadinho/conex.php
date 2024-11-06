<?php
$servername = "localhost";
$database = "mercado";
$username = "root";
$password = "";
// Criar conexão com o banco de dados
$conn = mysqli_connect($servername, $username, $password, $database);
// Checar conexão com o banco de dados
if (!$conn) {
    die("Falha ao conectar ao banco de dados" . mysqli_connect_error());
}
    return($conn);

?>