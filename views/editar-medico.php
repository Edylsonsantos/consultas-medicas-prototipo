<?php
// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Configurações de charset para caracteres especiais
$conn->set_charset("utf8");

// Verificar se o ID do médico foi passado na URL
if (isset($_GET['id'])) {
    $id_medico = $_GET['id'];

    // Consultar os dados do médico
    $sql = "SELECT * FROM medicos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_medico);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o médico foi encontrado
    if ($result->num_rows > 0) {
        $medico = $result->fetch_assoc();
    } else {
        echo "Médico não encontrado!";
        exit();
    }

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recuperando os dados do formulário
        $nome = $_POST['nome'];
        $especialidade = $_POST['especialidade'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $endereco = $_POST['endereco'];
        $status = $_POST['status'];

        // Array para campos alterados
        $fields = [];
        $params = [];

        // Verifica se os campos foram modificados
        if ($nome !== $medico['nome']) {
            $fields[] = "nome = ?";
            $params[] = $nome;
        }
        if ($especialidade !== $medico['especialidade']) {
            $fields[] = "especialidade = ?";
            $params[] = $especialidade;
        }
        if ($telefone !== $medico['telefone']) {
            $fields[] = "telefone = ?";
            $params[] = $telefone;
        }
        if ($email !== $medico['email']) {
            $fields[] = "email = ?";
            $params[] = $email;
        }
        if ($endereco !== $medico['endereco']) {
            $fields[] = "endereco = ?";
            $params[] = $endereco;
        }
        if ($status !== $medico['status_medico']) {
            $fields[] = "status_medico = ?";
            $params[] = $status;
        }
        // Atualizar os dados caso haja alterações
        if (count($fields) > 0) {
            $update_sql = "UPDATE medicos SET " . implode(", ", $fields) . " WHERE id = ?";
            $params[] = $id_medico;

            $update_stmt = $conn->prepare($update_sql);
            $types = str_repeat("s", count($params) - 1) . "i";
            $update_stmt->bind_param($types, ...$params);
            $update_stmt->execute();
            $update_stmt->close();

            header("Location: view-medicos.php?editar=success");
            exit();
        } else {
            header("Location: view-medicos.php?editar=error");
            exit();
        }
    }
} else {
    echo "ID de médico não fornecido!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Consulta Médica</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        // Função para mostrar/esconder o campo de número do documento
        function mostrarCampoDocumento() {
            const tipoDocumento = document.getElementById('tipo_documento').value;
            const campoNumero = document.getElementById('campo_numero_documento');
            
            if (tipoDocumento) {
                campoNumero.style.display = 'block';
            } else {
                campoNumero.style.display = 'none';
            }
        }
    </script>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="container container-ms">
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
            <form id="editDoctorForm" action="editar-medico.php?id=<?php echo $medico['id']; ?>" method="POST">
                <h2>Editar Médico</h2>
                
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $medico['nome']; ?>" required>
                
                <label for="especialidade">Especialidade:</label>
                <select id="especialidade" name="especialidade" required>
                    <option value="">Selecione uma especialidade</option>
                    <option value="Cardiologia" <?php echo $medico['especialidade'] === 'Cardiologia' ? 'selected' : ''; ?>>Cardiologia</option>
                    <option value="Dermatologia" <?php echo $medico['especialidade'] === 'Dermatologia' ? 'selected' : ''; ?>>Dermatologia</option>
                    <option value="Pediatria" <?php echo $medico['especialidade'] === 'Pediatria' ? 'selected' : ''; ?>>Pediatria</option>
                    <option value="Ortopedia" <?php echo $medico['especialidade'] === 'Ortopedia' ? 'selected' : ''; ?>>Ortopedia</option>
                    <option value="Ginecologia" <?php echo $medico['especialidade'] === 'Ginecologia' ? 'selected' : ''; ?>>Ginecologia</option>
                </select>
                
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" value="<?php echo $medico['telefone']; ?>" required>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo $medico['email']; ?>" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?php echo $medico['endereco']; ?>" required>

                <label for="status">Estado do medico:</label>
                <select id="status" name="status" required>
                    <option value="">Selecione um estado</option>
                    <option value="Ausente" <?php echo $medico['status_medico'] === 'Ausente' ? 'selected' : ''; ?>>Ausente</option>
                    <option value="Trabalhando" <?php echo $medico['status_medico'] === 'Trabalhando' ? 'selected' : ''; ?>>Trabalhando</option>
                    <option value="Suspenso" <?php echo $medico['status_medico'] === 'Suspenso' ? 'selected' : ''; ?>>Suspenso</option>
                </select>
                <input type="submit" value="Atualizar médico">
            </form>

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
    <script src="../script.js"></script>
</body>
</html>
