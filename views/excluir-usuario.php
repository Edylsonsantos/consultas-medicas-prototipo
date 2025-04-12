<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital"; // Altere para o nome do seu banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o ID do funcionário foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verifica se o funcionário existe no banco de dados
    $sql_check = "SELECT id FROM funcionarios WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows == 0) {
        // Se o funcionário não existir, redireciona com uma mensagem de erro
        header("Location: usuario.php?message=not_found");
        exit();
    }

    // Consulta para excluir o funcionário do banco de dados
    $sql_delete = "DELETE FROM funcionarios WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        // Redireciona para a página de listagem de funcionários com uma mensagem de sucesso
        header("Location: usuario.php?message=success");
        exit();
    } else {
        // Redireciona com uma mensagem de erro
        header("Location: usuario.php?message=error");
        exit();
    }

    // Fecha o statement
    $stmt_delete->close();
    $stmt_check->close();
}

// Fecha a conexão
$conn->close();
?>
