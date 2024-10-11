<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "iellg";
// crear conexion 
$conn = new mysqli($servername, $username, $password, $dbname);
//verifica la conexion
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>