<?php
session_start();
require 'conexao.php'; // Arquivo para conexão com o banco

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (!empty($email) && !empty($senha)) {
        $stmt = $pdo->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // Redirecionamento baseado no tipo de usuário
            if ($usuario['tipo'] === 'administrador') {
                header("Location: ../CNIS/pages/painel.php");
            } else {
                header("Location: ../CNIS/pages/painel_operador.php");
            }
            exit();
        }
    } else {
        echo "<p style='color:red;'>E-mail ou senha inválidos.</p>";
    }
} else {
    echo "<p style='color:red;'>Preencha todos os campos.</p>";
}
?>

<!-- login.html -->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>

</html>