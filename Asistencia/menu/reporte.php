<?php
// Establecer el manejo de errores y visualización
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo de conexión a la base de datos
require '../conexion/conexion.php';

// Obtener el nivel seleccionado (si está definido)
$selectedNivel = $_POST['nivel'] ?? '';

// Inicializar la consulta SQL
$query = "";
if ($selectedNivel >= 1 && $selectedNivel <= 6) {
    $query = "SELECT a.id_estudiante, e.nombre_estudiante, a.fecha_actual, a.asistencia_estudiante, a.id_curso, a.id_docente, c.nombre_curso
              FROM asistencia a
              INNER JOIN estudiantes e ON e.id_estudiante = a.id_estudiante
              LEFT JOIN cursos c ON c.id_curso = a.id_curso";
} elseif ($selectedNivel >= 7 && $selectedNivel <= 12) {
    $query = "SELECT a.id_estudiante, e.nombre_estudiante, a.fecha_actual, a.asistencia_estudiante, a.id_materia, a.id_curso, a.id_docente, c.nombre_curso, m.nombre_materia
              FROM asistencia_secundaria a
              INNER JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
              LEFT JOIN cursos c ON c.id_curso = a.id_curso
              LEFT JOIN materias m ON m.id_materia = a.id_materia";
}



// Crear un array para almacenar las condiciones del filtro
$conditions = array();

// Agregar condiciones según el nivel
if (!empty($selectedNivel)) {
    $conditions[] = "a.id_curso = ?";
}

// Si hay condiciones en el array, agregar un WHERE a la consulta
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

try {
    $stmt = $conn->prepare($query);
    if ($stmt) {
        // Vincular parámetros según sea necesario
        $bindParams = array();
        $types = ""; // Cadena para almacenar los tipos de parámetros

        // Agregar el parámetro del nivel educativo siempre
        $types .= "s";
        $bindParams[] = &$selectedNivel;

        // Unir los parámetros en un solo array
        $params = array_merge(array($types), $bindParams);

        // Llamar a bind_param con los parámetros como argumentos variables
        call_user_func_array(array($stmt, 'bind_param'), $params);

        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        throw new Exception("Error al preparar la consulta SQL");
    }
    // Resto del código para procesar los resultados...
} catch (Exception $e) {
    echo "Error al preparar la consulta SQL: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/css.css">
    <link rel="stylesheet" href="../estilos/tablareporte.css">
    <link rel="icon" href="../../images/1.png">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Reporte</title>
</head>

<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="../../images/institucion.png" alt="">
                </span>

                <div class="text logo-text">
                    <span class="name"></span>
                    <span class="profession">IELLG</span>
                </div>
            </div>

            <i class='bx bx-menu toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Buscar...">
                </li>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="../inicio.html">
                            <i class='bx bx-grid-alt icon'></i>
                            <span class="text nav-text">Inicio</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="estudiantes.html">
                            <i class='bx bxs-graduation icon'></i>
                            <span class="text nav-text">Estudiantes</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="cursos.html">
                            <i class='bx bx-folder-open icon '></i>
                            <span class="text nav-text">Cursos</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="asistencia.php">
                            <i class='bx bx-pie-chart-alt icon'></i>
                            <span class="text nav-text">Asistencia</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a class="select" href="menu/reporte.php">
                            <i class='bx bxs-file-doc icon inicio'></i>
                            <span class="text nav-text">Reporte general</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="#" onclick="confirmLogout()">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Cerrar Sesión</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Modo oscuro</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>

            </div>
        </div>
    </nav>
    <section class="home">

        <div class="inicio">
            <h1>Reporte general de asistencia </h1>
        </div>

        <a href="imprimir.php?nivel=<?php echo $selectedNivel; ?>" class="generar-reporte">
            <button id="generar-reporte-btn">
                <i class='bx bxs-file-doc'></i> Reporte general
            </button>
        </a>


        <div class="form-container">
            <form method="post" id="filtro-niveles">
                <label for="nivel">Filtrar por nivel educativo:</label>
                <select name="nivel" id="nivel">
                    <option value="">Selecciona un nivel</option>
                    <option value="1" <?php if ($selectedNivel === '1') echo 'selected'; ?>>Transición</option>
                    <option value="2" <?php if ($selectedNivel === '2') echo 'selected'; ?>>Primero</option>
                    <option value="3" <?php if ($selectedNivel === '3') echo 'selected'; ?>>Segundo</option>
                    <option value="4" <?php if ($selectedNivel === '4') echo 'selected'; ?>>Tercero</option>
                    <option value="5" <?php if ($selectedNivel === '5') echo 'selected'; ?>>Cuarto</option>
                    <option value="6" <?php if ($selectedNivel === '6') echo 'selected'; ?>>Quinto</option>
                    <option value="7" <?php if ($selectedNivel === '7') echo 'selected'; ?>>Sexto</option>
                    <option value="8" <?php if ($selectedNivel === '8') echo 'selected'; ?>>Séptimo</option>
                    <option value="9" <?php if ($selectedNivel === '9') echo 'selected'; ?>>Octavo</option>
                    <option value="10" <?php if ($selectedNivel === '10') echo 'selected'; ?>>Noveno</option>
                    <option value="11" <?php if ($selectedNivel === '11') echo 'selected'; ?>>Décimo</option>
                    <option value="12" <?php if ($selectedNivel === '12') echo 'selected'; ?>>Undécimo</option>
                </select>
                <button type="submit">Filtrar</button>
            </form>

            <?php
            // Verificar el nivel seleccionado y mostrar la clase correspondiente
            if ($selectedNivel >= 1 && $selectedNivel <= 6) { // Mostrar para primaria
                $claseFormulario = "formulario-asistencia primaria";
            } elseif ($selectedNivel >= 7 && $selectedNivel <= 12) { // Mostrar para secundaria
                $claseFormulario = "formulario-asistencia secundaria";
            } else {
                // No se seleccionó ningún nivel, asignar una clase por defecto
                $claseFormulario = "";
            }
            ?>

        </div>

        <div class="form-table-container">
            <div class="centrado">
                <div class="<?php echo $claseFormulario; ?>">
                    <?php if ($claseFormulario === "formulario-asistencia primaria") : ?>
                        <!-- Contenido del formulario para primaria -->
                        <div class="formulario-asistencia primaria">
                            <table class="styled-table">
                                <!-- Encabezados de la tabla -->
                                <thead>
                                    <tr>
                                        <!-- Encabezados de las columnas -->
                                        <th><i class="fas fa-id-card"></i> Documento del estudiante</th>
                                        <th><i class="fas fa-user"></i> Nombre completo</th>
                                        <th><i class="far fa-calendar-alt"></i> Fecha</th>
                                        <th><i class="far fa-check-square"></i> Asistencia</th>
                                        <th><i class="far fa-calendar-alt"></i> Curso</th>
                                        <th><i class="fas fa-chalkboard-teacher"></i> Docente</th>
                                    </tr>
                                </thead>
                                <!-- Cuerpo de la tabla -->
                                <tbody>
                                    <!-- Filas de la tabla -->
                                    <?php
                                    if (isset($result) && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td><input type='hidden' name='id_estudiantep[]' value='" . $row['id_estudiante'] . "'>" . $row['id_estudiante'] . "</td>";
                                            echo "<td><input type='hidden' name='nombre_estudiantep[]' value='" . $row['nombre_estudiante'] . "'>" . $row['nombre_estudiante'] . "</td>";
                                            echo "<td><input type='hidden' name='fecha[]' value='" . $row['fecha_actual'] . "'>" . $row['fecha_actual'] . "</td>";
                                            echo "<td><input type='hidden' name='asistenciap[]' value='" . $row['asistencia_estudiante'] . "'>" . $row['asistencia_estudiante'] . "</td>";
                                            echo "</td>";
                                            echo "<td><input type='hidden' name='id_cursop[]' value='" . $row['nombre_curso'] . "'>" . $row['nombre_curso'] . "</td>";
                                            echo "<td><input type='hidden' name='id_docentep[]' value='" . $row['id_docente'] . "'>" . $row['id_docente'] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No se encontraron resultados para este nivel educativo:<br>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif ($claseFormulario === "formulario-asistencia secundaria") : ?>
                        <!-- Contenido del formulario para secundaria -->
                        <div class="formulario-asistencia secundaria">
                            <table class="styled-table">
                                <!-- Encabezados de la tabla -->
                                <thead>
                                    <tr>
                                        <!-- Encabezados de las columnas -->
                                        <th><i class="fas fa-id-card"></i> Documento del estudiante</th>
                                        <th><i class="fas fa-user"></i> Nombre completo</th>
                                        <th><i class="far fa-calendar-alt"></i> Fecha</th>
                                        <th><i class="far fa-check-square"></i> Asistencia</th>
                                        <th><i class="fas fa-book"></i> Materia</th>
                                        <th><i class="far fa-calendar-alt"></i> Curso</th>
                                        <th><i class="fas fa-chalkboard-teacher"></i> Docente</th>
                                    </tr>
                                </thead>
                                <!-- Cuerpo de la tabla -->
                                <tbody>
                                    <!-- Filas de la tabla -->
                                    <?php
                                    if (isset($result) && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td><input type='hidden' name='id_estudiantes[]' value='" . $row['id_estudiante'] . "'>" . $row['id_estudiante'] . "</td>";
                                            echo "<td><input type='hidden' name='nombre_estudiantes[]' value='" . $row['nombre_estudiante'] . "'>" . $row['nombre_estudiante'] . "</td>";
                                            echo "<td><input type='hidden' name='fecha[]' value='" . $row['fecha_actual'] . "'>" . $row['fecha_actual'] . "</td>";
                                            echo "<td><input type='hidden' name='asistencia[]' value='" . $row['asistencia_estudiante'] . "'>" . $row['asistencia_estudiante'] . "</td>";
                                            echo "<td><input type='hidden' name='materia[]' value='" . $row['nombre_materia'] . "'>" . $row['nombre_materia'] . "</td>";
                                            echo "</td>";
                                            echo "<td><input type='hidden' name='id_cursos[]' value='" . $row['nombre_curso'] . "'>" . $row['nombre_curso'] . "</td>";
                                            echo "<td><input type='hidden' name='id_docentes[]' value='" . $row['id_docente'] . "'>" . $row['id_docente'] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No se encontraron resultados secundaria.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="tipo_asistencia" value="<?php echo $claseFormulario === 'formulario-asistencia primaria' ? 'primaria' : 'secundaria'; ?>">
            </div>
        </div>
    </section>

    <script src="../js/script.js"></script>
    <script src="../js/asistencia.js"></script>

</body>

</html>