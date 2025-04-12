<?php
    // Configurações de conexão com o banco de dados
    $servername = "localhost";  // Nome do servidor
    $username = "root";         // Nome do usuário do banco de dados
    $password = "";             // Senha do banco de dados
    $dbname = "hospital";       // Nome do banco de dados

    // Criação da conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Configurações de charset para garantir que caracteres especiais sejam tratados corretamente
    $conn->set_charset("utf8");

    // Verificar se o ID da consulta foi passado na URL
    if (isset($_GET['id'])) {
        $id_consulta = $_GET['id'];

        // Consultar os dados da consulta
        $sql = "SELECT * FROM consultas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_consulta);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar se a consulta foi encontrada
        if ($result->num_rows > 0) {
            $consulta = $result->fetch_assoc();
        } else {
            echo "Consulta não encontrada!";
            exit();
        }

        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recuperando os dados do formulário
            $nome_paciente = $_POST['nome_paciente'];
            $data_nascimento = $_POST['data_nascimento'];
            $genero = $_POST['genero'];
            $telefone = $_POST['telefone'];
            $endereco = $_POST['endereco'];
            $tipo_documento = $_POST['tipo_documento'];
            $numero_documento = $_POST['numero_documento'];
            $data_consulta = $_POST['data_consulta'];
            $especialidade = $_POST['especialidade'];
            $medico = $_POST['medico'];
            $motivo_consulta = $_POST['motivo_consulta'];

            // Array para armazenar os campos que foram alterados
            $fields = [];
            $params = [];

            // Verifica se os campos foram modificados e adiciona à consulta de atualização
            if ($nome_paciente !== $consulta['nome_paciente']) {
                $fields[] = "nome_paciente = ?";
                $params[] = $nome_paciente;
            }
            if ($data_nascimento !== $consulta['data_nascimento']) {
                $fields[] = "data_nascimento = ?";
                $params[] = $data_nascimento;
            }
            if ($genero !== $consulta['genero']) {
                $fields[] = "genero = ?";
                $params[] = $genero;
            }
            if ($telefone !== $consulta['telefone']) {
                $fields[] = "telefone = ?";
                $params[] = $telefone;
            }
            if ($endereco !== $consulta['endereco']) {
                $fields[] = "endereco = ?";
                $params[] = $endereco;
            }
            if ($tipo_documento !== $consulta['tipo_documento']) {
                $fields[] = "tipo_documento = ?";
                $params[] = $tipo_documento;
            }
            if ($numero_documento !== $consulta['numero_documento']) {
                $fields[] = "numero_documento = ?";
                $params[] = $numero_documento;
            }
            if ($data_consulta !== $consulta['data_consulta']) {
                $fields[] = "data_consulta = ?";
                $params[] = $data_consulta;
            }
            if ($especialidade !== $consulta['especialidade']) {
                $fields[] = "especialidade = ?";
                $params[] = $especialidade;
            }
            if ($medico !== $consulta['medico_responsavel']) {
                $fields[] = "medico_responsavel = ?";
                $params[] = $medico;
            }
            if ($motivo_consulta !== $consulta['motivo_consulta']) {
                $fields[] = "motivo_consulta = ?";
                $params[] = $motivo_consulta;
            }

            // Se algum campo foi alterado, realiza a atualização
            if (count($fields) > 0) {
                // Cria a consulta de atualização
                $update_sql = "UPDATE consultas SET " . implode(", ", $fields) . " WHERE id = ?";
                $params[] = $id_consulta;  // Adiciona o ID da consulta para a cláusula WHERE

                // Prepara e executa a consulta de atualização
                $update_stmt = $conn->prepare($update_sql);
                $types = str_repeat("s", count($params) - 1) . "i"; // Cria o tipo dos parâmetros (string para campos, inteiro para o ID)
                $update_stmt->bind_param($types, ...$params);
                $update_stmt->execute();
                $update_stmt->close();
                
                // Após a atualização, redireciona para a página view-consultas.php com uma mensagem
                header("Location: view-consultas.php?id=$id_consulta&editar=success");
                exit();
            } else {
                // Se nenhum campo foi alterado, redireciona com mensagem de erro
                header("Location: view-consultas.php?id=$id_consulta&editar=error");
                exit();
            }
        }
    } else {
        echo "ID de consulta não fornecido!";
        exit();
    }

    // Consulta para obter todos os funcionários
    $sql = "SELECT * FROM funcionarios";
    $result = $conn->query($sql);
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
            <form id="medicalForm" action="editar-consultas.php?id=<?php echo $consulta['id']; ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $consulta['id']; ?>">

                <!-- Informações do Paciente -->
                <h2>Informações do Paciente</h2>
                <label for="nome_paciente">Nome do Paciente:</label>
                <input type="text" id="nome_paciente" name="nome_paciente" value="<?php echo $consulta['nome_paciente']; ?>" required>

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $consulta['data_nascimento']; ?>" required>

                <label for="genero">Gênero:</label>
                <select id="genero" name="genero" required>
                    <option value="Masculino" <?php echo $consulta['genero'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="Feminino" <?php echo $consulta['genero'] == 'Feminino' ? 'selected' : ''; ?>>Feminino</option>
                    <option value="Outro" <?php echo $consulta['genero'] == 'Outro' ? 'selected' : ''; ?>>Outro</option>
                </select>

                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" value="<?php echo $consulta['telefone']; ?>" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?php echo $consulta['endereco']; ?>" required>

                <!-- Documento de Identificação -->
                <label for="tipo_documento">Tipo de Documento:</label>
                <select id="tipo_documento" name="tipo_documento" onchange="mostrarCampoDocumento()" required>
                    <option value="BI" <?php echo $consulta['tipo_documento'] == 'BI' ? 'selected' : ''; ?>>BI</option>
                    <option value="Cedula" <?php echo $consulta['tipo_documento'] == 'Cedula' ? 'selected' : ''; ?>>Cédula</option>
                    <option value="Cartao_Eleitor" <?php echo $consulta['tipo_documento'] == 'Cartao_Eleitor' ? 'selected' : ''; ?>>Cartão de Eleitor</option>
                    <option value="Passaporte" <?php echo $consulta['tipo_documento'] == 'Passaporte' ? 'selected' : ''; ?>>Passaporte</option>
                </select>

                <div id="campo_numero_documento" style="display: <?php echo $consulta['numero_documento'] ? 'block' : 'none'; ?>;">
                    <label for="numero_documento">Número do Documento:</label>
                    <input type="text" id="numero_documento" name="numero_documento" value="<?php echo $consulta['numero_documento']; ?>">
                </div>

                <!-- Informações da Consulta -->
                <h2>Informações da Consulta</h2>
                <label for="data_consulta">Data da Consulta:</label>
                <input type="date" id="data_consulta" name="data_consulta" value="<?php echo $consulta['data_consulta']; ?>" required>

                <label for="especialidade">Especialidade Médica:</label>
                <select id="especialidade" name="especialidade" required>
                    <option value="Clínico Geral" <?php echo $consulta['especialidade'] == 'Clínico Geral' ? 'selected' : ''; ?>>Clínico Geral</option>
                    <option value="Pediatria" <?php echo $consulta['especialidade'] == 'Pediatria' ? 'selected' : ''; ?>>Pediatria</option>
                    <option value="Ginecologia" <?php echo $consulta['especialidade'] == 'Ginecologia' ? 'selected' : ''; ?>>Ginecologia</option>
                    <option value="Cardiologia" <?php echo $consulta['especialidade'] == 'Cardiologia' ? 'selected' : ''; ?>>Cardiologia</option>
                </select>

                <label for="medico">Médico Responsável:</label>
                <select id="medico" name="medico" required>
                    <option value="">Selecione um médico</option>
                    <?php 
                        // Supondo que $result seja o array com os dados dos médicos
                        foreach ($result as $fun) { 
                            // Verifica se o médico no loop é igual ao médico responsável na consulta
                            $selected = $fun['id'] == $consulta['medico_responsavel'] ? 'selected' : ''; 
                    ?>
                        <option value="<?php echo $fun['nome']; ?>" <?php echo $selected; ?>>
                            <?php echo $fun['nome']; ?>
                        </option>
                    <?php 
                        } 
                    ?>
                </select>


                <label for="motivo_consulta">Motivo da Consulta:</label>
                <textarea id="motivo_consulta" name="motivo_consulta" rows="4" required><?php echo $consulta['motivo_consulta']; ?></textarea>

                <input type="submit" value="Atualizar Consulta">
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
