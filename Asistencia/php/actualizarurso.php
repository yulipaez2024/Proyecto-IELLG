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
require('../conexion/conexion.php'); // Asegúrate de incluir el archivo que contiene la conexión

if (isset($_POST["btnactualizarcurso"])) { 
    if (!empty($_POST["id-curso"]) && !empty($_POST["nombre_curso"])) {
        $id_curso = $_POST["id-curso"];
        $nuevo_nombre = $_POST["nombre_curso"];

        // Sentencia preparada para actualizar el curso
        $stmt = $conn->prepare("UPDATE cursos SET nombre_curso=? WHERE id_curso = ?");
        $stmt->bind_param("ss", $nuevo_nombre, $id_curso);
        $stmt->execute();

        // Verifica si la actualización fue exitosa
        if ($stmt->affected_rows > 0) {
            ?>
            <!-- Ventana modal para actualización exitosa -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Actualización Exitosa</h4>
                        <button class="close" onclick="location.href='../menu/cursos.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "El curso fue actualizado exitosamente.";
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
            <!-- Ventana modal para error -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Error</h4>
                        <button class="close" onclick="location.href='../menu/cursos.html'">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo "Hubo un error al actualizar el curso. Aún no está registrado.";
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" onclick="location.href='../menu/cursos.html'">Cerrar</button>
                    </div>
                </div>
            </div>
            <?php
        }

        // Cierra la sentencia preparada y la conexión
        $stmt->close();
        $conn->close();
    }
}

?>
