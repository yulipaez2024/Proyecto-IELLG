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
    if (isset($_POST["btnasistencia"])) {
        if (!empty($_POST["id-asistencia"]) && !empty($_POST["nombre-asistencia"]) && !empty($_POST["fecha"]) && !empty($_POST["curso-asistencia"]) && isset($_POST["asistencia"])) {
            $id_estudiante = $_POST["id-asistencia"];
            $nombre_estudiante = $_POST["nombre-asistencia"];
            $fecha = $_POST["fecha"];
            $nivel_educativo = $_POST["curso-asistencia"];
            $asistencia = $_POST["asistencia"];

            // Verificar si el ID del estudiante existe en la tabla estudiantes
            $stmt_verificar_estudiante = $conn->prepare("SELECT id_estudiante FROM estudiantes WHERE id_estudiante = ?");
            $stmt_verificar_estudiante->bind_param("s", $id_estudiante);
            $stmt_verificar_estudiante->execute();
            $result_verificar_estudiante = $stmt_verificar_estudiante->get_result();

            // Verificar si el nivel educativo existe en la tabla cursos
            $stmt_verificar_curso = $conn->prepare("SELECT id_curso FROM cursos WHERE id_curso = ?");
            $stmt_verificar_curso->bind_param("s", $nivel_educativo);
            $stmt_verificar_curso->execute();
            $result_verificar_curso = $stmt_verificar_curso->get_result();

            if ($result_verificar_estudiante->num_rows > 0 && $result_verificar_curso->num_rows > 0) {
                // Ambas verificaciones pasaron, proceder con la inserción de asistencia
                $stmt = $conn->prepare("INSERT INTO asistencia (id_estudiante, nombre_completo, fecha_actual, nivel_estudiante, asistencia_estudiante) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $id_estudiante, $nombre_estudiante, $fecha, $nivel_educativo, $asistencia);
                $stmt->execute();

                // Verifica si la inserción fue exitosa
                if ($stmt->affected_rows > 0) {
    ?>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Registro Exitoso</h4>
                                <button class="close" onclick="location.href='../menu/asistencia.html'">&times;</button>
                            </div>
                            <div class="modal-body">
                                <?php
                                echo "La asistencia del estudiante fue registrada exitosamente.";
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success" onclick="location.href='../menu/asistencia.html'">Cerrar</button>
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
                                <button class="close" onclick="location.href='../menu/asistencia.html'">&times;</button>
                            </div>
                            <div class="modal-body">
                                <?php
                                echo "Hubo un error al registrar la asistencia. Inténtelo de nuevo.";
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" onclick="location.href='../menu/asistencia.html'">Cerrar</button>
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
                            <button class="close" onclick="location.href='../menu/asistencia.html'">&times;</button>
                        </div>
                        <div class="modal-body">
                            <?php
                            echo "Hubo un error el estudiante u curso no es encuentra registrado.";
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" onclick="location.href='../menu/asistencia.html'">Cerrar</button>
                        </div>
                    </div>
                </div>
    <?php
            }
        }
    }
    ?>