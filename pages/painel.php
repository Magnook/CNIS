<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'administrador') {
    die("Acesso negado.");
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="panel-container">
        <h2>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</h2>
        <p>Seu tipo de usuário: <?php echo $_SESSION['usuario_tipo']; ?></p>

        <a href="cadastro.php" class="panel-link">Cadastrar Usuário</a>
        <a href="../logout.php" class="panel-link">Sair</a>
    </div>
</body>

</html>