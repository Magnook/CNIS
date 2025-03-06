<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'administrador') {
    die("Acesso negado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        echo "<p style='color:red;'>Erro: E-mail já cadastrado!</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nome, $email, $senha, $tipo])) {
            echo "<p style='color:green;'>Usuário cadastrado com sucesso!</p>";
        } else {
            echo "<p style='color:red;'>Erro ao cadastrar.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="cadastro-container">
        <h2>Cadastrar Novo Usuário</h2>
        <form action="cadastro.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>

            <label for="tipo">Tipo:</label>
            <select name="tipo">
                <option value="operador">Operador</option>
                <option value="administrador">Administrador</option>
            </select>

            <button type="submit">Cadastrar</button>
        </form>
        <a href="painel.php" class="back-button">Voltar</a>
    </div>
</body>

</html>