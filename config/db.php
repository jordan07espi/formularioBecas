<?php
$host = "localhost";
$user = "root"; //root
$pass = ""; //
$db   = "becas";  //becas

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_errno) {
    // Para ti (guarda el error en un archivo)
    error_log("Error de conexión a la BD: " . $conn->connect_error);
    // Para el usuario
    die("Hubo un problema al conectar con el sistema. Por favor, intente más tarde.");
}
?>