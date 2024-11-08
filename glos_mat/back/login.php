<!-- 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff8f1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        #main-login {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
       
        
    </style>
</head>
<body>
    <main id="main-login">
        <h2>Login</h2>
        <form method="POST" action="login.php" class="mb-3">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>
            <div class="mb-3">
                <input type="password" name="senha" class="form-control" placeholder="Senha" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-2">Entrar</button>
        </form>
        <p>Não tem uma conta? <a id="a-login" href="cadastro.php">Cadastre-se aqui</a></p>
        <?php if (isset($erro)) { echo "<p class='text-danger'>$erro</p>"; } ?>
    </main>
</body>
</html> -->
<?php
session_start();
include 'db/conexao.php';

$erro = '';  // Variável para armazenar mensagens de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['senha'] === $senha) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['usuario_email'] = $user['email'];
            header("Location: index.php");
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Matglossary</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="login">
    <div class="login-container">
        <h2>Login</h2>

        <!-- Exibir mensagens de erro -->
        <?php if ($erro): ?>
            <div class="error-message"><?= $erro; ?></div>
        <?php endif; ?>

        <form method="POST" id="formLogin">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="senha" required>
            </div>
            <button type="submit" class="login-button">Entrar</button>
        </form>
        
        <p>Não tem uma conta? <a id="a-login" href="cadastro.php">Cadastre-se aqui</a></p>
        <?php if (isset($erro)) { echo "<p class='text-danger'>$erro</p>"; } ?>
    </div>

</body>

</html>
