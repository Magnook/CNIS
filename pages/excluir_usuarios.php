<?php
session_start();
require '../conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'administrador') {
    die("Acesso negado.");
}

// Obtém ID do usuário a excluir
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Evita que um admin exclua a si mesmo
    if ($id == $_SESSION['usuario_id']) {
        die("Você não pode excluir sua própria conta.");
    }

    // Exclui usuário
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    if ($stmt->execute([$id])) {
        header("Location: usuarios.php");
        exit();
    } else {
        echo "Erro ao excluir usuário.";
    }
} else {
    echo "ID inválido.";
}
?>
