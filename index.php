<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Veículos</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<?php include 'views/header.php'; ?>

<div class="container-i">
    <div class="sidebar">
        <ul>
            <li><a href="">Início</a></li>
            <li><a href="views/view-consultas.php">Consultas</a></li>
            <li><a href="views/view-medicos.php">Medicos</a></li>
            <li><a href="views/usuario.php">Administrador</a></li>
            <li><a href="views/atendido.php">Consultas finalizadas</a></li>
            <li><a href="views/pendente.php">Consultas pendentes</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </div>

    <div class="content">
        <?php
            // Conexão com o banco de dados
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hospital"; // Nome do seu banco de dados

            // Cria a conexão
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica a conexão
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }

            // Consultas atendidas
            $sql_consultas_atendidas = "SELECT COUNT(*) AS consultas_atendidas FROM consultas WHERE status_atendimento = 'atendida'";
            $result_consultas_atendidas = $conn->query($sql_consultas_atendidas);
            $consultas_atendidas = $result_consultas_atendidas->fetch_assoc()['consultas_atendidas'];

            // Consultas não atendidas
            $sql_consultas_nao_atendidas = "SELECT COUNT(*) AS consultas_nao_atendidas FROM consultas WHERE status_atendimento = 'nao atendida'";
            $result_consultas_nao_atendidas = $conn->query($sql_consultas_nao_atendidas);
            $consultas_nao_atendidas = $result_consultas_nao_atendidas->fetch_assoc()['consultas_nao_atendidas'];

            // Total de usuários
            $sql_usuarios = "SELECT COUNT(*) AS total_usuarios FROM funcionarios";
            $result_usuarios = $conn->query($sql_usuarios);
            $total_usuarios = $result_usuarios->fetch_assoc()['total_usuarios'];

            // Total de médicos
            $sql_medicos = "SELECT COUNT(*) AS total_medicos FROM medicos";
            $result_medicos = $conn->query($sql_medicos);
            $total_medicos = $result_medicos->fetch_assoc()['total_medicos'];

            // Médicos suspensos
            $sql_medicos_suspensos = "SELECT COUNT(*) AS medicos_suspensos FROM medicos WHERE status_medico = 'suspenso'";
            $result_medicos_suspensos = $conn->query($sql_medicos_suspensos);
            $medicos_suspensos = $result_medicos_suspensos->fetch_assoc()['medicos_suspensos'];

            // Médicos ausentes
            $sql_medicos_ausentes = "SELECT COUNT(*) AS medicos_ausentes FROM medicos WHERE status_medico = 'ausente'";
            $result_medicos_ausentes = $conn->query($sql_medicos_ausentes);
            $medicos_ausentes = $result_medicos_ausentes->fetch_assoc()['medicos_ausentes'];

            // Fecha a conexão
            $conn->close();
        ?>
        <div class="grid-container">
            <!-- Card para Consultas Atendidas -->
            <div class="info-box consultas-atendidas">
                <div class="icon"><i class='bx bx-check-circle'></i></div>
                <div>
                    <h3>Consultas Atendidas</h3>
                    <div class="value"><?php echo $consultas_atendidas; ?></div>
                </div>
            </div>

            <!-- Card para Consultas Não Atendidas -->
            <div class="info-box consultas-nao-atendidas">
                <div class="icon"><i class='bx bx-x-circle'></i></div>
                <div>
                    <h3>Consultas Não Atendidas</h3>
                    <div class="value"><?php echo $consultas_nao_atendidas; ?></div>
                </div>
            </div>

            <!-- Card para Total de Usuários -->
            <div class="info-box total-usuarios">
                <div class="icon"><i class='bx bx-user'></i></div>
                <div>
                    <h3>Total de Usuários</h3>
                    <div class="value"><?php echo $total_usuarios; ?></div>
                </div>
            </div>

            <!-- Card para Total de Médicos -->
            <div class="info-box total-medicos">
                <div class="icon"><i class='bx bx-user-voice'></i></div>
                <div>
                    <h3>Total de Médicos</h3>
                    <div class="value"><?php echo $total_medicos; ?></div>
                </div>
            </div>

            <!-- Card para Médicos Suspensos -->
            <div class="info-box medicos-suspensos">
                <div class="icon"><i class='bx bx-error-circle'></i></div>
                <div>
                    <h3>Médicos Suspensos</h3>
                    <div class="value"><?php echo $medicos_suspensos; ?></div>
                </div>
            </div>

            <!-- Card para Médicos Ausentes -->
            <div class="info-box medicos-ausentes">
                <div class="icon"><i class='bx bx-calendar-x'></i></div>
                <div>
                    <h3>Médicos Ausentes</h3>
                    <div class="value"><?php echo $medicos_ausentes; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar sidebar-right">
            <h1>Pesquisar</h1>
            <form action="../views/view-medicos.php" method="GET">
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
<script src="script.js"></script>
</body>
</html>
