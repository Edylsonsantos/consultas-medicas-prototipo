<?php
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

// Mensagens de status
$message = "";
$style = "";

// Lógica para exibir médicos
$sql_medicos = "SELECT * FROM medicos";
$result_medicos = $conn->query($sql_medicos);
// Verifica se a URL contém o parâmetro 'editar' para exibir a mensagem
if (isset($_GET['editar'])) {
    if ($_GET['editar'] == 'success') {
        $message = "Médico atualizado com sucesso!";
        $style = "success";
    } elseif ($_GET['editar'] == 'error') {
        $message = "Nenhum campo foi alterado ou houve um erro na atualização. Tente novamente!";
        $style = "error";
    }
}
// Fecha a conexão
$conn->close();
?>
<?php
    if (isset($_GET['status'])) {
        $style = $_GET['status'];
        $message = "Excluido";
    } else {
        $message = '';
        $style = '';
    }
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Médicos</title>
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
        <div id="messageBox" class="message-box" style="display: block;">
                <i class='bx bx-bell <?php echo $style; ?>'></i><p class="<?php echo $style; ?>"><?php echo $message; ?></p>
            </div>
            <a href="registro-medico.php" target="_blank" rel="noopener noreferrer" class="novo">Registrar medico</a>

            <?php if ($result_medicos && $result_medicos->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Nome</th>
                        <th>CRM</th>
                        <th>Especialidade</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Função</th>
                    </tr>
                    <?php while ($row = $result_medicos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo $row['crm']; ?></td>
                            <td><?php echo $row['especialidade']; ?></td>
                            <td><?php echo $row['telefone']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['status_medico']; ?></td>
                            <td style="display:flex; align-items: center; justify-content: space-beetwen; width:100%">
                                <a href="editar-medico.php?id=<?php echo $row['id']; ?>" title="Editar">
                                    <i class="bx bx-edit"></i> <!-- Ícone de editar -->
                                </a>
                                <a href="excluir-medico.php?id=<?php echo $row['id']; ?>" title="">
                                    <i class="bx bx-trash"></i> <!-- Ícone de editar -->
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="warning-td">Nenhum médico registrado. <i class='bx bxl-dropbox'></i></p>
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
