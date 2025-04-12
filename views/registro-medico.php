<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Médico</title>
    <link rel="stylesheet" href="../styles.css">
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
            <h2>Cadastro de Médico</h2>
            <form id="doctorForm" action="processar_medico.php" method="POST">
                <!-- Informações do Médico -->
                <label for="nome_medico">Nome do Médico:</label>
                <input type="text" id="nome_medico" name="nome_medico" required>

                <label for="crm">CRM (Registro Profissional):</label>
                <input type="text" id="crm" name="crm" required>

                <label for="especialidade">Especialidade:</label>
                <select id="especialidade" name="especialidade" required>
                    <option value="">Selecione</option>
                    <option value="Clínico Geral">Clínico Geral</option>
                    <option value="Pediatria">Pediatria</option>
                    <option value="Ginecologia">Ginecologia</option>
                    <option value="Cardiologia">Cardiologia</option>
                </select>

                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" required>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" required>

                <input type="submit" value="Registrar Médico">
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
