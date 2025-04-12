<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";
$message = "";
$style = "none";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o ID foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Exclui o médico do banco de dados
    $sql_delete = "DELETE FROM medicos WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Médico excluído com sucesso!";
        $style = "success";
    } else {
        $message = "Erro ao excluir o médico. Verifique se há registros relacionados.";
        $style = "error";
    }
    $stmt->close();
} else {
    $message = "ID do médico não fornecido.";
    $style = "warning";
}

// Fecha a conexão com o banco
$conn->close();

// Redireciona para a página de médicos
header("Location: view-medicos.php?status=$style&message=$message");
exit();
?>