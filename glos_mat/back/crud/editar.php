<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "math_gloss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o ID foi passado e tenta buscar o termo no banco
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM termos WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p class='alert alert-danger'>Termo não encontrado.</p>";
        exit;
    }
} else {
    echo "<p class='alert alert-danger'>ID do termo não fornecido.</p>";
    exit;
}

// Lógica para atualizar o termo no banco de dados
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $termo = $_POST['termo'];
    $definicao = $_POST['definicao'];

    if (!empty($termo) && !empty($definicao)) {
        // Consulta SQL para atualizar o termo
        $sql = "UPDATE termos SET termo=?, definicao=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $termo, $definicao, $id);

        if ($stmt->execute()) {
            echo "<p class='alert alert-success'>Termo atualizado com sucesso!</p>";
            header("Location: ../index.php");
            exit;
        } else {
            echo "<p class='alert alert-danger'>Erro ao atualizar: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='alert alert-warning'>Por favor, preencha todos os campos.</p>";
    }
}

$conn->close();
?>
<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: ../login.php");
    exit();
}

// Se estiver logado, exibe o conteúdo do backend
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Termo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
</head>
<body>
<div class="container mt-4">
    <h1><a href="../index.php" class="btn-voltar"><i class="bi bi-arrow-left"></i></a>Editar Termo</h1>

    <form method="POST" action="editar.php?id=<?php echo $id; ?>">
        <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
        <div class="mb-3">
            <label for="termo" class="form-label">Termo</label>
            <input type="text" name="termo" class="form-control" value="<?php echo isset($row['termo']) ? $row['termo'] : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="definicao" class="form-label">Definição</label>
            <textarea name="definicao" class="form-control" required><?php echo isset($row['definicao']) ? $row['definicao'] : ''; ?></textarea>
        </div>
        <button type="submit" name="editar" class="btn btn-primary">Salvar</button>
    </form>
</div>
</body>
</html>
