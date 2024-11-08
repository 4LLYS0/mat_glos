<?php 
include '../db/conexao.php'; 

// Verifique se a conexão foi bem-sucedida
if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

if (isset($_POST['adicionar'])) {
    $termo = $_POST['termo'];
    $definicao = $_POST['definicao'];
    
    // Verificar se foi enviado um arquivo
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $imagem_ext = strtolower(pathinfo($imagem_nome, PATHINFO_EXTENSION));
        
        // Definir o diretório de upload e nome do arquivo
        $diretorio = 'uploads/';
        $imagem_final = uniqid() . '.' . $imagem_ext;
        $caminho_imagem = $diretorio . $imagem_final;
        
        // Mover o arquivo para o diretório de destino
        if (move_uploaded_file($imagem_tmp, $caminho_imagem)) {
            // Usando prepared statement para evitar SQL Injection
            $sql = "INSERT INTO termos (termo, definicao, imagem) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $termo, $definicao, $caminho_imagem); // "sss" significa três parâmetros do tipo string

            if ($stmt->execute()) {
                echo "<p class='alert alert-success mt-3'>Termo adicionado com sucesso!</p>";
            } else {
                echo "<p class='alert alert-danger mt-3'>Erro: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='alert alert-danger mt-3'>Erro ao fazer upload da imagem.</p>";
        }
    } else {
        // Se não for enviado nenhum arquivo
        $sql = "INSERT INTO termos (termo, definicao) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $termo, $definicao);

        if ($stmt->execute()) {
            echo "<p class='alert alert-success mt-3'>Termo adicionado com sucesso!</p>";
        } else {
            echo "<p class='alert alert-danger mt-3'>Erro: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<?php include '../db/conexao.php'; ?>
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
    <title>Adicionar Termo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
</head>
<body>
    <div class="container mt-4">
    
    <h1><a href="../index.php" class="btn-voltar"><i class="bi bi-arrow-left"></i></a>Adicionar Novo Termo</h1>
    <form method="POST" action="adicionar.php" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="termo" class="form-label">Termo</label>
        <input type="text" name="termo" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="definicao" class="form-label">Definição</label>
        <textarea name="definicao" class="form-control" required></textarea>
    </div>

    <button type="submit" name="adicionar"  class="btn btn-primary">Adicionar</button>
</form>



    </div>
</body>
</html>
