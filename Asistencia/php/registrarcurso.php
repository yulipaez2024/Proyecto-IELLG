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
if (isset($_POST["btnagregarcurso"])) {
    if (!empty($_POST["id_curso"]) && !empty($_POST["nombre_curso"])) {
        $id_curso = $_POST["id_curso"];
        $nombre_curso = $_POST["nombre_curso"];

        // Aquí deberías agregar código para insertar los datos en la base de datos

        $stmt = $conn->prepare("INSERT INTO cursos (id_curso, nombre_curso) VALUES (?, ?)");
        $stmt->bind_param("is",  $id_curso ,  $nombre_curso);
        $stmt->execute();

        // Verifica si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Registro Exitoso</h4>
                        <button class="close" onclick="location.href='../menu/cursos.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "El curso fue registrado exitosamente.";
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
                        echo "Hubo un error al registrar. Inténtelo de nuevo.";
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
