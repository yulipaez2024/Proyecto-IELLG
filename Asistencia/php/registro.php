<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
<?php
error_reporting(E_ALL); // Mostrar todos los tipos de errores
ini_set('display_errors', 1); // Mostrar los errores en la página
// Establecer la conexión a la base de datos 
require('../conexion/conexion.php');

// Recibe los datos a través del formulario utilizando el método POST
if (isset($_POST["btnregistrar"])) {
    if (!empty($_POST["nombres"]) && !empty($_POST["correo"]) && !empty($_POST["password"])) {
        $nombres = $_POST["nombres"];
        $usuario = $_POST["correo"];
        $contraseña = $_POST["password"];

        // Aquí deberías agregar código para insertar los datos en la base de datos

        $stmt = $conn->prepare("INSERT INTO docentes (nombres, usuario, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombres, $usuario, $contraseña);
        $stmt->execute();

        // Verifica si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            header("location: ../inicio.html");
            exit(); // Importante: salir del script después de redirigir
        } else {
            // Mostrar mensaje de error en caso de que no se haya insertado correctamente 
?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Error</h4>
                        <button class="close" onclick="location.href='../logeo.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "Hubo un error al registrar. Inténtelo de nuevo.";
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" onclick="location.href='../logeo.html'">Cerrar</button>
                    </div>
                </div>
            </div>
<?php
        }
    }
}

?>