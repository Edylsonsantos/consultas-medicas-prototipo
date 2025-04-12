<?php
session_start();

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

$message = "";
$style = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Consulta SQL
    $sql = "SELECT id, nome, senha, cargo FROM funcionarios WHERE email = ?";

    // Verifica se a consulta foi preparada corretamente
    if ($stmt = $conn->prepare($sql)) {
        // Vincula o parâmetro
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Verifica se o usuário foi encontrado
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $nome, $hashed_password, $cargo);
            $stmt->fetch();

            // Verifica a senha
            if (password_verify($senha, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_nome'] = $nome;
                $_SESSION['user_cargo'] = $cargo;

                header("Location: index.php");
                exit();
            } else {
                // Senha incorreta
                $message = "Senha incorreta!";
                $style = "error";
            }
        } else {
            // Usuário não encontrado
            $message = "Usuário não encontrado!";
            $style = "error";
        }

        // Fecha o statement
        $stmt->close();
    } else {
        // Se a consulta não puder ser preparada
        $message = "Ocorreu um erro";
        $style = "error";
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: "poppins", sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ddd;
        }
</style>

</head>
<body>

    <div class="login-container">
        <h2>REGISTRO HOSPITAL</h2>
        
        <?php if ($message): ?>
            <div class="message-login">
                <p class="<?php echo $style; ?>"style="color: red; font-family: 'poppins', sans serif"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite seu email" required>
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            </div>
            <button type="submit">Autenticar</button>
        </form>
    </div>

</body>
</html>
