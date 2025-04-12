<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";  // Nome da base de dados para consultas

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$message = ""; // Variável para armazenar a mensagem de status
$style = ""; // Variável para armazenar o estilo da mensagem

// Lógica para filtrar as consultas com base no número de documento, se fornecido
$consulta_result = null;

// Verificar se o parâmetro 'numero_documento' foi passado na URL
$numero_documento = isset($_GET['numero_documento']) ? $_GET['numero_documento'] : null;

// Se o número de documento for fornecido, filtra as consultas por esse parâmetro
if ($numero_documento) {
    $sql_consulta = "SELECT * FROM consultas WHERE numero_documento = ?";  // Altere 'numero_documento' para o nome real do campo
    $stmt_consulta = $conn->prepare($sql_consulta);
    $stmt_consulta->bind_param("s", $numero_documento);  // 's' significa string
} else {
    // Caso contrário, retorne todas as consultas
    $sql_consulta = "SELECT * FROM consultas WHERE status_atendimento = 'nao_atendido'";
    $stmt_consulta = $conn->prepare($sql_consulta);
}

$stmt_consulta->execute();
$consulta_result = $stmt_consulta->get_result();

$stmt_consulta->close();

// Lógica para atualizar o status da consulta
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id_consulta = $_GET['id'];
    $status = $_GET['status'];

    // Atualizando o status da consulta
    $update_sql = "UPDATE consultas SET status_atendimento = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $id_consulta);
    $update_stmt->execute();
    $update_stmt->close();

    // Mensagem de sucesso
    $message = "Consulta finalizada (Atendida)";

    // Redirecionar para a página atual para mostrar a mensagem de sucesso
    header("Location: atendido.php?style=success&message=" . urlencode($message));
    $message = "Consulta finalizada (Atendida)";
    exit();
}
// Verificar se a mensagem está presente na URL
$message = isset($_GET['message']) ? $_GET['message'] : '';
$style = isset($_GET['style']) ? $_GET['style'] : '';
// Fecha a conexão com o banco
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Consultas Médicas</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"> 
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
            <!-- Resultados da consulta -->
            <div id="messageBox" class="message-box" style="display: block;">
                <p class="<?php echo $style; ?>"><?php echo $message; ?></p>
            </div>

            <?php if ($consulta_result && $consulta_result->num_rows > 0): ?>
                <h3>Consultas médicas registradas:</h3>
                <table>
                    <tr>
                        <th>Nome do Paciente</th>
                        <th>Especialidade</th>
                        <th>Data da Consulta</th>
                        <th>Hora da Consulta</th>
                        <th>Estado</th>
                        <th>Marcar Estado</th>
                    </tr>
                    <?php while ($row = $consulta_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nome_paciente']; ?></td>
                            <td><?php echo $row['especialidade']; ?></td>
                            <td><?php echo $row['data_consulta']; ?></td>
                            <td><?php echo $row['hora_consulta']; ?></td>
                            <td><?php echo $row['status_atendimento'] == 'atendido' ? 'Atendido' : 'Não Atendido'; ?></td>
                            <td>
                                <!-- Botões para alterar o status -->
                                <a href="?id=<?php echo $row['id']; ?>&status=atendido" class="btn-success" title="Marcar como Atendido">Marcar como atendido</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php elseif ($consulta_result): ?>
                <p class="warning-td">Nenhuma consulta pendente. <i class='bx bxl-dropbox'></i></p>
                
            <?php endif; ?>
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
