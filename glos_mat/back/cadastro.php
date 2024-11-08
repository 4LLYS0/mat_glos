<?php
include 'db/conexao.php';


// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $foto_perfil = NULL;

    // Verificação e upload da foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $foto_nome = $_FILES['foto_perfil']['name'];
        $foto_tmp = $_FILES['foto_perfil']['tmp_name'];
        $foto_destino = 'uploads/' . basename($foto_nome);

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($foto_tmp, $foto_destino)) {
            $foto_perfil = $foto_destino;
        } else {
            $erro = "Erro ao fazer upload da foto.";
        }
    }

    // Verifica se o email já existe no banco de dados
    $sql_check_email = "SELECT * FROM usuarios WHERE email = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result = $stmt_check_email->get_result();

    if ($result->num_rows > 0) {
        $erro = "Este email já está cadastrado.";
    } else {
        // Se o email não existir, insere o novo usuário no banco de dados
        $sql = "INSERT INTO usuarios (nome, email, senha, foto_perfil) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email, $senha, $foto_perfil);

        if ($stmt->execute()) {
            $sucesso = "Usuário cadastrado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar usuário: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Matglossary</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="cadastro"> 
    <div class="signup-container">
        <h2>Cadastro</h2>

        <!-- Exibição de erros ou sucesso -->
        <?php if ($erro): ?>
            <div class="error-message"><?= $erro; ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="success-message"><?= $sucesso; ?></div>
        <?php endif; ?>

        <form id="formCadastro" method="POST" action="cadastro.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="senha" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirme a Senha</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <div class="form-group">
                <label for="foto_perfil">Foto de Perfil</label>
                <input type="file" id="foto_perfil" name="foto_perfil">
            </div>
            <button type="submit" class="signup-button">Cadastrar</button>
        </form>
        <p class="login-link">Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>
