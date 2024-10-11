<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>

<?php
// Establecer la conexión con la base de datos
require '../conexion/conexion.php';

// Variable para determinar si se mostrará la ventana modal
$mostrarModal = false;

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el tipo de asistencia (primaria o secundaria)
    $claseFormulario = $_POST['tipo_asistencia'];

    // Verificar el tipo de asistencia y guardar en la tabla correspondiente
    if ($claseFormulario === 'primaria') {
        // Recuperar los datos del formulario para la asistencia primaria
        $fecha_actual = date("Y-m-d");
        $asistencia = isset($_POST['asistenciap']) ? $_POST['asistenciap'] : array();
        $id_curso = isset($_POST['id_cursop']) ? $_POST['id_cursop'] : array();
        $id_docente = isset($_POST['docentep']) ? $_POST['docentep'] : ''; // El id_docente debe ser el mismo para todos los estudiantes

        // Preparar la consulta SQL utilizando consultas preparadas
        $query = "INSERT INTO asistencia (id_estudiante, fecha_actual, asistencia_estudiante, id_curso, id_docente) VALUES (?, ?, ?, ?, ?)";

        // Preparar la declaración
        $stmt = $conn->prepare($query);
        if ($stmt) {
            // Vincular parámetros e insertar datos en la tabla
            foreach ($_POST['id_estudiantep'] as $key => $id_estudiante) {
                // Verificar si se enviaron datos para este estudiante
                if (!empty($asistencia[$id_estudiante]) && !empty($id_curso[$key])) {
                    $asistencia_estudiante = $asistencia[$id_estudiante];
                    $id_curso_estudiante = $id_curso[$key];
                    $id_docente = isset($_POST['docentep']) ? $_POST['docentep'] : '';

                    // Vincular parámetros 
                    $stmt->bind_param("isssi", $id_estudiante, $fecha_actual, $asistencia_estudiante, $id_curso_estudiante, $id_docente);

                    // Ejecutar la consulta
                    $result = $stmt->execute();
                    if ($result) {
                        // Si la consulta se ejecuta con éxito, establecer $mostrarModal como true
                        $mostrarModal = true;
                    } else {
                        // Manejar el error si la ejecución de la consulta falla
                        $mostrarModal = false;
                        echo "Error al ejecutar la consulta SQL: " . $conn->error;
                    }
                }
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            // Manejar el error si la preparación de la consulta falla
            $mostrarModal = false;
            echo "Error al preparar la consulta SQL: " . $conn->error;
        }
    } elseif ($claseFormulario === 'secundaria') {
        // Recuperar los datos del formulario para la asistencia secundaria
        $fecha_actual = date("Y-m-d");
        $asistencia = isset($_POST['asistencias']) ? $_POST['asistencias'] : array();
        $materia = isset($_POST['id_materias']) ? $_POST['id_materias'][0] : '';
        $id_curso = isset($_POST['id_cursos']) ? $_POST['id_cursos'] : array();
        $id_docente = isset($_POST['docentes']) ? $_POST['docentes'][0] : '';

        // Preparar la consulta SQL utilizando consultas preparadas
        $query = "INSERT INTO asistencia_secundaria (id_estudiante, fecha_actual, asistencia_estudiante, id_materia, id_curso, id_docente)
                  VALUES (?, ?, ?, ?, ?, ?)";

        // Preparar la declaración
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Vincular parámetros e insertar datos en la tabla
            foreach (array_keys($_POST['id_estudiantes']) as $index) {
                $id_estudiante = $_POST['id_estudiantes'][$index];
            
                // Obtener los datos correspondientes a cada estudiante
                $asistencia_estudiante = isset($asistencia[$id_estudiante]) ? $asistencia[$id_estudiante] : '';
                $materia_estudiante = $materia;
                $id_curso_estudiante = isset($id_curso[$index]) ? $id_curso[$index] : '';
                $id_docente_estudiante = $id_docente;
            
                // Vincular parámetros
                $stmt->bind_param("issiii", $id_estudiante, $fecha_actual, $asistencia_estudiante, $materia_estudiante, $id_curso_estudiante, $id_docente_estudiante);
            
                // Ejecutar la consulta
                $result = $stmt->execute();
            
                if ($result) {
                    // Si la consulta se ejecuta con éxito, establecer $mostrarModal como true
                    $mostrarModal = true;
                }
            }            

            // Cerrar la declaración
            $stmt->close();
        } else {
            // Manejar el error si la preparación de la consulta falla
            $mostrarModal = false;
            error_log("Error al preparar la consulta SQL: " . $conn->error);
        }
    }
    // Mostrar la ventana modal si es necesario
    if ($mostrarModal) {
?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Actualización Exitosa</h4>
                    <button class="close" onclick="location.href='asistencia.php'">&times;</button>
                </div>
                <div class="modal-body">
                    <?php
                    echo "La asistencia fue registrada exitosamente.";
                    ?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="location.href='asistencia.php'">Cerrar</button>
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
                    <button class="close" onclick="location.href='asistencia.php'">&times;</button>
                </div>
                <div class="modal-body">
                    <?php
                    echo "Hubo un error al registrar la asistencia. Vuelve a intentarlo.";
                    ?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" onclick="location.href='asistencia.php'">Cerrar</button>
                </div>
            </div>
        </div>
<?php
    }
}
?>