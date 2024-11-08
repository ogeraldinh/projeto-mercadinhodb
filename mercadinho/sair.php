<?php
    session_start();
    unset ($_SESSION['id_usuario']);
    unset($_SESSION['nome_usuario']);
    session_destroy();
    header('location:login.php')
?>