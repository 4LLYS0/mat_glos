<?php include 'db/conexao.php'; ?>
<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Matglossary - ADMIN </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-dark shadow-sm">
<div class="container-fluid">
    <a class="navbar-brand" style="color: white;" href="#">Dashboard Matglossary</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul> 
      <a href="../front/index.php">
    <button class="btn btn-sm btn-primary" style="margin:5px;" type="button">Ir para o site</button>
</a>
<a href="logout.php">
    <button class="btn btn-sm btn-warning" type="button"><i class="bi bi-box-arrow-right"></i></button>
</a>

    </div>
  </div>
    </nav>
<div class="container mt-4">
<?php echo "<h1>Bem-vindo, " . $_SESSION['usuario_nome'] . "</h1>"; ?>        

    <form method="POST" action="index.php" class="my-3">
    <div class="input-group mb-3">
    <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar termo..." aria-label="Pesquisar termo">
    <button class="btn btn-primary" type="submit">Pesquisar</button>
</div>

    </form>
    <a href="../back/crud/adicionar.php" class="btn btn-success">Adicionar Novo Termo</a>

    <?php
    $sql = "SELECT * FROM termos";
    if (isset($_POST['pesquisa'])) {
        $pesquisa = $_POST['pesquisa'];
        $sql .= " WHERE termo LIKE '%$pesquisa%'";
    }
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card my-3'>";
            echo "<div class='card-body'>";
            echo "<div class='d-flex justify-content-between align-items-center'>";
            echo "<h2 class='card-title'>{$row['termo']}</h2>";
            echo "<div>";
            echo "<a href='CRUD/editar.php?id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a> ";
            echo "<a href='CRUD/excluir.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Deseja excluir este termo?')\">Excluir</a>";
            echo "</div>";
            echo "</div>";
            echo "<p class='card-text'>{$row['definicao']}</p>";
            
            // Exibir a imagem, se houver
            if (!empty($row['imagem'])) {
                echo "<img src='{$row['imagem']}' alt='{$row['termo']}' class='img-fluid' style='max-height: 200px;'>";
            }
            echo "</div></div>";
        }
    } else {
        echo "<p class='alert alert-info'>Nenhum termo encontrado.</p>";
    }
    $conn->close();
    ?>

</div>
<footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2024 Matglossary | Todos os direitos reservados</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>


