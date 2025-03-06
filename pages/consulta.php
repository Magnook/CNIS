<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'operador') {
    die("Acesso negado.");
}

$resultado = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = isset($_POST['cpf']) ? trim($_POST['cpf']) : '';
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $data_nascimento = isset($_POST['data_nascimento']) ? trim($_POST['data_nascimento']) : '';

    // Construindo a query dinamicamente
    $query = "SELECT nome_completo, cpf, data_nascimento FROM pessoas WHERE 1=1";
    $params = [];

    if (!empty($cpf)) {
        $query .= " AND cpf = ?";
        $params[] = $cpf;
    }
    if (!empty($nome)) {
        $query .= " AND nome_completo LIKE ?";
        $params[] = "%$nome%";
    }
    if (!empty($data_nascimento)) {
        $query .= " AND data_nascimento = ?";
        $params[] = $data_nascimento;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Pessoas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="consulta-container">
        <h2>Consulta de Pessoas no CNIS</h2>
        <form method="POST">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" maxlength="11">

            <a href="#" id="mostrar-pesquisa-avancada">
                <i class="fas fa-search"></i> Pesquisa Avançada
            </a>

            <div id="pesquisa-avancada" style="display: none;">
                <label for="nome">Nome Completo:</label>
                <input type="text" name="nome" id="nome">

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" name="data_nascimento" id="data_nascimento">
            </div>

            <button type="submit">Pesquisar</button>
        </form>

        <?php if (!empty($resultado)) : ?>
            <h3>Resultados:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Data de Nascimento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado as $pessoa) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pessoa['nome_completo']); ?></td>
                            <td><?php echo htmlspecialchars($pessoa['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($pessoa['data_nascimento']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="painel_operador.php" class="back-button">Voltar</a>
    </div>

    <script>
        document.getElementById('mostrar-pesquisa-avancada').addEventListener('click', function(event) {
            event.preventDefault();
            var pesquisaAvancada = document.getElementById('pesquisa-avancada');
            if (pesquisaAvancada.style.display === 'none') {
                pesquisaAvancada.style.display = 'block';
            } else {
                pesquisaAvancada.style.display = 'none';
            }
        });
    </script>
</body>

</html>