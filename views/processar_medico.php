<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redireciona para login se não estiver logado
    exit();
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$message = ""; // Variável para armazenar a mensagem de status
$style = ""; // Variável para armazenar o estilo da mensagem

// Verifica se a requisição foi feita via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome_medico'];
    $crm = $_POST['crm'];
    $especialidade = $_POST['especialidade'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];

    // Verifica se o CRM ou email já existe
    $sql_check = "SELECT id FROM medicos WHERE crm = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $crm, $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Médico já registrado
        $style = "warning";
        $message = "Já existe um médico com este CRM ou e-mail.";
    } else {
        // Inserção no banco de dados
        $sql_insert = "INSERT INTO medicos (nome, crm, especialidade, telefone, email, endereco) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssss", $nome, $crm, $especialidade, $telefone, $email, $endereco);

        if ($stmt_insert->execute()) {
            $style = "success";
            $message = "Médico registrado com sucesso!";
        } else {
            $style = "error";
            $message = "Erro ao registrar médico.";
        }

        $stmt_insert->close();
    }

    $stmt_check->close();
}

$conn->close();
header("Location: view-medicos.php?style=" . urlencode($style) . "&message=" . urlencode($message));
exit();
?>
