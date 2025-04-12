<?php
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Destroi a sessão e redireciona para a página de login
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
} else {
    // Caso o usuário não esteja logado, redireciona diretamente para o login
    header("Location: login.php");
    exit();
}
?>
