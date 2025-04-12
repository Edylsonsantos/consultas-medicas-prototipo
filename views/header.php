<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/consultas-medicas/login.php");
    exit();
}

$nomeUsuario = $_SESSION['user_nome'];
$cargoUsuario = $_SESSION['user_cargo'];

if ($cargoUsuario === 'IT') {
    $cargoAbreviado = 'Administrador';
} elseif ($cargoUsuario === 'Gestor Hospital') {
    $cargoAbreviado = 'Gestor';
} elseif ($cargoUsuario === 'Recepcionista') {
    $cargoAbreviado = 'Recepcionista';
} else {
    $cargoAbreviado = $cargoUsuario;
}

$hora = date("H");
if ($hora >= 5 && $hora < 12) {
    $saudacao = "Bom dia";
} elseif ($hora >= 12 && $hora < 18) {
    $saudacao = "Boa tarde";
} else {
    $saudacao = "Boa noite";
}
?>
<header>
    <div class="user-info">
        <span><?php echo "$saudacao, $nomeUsuario ($cargoAbreviado)"; ?></span>
    </div>
    <h1>REGISTRO HOSPITALAR</h1>
</header>
