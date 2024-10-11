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

// Recibe los datos a través del formulario utilizando el método POST
if (isset($_POST["btneliminar"])) {
    if (!empty($_POST["id-eliminar"])) {
        $id_estudiante = $_POST["id-eliminar"];

        // Aquí deberías agregar código para eliminar los datos en la base de datos

        $stmt = $conn->prepare("DELETE FROM estudiantes WHERE id_estudiante = ?");
        $stmt->bind_param("s", $id_estudiante);
        $stmt->execute();

        // Verifica si la eliminación fue exitosa
        if ($stmt->affected_rows > 0) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminación Exitosa</h4>
                        <button class="close" onclick="location.href='../menu/estudiantes.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "El estudiante fue eliminado exitosamente.";
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" onclick="location.href='../menu/estudiantes.html'">Cerrar</button>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Error</h4>
                        <button class="close" onclick="location.href='../menu/estudiantes.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "Hubo un error al eliminar. El estudiante no se encuentra registrado";
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" onclick="location.href='../menu/estudiantes.html'">Cerrar</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>
