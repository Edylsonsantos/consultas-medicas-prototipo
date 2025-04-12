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

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];  // Obtém a senha

    // Valida o email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: usuario.php?status=invalid_email");
        exit();
    }

    // Verifica se o email já existe no banco de dados
    $sql_check_email = "SELECT id FROM funcionarios WHERE email = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);

    // Verifica se o prepare foi bem-sucedido
    if ($stmt_check_email === false) {
        die('Erro ao preparar a consulta: ' . $conn->error);
    }

    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows > 0) {
        // O email já existe
        header("Location: usuario.php?status=email_exists");
        exit();
    }

    // Gera o hash da senha
    $hashed_password = password_hash($senha, PASSWORD_BCRYPT);

    // Consulta para inserir os dados no banco de dados
    $sql = "INSERT INTO funcionarios (nome, cargo, email, telefone, senha) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verifica se o prepare foi bem-sucedido
    if ($stmt === false) {
        die('Erro ao preparar a consulta: ' . $conn->error);
    }

    $stmt->bind_param("sssss", $nome, $cargo, $email, $telefone, $hashed_password);

    // Verifica se o cadastro foi bem-sucedido
    if ($stmt->execute()) {
        // Redireciona para a página de visualização dos funcionários com uma mensagem de sucesso
        header("Location: usuario.php?status=success");
        exit();
    } else {
        // Exibe o erro real
        header("Location: usuario.php?status=error&error=" . urlencode($stmt->error));
        exit();
    }

    // Fecha o statement
    $stmt->close();
    $stmt_check_email->close();
}

// Fecha a conexão
$conn->close();
?>
