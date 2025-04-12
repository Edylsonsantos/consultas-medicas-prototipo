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
    $nome_paciente = $_POST['nome_paciente'];
    $data_nascimento = $_POST['data_nascimento'];
    $genero = $_POST['genero'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $tipo_documento = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];
    $data_consulta = $_POST['data_consulta'];
    $hora_consulta = $_POST['hora_consulta'];
    $especialidade = $_POST['especialidade'];
    $medico_responsavel = $_POST['medico'];
    $motivo_consulta = $_POST['motivo_consulta'];

    // Verifica se a consulta já foi agendada para a mesma data e hora
    $sql_check = "SELECT id FROM consultas WHERE data_consulta = ? AND hora_consulta = ? AND medico_responsavel = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("sss", $data_consulta, $hora_consulta, $medico_responsavel);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Consulta já registrada no mesmo horário
        $style = "warning";
        $message = "Já existe uma consulta agendada para esse horário.";
    } else {
        // Consulta não agendada, prossegue com o registro
        $sql_insert = "INSERT INTO consultas (nome_paciente, data_nascimento, genero, telefone, endereco, tipo_documento, numero_documento, data_consulta, hora_consulta, especialidade, medico_responsavel, motivo_consulta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssssssssss", $nome_paciente, $data_nascimento, $genero, $telefone, $endereco, $tipo_documento, $numero_documento, $data_consulta, $hora_consulta, $especialidade, $medico_responsavel, $motivo_consulta);

        if ($stmt_insert->execute()) {
            $style = "success";
            $message = "Consulta registrada com sucesso!";
        } else {
            $style = "error";
            $message = "Erro ao registrar consulta.";
        }

        $stmt_insert->close();
    }

    $stmt_check->close();
}

$conn->close();
header("Location: view-consultas.php?style=" . urlencode($style) . "&message=" . urlencode($message));
?>
