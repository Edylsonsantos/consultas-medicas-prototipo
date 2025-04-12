
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

// Consulta para obter todos os funcionários
$sql = "SELECT * FROM funcionarios";
$result = $conn->query($sql);

// Verifica se foi passado o status na URL (success ou error)
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Fecha a conexão com o banco
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Funcionários</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="../">Início</a></li>
                <li><a href="view-consultas.php">Consultas</a></li>
                <li><a href="view-medicos.php">Medicos</a></li>
                <li><a href="usuario.php">Administrador</a></li>
                <li><a href="atendido.php">Consultas finalizadas</a></li>
                <li><a href="pendente.php">Consultas pendentes</a></li>
                <li><a href="../logout.php">Sair</a></li>
            </ul>
        </div>

        <div class="content">
            <!-- Exibe a mensagem de sucesso ou erro -->
            <div id="messageBox" class="message-box" style="display: block;">
                <?php
                    if (isset($_GET['status'])) {
                        $status = $_GET['status'];
                        if ($status == 'success') {
                            echo '<p class="success">Usuario registado com sucesso!</p>';
                        } elseif ($status == 'error') {
                            echo '<p class="error">Ocorreu um erro ao registrar usuario.</p>';
                        } elseif ($status == 'invalid_email') {
                            echo '<p class="warning">Por favor, insira um email válido.</p>';
                        } elseif ($status == 'email_exists') {
                            echo '<p  class="warning">Este email já está registado. Por favor, use outro.</p>';
                        }
                        if (isset($_GET['error'])) {
                            echo '<p  class="error">Erro: ' . htmlspecialchars($_GET['error']) . '</p>';
                        }
                    }
                ?>
                <?php
                    // Exibe mensagens de status, se houver
                    if (isset($_GET['message'])) {
                        if ($_GET['message'] == 'success') {
                            echo '<p class="success">Usuario excluído com sucesso!</p>';
                        } elseif ($_GET['message'] == 'error') {
                            echo '<p class="error">Erro ao excluir o usuario.</p>';
                        } elseif ($_GET['message'] == 'not_found') {
                            echo '<p class="warning">Usuario não encontrado.</p>';
                        }
                    }
                ?>
            </div>
            <!-- Exibe a tabela com os funcionários -->
            
            <a href="registrar-usuario.php" target="_blank" rel="noopener noreferrer" class="novo">Adicionar novo usuario</a>
            <h3>Usuários:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Apagar</th>
                </tr>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo $row['cargo']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['telefone']; ?></td>
                            <td>
                                <a href="excluir-usuario.php?id=<?php echo $row['id']; ?>">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <td><p class="warning-td">Nenhum usuario encontrado <i class='bx bxl-dropbox'></i></p></td>
                <?php endif; ?>
            </table>
        </div>

        <div class="sidebar sidebar-right">
            <h1>Pesquisar</h1>
            <form action="view-consultas.php" method="GET">
                <input type="text" id="numero_documento" name="numero_documento" placeholder="Pesquisar pelo numero da consulta..." required>
                <button type="submit">Pesquisar</button>
            </form><br><br>

            <h1 style="margin-top: 15px">Calendário</h1>
            <div class="calendar">
                <div class="calendar-header">
                    <button id="prevMonth">&lt;</button>
                    <span id="monthYear"></span>
                    <button id="nextMonth">&gt;</button>
                </div>
                <div class="calendar-days">
                    <div>Dom</div>
                    <div>Seg</div>
                    <div>Ter</div>
                    <div>Qua</div>
                    <div>Qui</div>
                    <div>Sex</div>
                    <div>Sáb</div>
                </div>
                <div class="calendar-grid" id="calendarGrid"></div>
            </div>
        </div>
    </div>
    <script>
        // Função para esconder a message-box após 10 segundos
        setTimeout(function() {
            const messageBox = document.querySelector('.message-box');
            if (messageBox) {
                messageBox.style.display = 'none';
            }
            
            // Limpa o parâmetro da URL após 10 segundos
            const currentUrl = window.location.href;
            const newUrl = currentUrl.split('?')[0];  // Remove qualquer query string
            window.history.replaceState({}, document.title, newUrl); // Atualiza a URL sem recarregar a página
        }, 10000); // 10000ms = 10 segundos
    </script>
<script src="../script.js"></script>
</body>
</html>
