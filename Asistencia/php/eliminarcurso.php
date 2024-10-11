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
if (isset($_POST["btneiminarcurso"])) {
    if (!empty($_POST["id-eliminar-curso"])) {
        $id_curso = $_POST["id-eliminar-curso"];

        // Aquí deberías agregar código para eliminar los datos en la base de datos

        $stmt = $conn->prepare("DELETE FROM cursos WHERE id_curso = ?");
        $stmt->bind_param("s", $id_curso );
        $stmt->execute();

        // Verifica si la eliminación fue exitosa
        if ($stmt->affected_rows > 0) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminación Exitosa</h4>
                        <button class="close" onclick="location.href='../menu/cursos.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "El curso fue eliminado exitosamente.";
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" onclick="location.href='../menu/cursos.html'">Cerrar</button>
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
                        <button class="close" onclick="location.href='../menu/cursos.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "Hubo un error al eliminar. El curso no se encuentra registrado";
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" onclick="location.href='../menu/cursos.html'">Cerrar</button>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>