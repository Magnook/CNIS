<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'operador') {
    die("Acesso negado.");
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura e limpa os dados
    $nome_completo = trim($_POST['nome_completo'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $nome_mae = trim($_POST['nome_mae'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $estado_nascimento = trim($_POST['estado_nascimento'] ?? '');
    $municipio_nascimento = trim($_POST['municipio_nascimento'] ?? '');
    $rg = trim($_POST['rg'] ?? '');
    $certidao = trim($_POST['certidao'] ?? '');
    $cnh = trim($_POST['cnh'] ?? '');
    $carteira_maritima = trim($_POST['carteira_maritima'] ?? '');

    // Validação dos campos obrigatórios
    if (empty($nome_completo) || empty($cpf) || empty($nome_mae) || empty($data_nascimento)) {
        $error = "Os campos Nome Completo, CPF, Nome da Mãe e Data de Nascimento são obrigatórios.";
    } elseif (strlen(preg_replace('/[^0-9]/', '', $cpf)) != 11) {
        $error = "CPF inválido. Deve conter 11 dígitos.";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email inválido.";
    } else {
        try {
            // Verifica se o CPF já existe
            $stmt = $pdo->prepare("SELECT id FROM pessoas WHERE cpf = ?");
            $stmt->execute([$cpf]);
            if ($stmt->fetch()) {
                $error = "CPF já cadastrado.";
            } elseif (!empty($rg)) {
                $stmt = $pdo->prepare("SELECT id FROM pessoas WHERE rg = ?");
                $stmt->execute([$rg]);
                if ($stmt->fetch()) {
                    $error = "RG já cadastrado.";
                }
            }

            if (empty($error)) {
                $sql = "INSERT INTO pessoas (
                    nome_completo, cpf, nome_mae, data_nascimento, telefone, email, 
                    endereco, estado_nascimento, municipio_nascimento, rg, certidao, 
                    cnh, carteira_maritima
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);
                $params = [
                    $nome_completo,
                    $cpf,
                    $nome_mae,
                    $data_nascimento,
                    $telefone ?: null,
                    $email ?: null,
                    $endereco ?: null,
                    $estado_nascimento ?: null,
                    $municipio_nascimento ?: null,
                    $rg ?: null,
                    $certidao ?: null,
                    $cnh ?: null,
                    $carteira_maritima ?: null
                ];

                if ($stmt->execute($params)) {
                    $success = "Cadastro realizado com sucesso!";
                } else {
                    $error = "Erro ao cadastrar no banco de dados.";
                }
            }
        } catch (PDOException $e) {
            $error = "Erro: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pessoa</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="cadastro-container">
        <h2>Cadastro de Pessoa</h2>

        <?php if ($error) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST">
            <fieldset>
                <legend>Dados Pessoais</legend>

                <label for="nome_completo">Nome Completo:*</label>
                <input type="text" name="nome_completo" required>

                <label for="cpf">CPF:*</label>
                <input type="text" name="cpf" required placeholder="000.000.000-00">

                <label for="nome_mae">Nome da Mãe:*</label>
                <input type="text" name="nome_mae" required>

                <label for="data_nascimento">Data de Nascimento:*</label>
                <input type="date" name="data_nascimento" required>

                <label for="estado_nascimento">Estado de Nascimento:</label>
                <input type="text" name="estado_nascimento">

                <label for="municipio_nascimento">Município de Nascimento:</label>
                <input type="text" name="municipio_nascimento">
            </fieldset>

            <fieldset>
                <legend>Dados de Contato</legend>

                <label for="endereco">Endereço:</label>
                <textarea name="endereco"></textarea>

                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" placeholder="(00) 00000-0000">

                <label for="email">Email:</label>
                <input type="email" name="email">
            </fieldset>

            <fieldset>
                <legend>Documentos Adicionais</legend>

                <label for="rg">RG:</label>
                <input type="text" name="rg">

                <label for="certidao">Certidão:</label>
                <input type="text" name="certidao">

                <label for="cnh">CNH:</label>
                <input type="text" name="cnh">

                <label for="carteira_maritima">Carteira Marítima:</label>
                <input type="text" name="carteira_maritima">
            </fieldset>

            <button type="submit">Cadastrar</button>
        </form>
        <p>* Campos obrigatórios</p>
        <a href="painel_operador.php" class="back-button">Voltar</a>
    </div>
</body>

</html>