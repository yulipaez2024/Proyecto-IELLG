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
if (isset($_POST["btnactualizar"])) {
    if (!empty($_POST["id-agregar"]) && !empty($_POST["nombre-estudiante"]) && !empty($_POST["curso-agregar"])) {
        $id_estudiante = $_POST["id-agregar"];
        $nuevo_nombre = $_POST["nombre-estudiante"];
        $nuevo_curso = $_POST["curso-agregar"];

        // Aquí deberías agregar código para realizar la actualización en la base de datos

        $stmt = $conn->prepare("UPDATE estudiantes SET nombre_estudiante = ?, curso_estudiante = ? WHERE id_estudiante = ?");
        $stmt->bind_param("sss", $nuevo_nombre, $nuevo_curso, $id_estudiante);
        $stmt->execute();

        // Verifica si la actualización fue exitosa
        if ($stmt->affected_rows > 0) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Actualización Exitosa</h4>
                        <button class="close" onclick="location.href='../menu/estudiantes.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "El estudiante fue actualizado exitosamente.";
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
                        echo "Hubo un error al actualizar el estudiante. Debes registrar al estudiante primero.";
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
