
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Veículos</title>
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
            <form action="save-usuario.php" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <select id="cargo" name="cargo" required>
                    <option value="">Selecione o cargo</option>
                    <option value="Administrador">IT</option>
                    <option value="Gestor">Gestor hospitalar</option>
                    <option value="Recepcionista">Recepcionista</option>
                </select>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit" class="btn">Registrar</button>
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


