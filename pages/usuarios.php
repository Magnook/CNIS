<?php
session_start();
require '../conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'administrador') {
    die("Acesso negado.");
}

// Consulta para obter usuários
$stmt = $pdo->query("SELECT id, nome, email, tipo FROM usuarios ORDER BY nome ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários Cadastrados</title>
</head>
<body>
    <h2>Lista de Usuários</h2>
    <table border="1">
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= htmlspecialchars($usuario['tipo']) ?></td>
                <td>
                    <?php if ($usuario['id'] !== $_SESSION['usuario_id']): ?>
                        <a href="excluir_usuario.php?id=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                    <?php else: ?>
                        (Você)
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="painel.php">Voltar ao Painel</a>
</body>
</html>
