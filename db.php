;<?php
// Ajuste se seu MySQL tiver senha (no XAMPP padrão: root sem senha)
$host = "localhost";
$user = "root";
/$pass = "";
$db = "Arquivo";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// charset para acentuação correta
mysqli_set_charset($conn, "utf8mb4");
?>