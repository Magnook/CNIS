<?php
session_start();

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'operador') {
    die("Acesso negado.");
}

require '../conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Operador</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="panel-container">
        <h2>Bem-vindo, <?= $_SESSION['usuario_nome']; ?>!</h2>
        <p>Este é o seu painel de operador.</p>

        <a href="consulta.php" class="panel-link">Consultar Usuários</a>
        <a href="cadastro_pessoas.php" class="panel-link">Cadastro de Pessoas</a>
        <a href="../logout.php" class="panel-link">Sair</a>
    </div>
</body>

</html>