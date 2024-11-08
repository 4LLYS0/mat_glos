<?php
include '../db/conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM termos WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='alert alert-success'>Termo excluído com sucesso!</p>";
    } else {
        echo "<p class='alert alert-danger'>Erro: " . $conn->error . "</p>";
    }
}
header("Location: ../index.php");
?>
