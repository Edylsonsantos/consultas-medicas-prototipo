
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
    <title>Cadastro de Consulta Médica</title>
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
            <form id="medicalForm" action="processar_consulta.php" method="POST">
                <!-- Informações do Paciente -->
                <h2>Informações do Paciente</h2>
                <label for="nome_paciente">Nome do Paciente:</label>
                <input type="text" id="nome_paciente" name="nome_paciente" required>

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required>

                <label for="genero">Gênero:</label>
                <select id="genero" name="genero" required>
                    <option value="">Selecione</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                </select>

                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" required>

                <!-- Documento de Identificação -->
                <label for="tipo_documento">Tipo de Documento:</label>
                <select id="tipo_documento" name="tipo_documento" onchange="mostrarCampoDocumento()" required>
                    <option value="">Selecione</option>
                    <option value="BI">BI</option>
                    <option value="Cedula">Cédula</option>
                    <option value="Cartao_Eleitor">Cartão de Eleitor</option>
                    <option value="Passaporte">Passaporte</option>
                </select>

                <div id="campo_numero_documento" style="display: none;">
                    <label for="numero_documento">Número do Documento:</label>
                    <input type="text" id="numero_documento" name="numero_documento">
                </div>

                <!-- Informações da Consulta -->
                <h2>Informações da Consulta</h2>
                <label for="data_consulta">Data da Consulta:</label>
                <input type="date" id="data_consulta" name="data_consulta" required>

                <label for="hora_consulta">Hora da Consulta:</label>
                <input type="time" id="hora_consulta" name="hora_consulta" required>

                <label for="especialidade">Especialidade Médica:</label>
                <select id="especialidade" name="especialidade" required>
                    <option value="">Selecione</option>
                    <option value="Clínico Geral">Clínico Geral</option>
                    <option value="Pediatria">Pediatria</option>
                    <option value="Ginecologia">Ginecologia</option>
                    <option value="Cardiologia">Cardiologia</option>
                </select>

                <label for="medico">Médico Responsável:</label>
                <select id="medico" name="medico" required>
                    <option value="">Selecione</option>
                    <?php  
                        foreach ($result as $fun) {
                            echo '<option value="'.$fun['nome'].'">'.$fun['nome'].'</option>';
                        }
                    ?>
                </select>

                <label for="motivo_consulta">Motivo da Consulta:</label>
                <textarea id="motivo_consulta" name="motivo_consulta" rows="4" required></textarea>

                <input type="submit" value="Registrar Consulta">
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
