<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>

<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../conexion/conexion.php');

if (isset($_POST["btncontrasena"])) {
    if (!empty($_POST["correo"]) && !empty($_POST["password"])) {
        $correo = $_POST["correo"];
        $nueva_contraseña = $_POST["password"];

        // Verificar si el correo está registrado en la base de datos
        $stmt = $conn->prepare("SELECT * FROM docentes WHERE usuario = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $hashed_password = password_hash($nueva_contraseña, PASSWORD_BCRYPT);
            // El correo está registrado, proceder a actualizar la contraseña
            $stmt = $conn->prepare("UPDATE docentes SET password = ? WHERE usuario = ?");
            $stmt->bind_param("ss", $hashed_password, $correo);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                ?>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Contraseña Modificada</h4>
                            <button class="close" onclick="location.href='../logeo.html'">&times;</button>
                        </div>
                        <div class="modal-body">
                            <?php
                            echo "Contraseña modificada exitosamente.";
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" onclick="location.href='../logeo.html'">Cerrar</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Error</h4>
                        <button class="close" onclick="location.href='../registrarme.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "El correo no está registrado en el sistema, regístrate primero.";
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" onclick="location.href='../registrarme.html'">Cerrar</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
