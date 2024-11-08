<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "math_gloss"; 

// Conexão com o banco de dados utilizando MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

?>
