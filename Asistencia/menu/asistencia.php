<?php
// Establecer el manejo de errores y visualización
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establecer la conexión con la base de datos
require '../conexion/conexion.php';

// Obtener el nivel seleccionado (si está definido)
$selectedNivel = $_POST['nivel'] ?? '';

// Inicializar la consulta SQL
$query = "SELECT estudiantes.id_estudiante, estudiantes.nombre_estudiante, estudiantes.curso_estudiante
          FROM estudiantes
          LEFT JOIN cursos ON cursos.id_curso = estudiantes.curso_estudiante";

// Crear un array para almacenar las condiciones del filtro
$conditions = array();

// Agregar condiciones al filtro
if (!empty($selectedNivel)) {
    $conditions[] = "estudiantes.curso_estudiante = ?";
}

// Si hay condiciones en el array, agregar un WHERE a la consulta
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

try {
    $stmt = $conn->prepare($query);
    if ($stmt) {
        // Vincular parámetros según sea necesario
        if (!empty($selectedNivel)) {
            $stmt->bind_param("s", $selectedNivel);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar el nivel seleccionado y mostrar la clase correspondiente
        if ($selectedNivel >= 1 && $selectedNivel <= 6) { // Mostrar para primaria
            $claseFormulario = "formulario-asistencia primaria";
        } elseif ($selectedNivel >= 7 && $selectedNivel <= 12) { // Mostrar para secundaria
            $claseFormulario = "formulario-asistencia secundaria";
        } else {
            // No se seleccionó ningún nivel, asignar una clase por defecto
            $claseFormulario = "";
        }
    } else {
        throw new Exception("Error al preparar la consulta SQL");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Obtener los datos de la tabla 'materia'
$query = "SELECT id_materia, nombre_materia FROM materias";
$resultado = $conn->query($query);

// Verificar si se obtuvieron resultados
if ($resultado->num_rows > 0) {
    // Crear un array asociativo para almacenar los datos de las materias
    $materias = array();
    while ($row = $resultado->fetch_assoc()) {
        // Almacenar los datos de la materia en el array asociativo
        $materias[$row['id_materia']] = $row['nombre_materia'];
    }
} else {
    echo "No se encontraron materias en la base de datos.";
}

// Obtener los datos de la tabla 'docente'
$query = "SELECT id_docente, nombres FROM docentes";
$resultado_docentes = $conn->query($query);

// Verificar si se obtuvieron resultados
if ($resultado_docentes->num_rows > 0) {
    // Crear un array asociativo para almacenar los datos de docentes
    $docentes_array = array();
    while ($row = $resultado_docentes->fetch_assoc()) {
        // Almacenar los datos de docente en el array asociativo
        $docentes_array[$row['id_docente']] = $row['nombres'];
    }
} else {
    echo "No se encontraron docentes en la base de datos.";
}


?>

<!-- Tu código HTML/PHP para mostrar los datos -->



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/css.css">
    <link rel="stylesheet" href="../estilos/actualizar.css">
    <link rel="stylesheet" href="../estilos/tablareporte.css">
    <link rel="icon" href="../../images/1.png">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <title>Asistencia</title>
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
                            <i class='bx bx-folder-open icon'></i>
                            <span class="text nav-text">Cursos</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a class="select" href="asistencia.php">
                            <i class='bx bx-pie-chart-alt icon inicio'></i>
                            <span class="text nav-text">Asistencia</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="reporte.php">
                            <i class='bx bxs-file-doc icon'></i>
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
            <h1>Registrar asistencia estudiantes</h1>
        </div>

        <div class="form-table-container">
            <div class="form-container">
                <form method="post" id="filtro-niveles">
                    <label for="nivel">Filtrar por nivel educativo:</label>
                    <select name="nivel" id="nivel">
                        <option value="">Selecciona un nivel</option>
                        <option value="1" <?php if ($selectedNivel === '1') {
                                                echo 'selected';
                                            }
                                            ?>>Transición</option>
                        <option value="2" <?php if ($selectedNivel === '2') {
                                                echo 'selected';
                                            }
                                            ?>>Primero</option>
                        <option value="3" <?php if ($selectedNivel === '3') {
                                                echo 'selected';
                                            }
                                            ?>>Segundo</option>
                        <option value="4" <?php if ($selectedNivel === '4') {
                                                echo 'selected';
                                            }
                                            ?>>Tercero</option>
                        <option value="5" <?php if ($selectedNivel === '5') {
                                                echo 'selected';
                                            }
                                            ?>>Cuarto</option>
                        <option value="6" <?php if ($selectedNivel === '6') {
                                                echo 'selected';
                                            }
                                            ?>>Quinto</option>
                        <option value="7" <?php if ($selectedNivel === '7') {
                                                echo 'selected';
                                            }
                                            ?>>Sexto</option>
                        <option value="8" <?php if ($selectedNivel === '8') {
                                                echo 'selected';
                                            }
                                            ?>>Séptimo</option>
                        <option value="9" <?php if ($selectedNivel === '9') {
                                                echo 'selected';
                                            }
                                            ?>>Octavo</option>
                        <option value="10" <?php if ($selectedNivel === '10') {
                                                echo 'selected';
                                            }
                                            ?>>Noveno</option>
                        <option value="11" <?php if ($selectedNivel === '11') {
                                                echo 'selected';
                                            }
                                            ?>>Décimo</option>
                        <option value="12" <?php if ($selectedNivel === '12') {
                                                echo 'selected';
                                            }
                                            ?>>Undécimo</option>
                    </select>
                    <button type="submit">Filtrar</button>
                </form>
            </div>
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

            <div class="form-table-container">
                <form id="miFormulario" method="post" action="guardar_asistencia.php">
                    <div class="centrado">
                        <div class="<?php echo $claseFormulario; ?>">
                            <!-- Etiqueta y campo para el docente -->
                            <?php if ($claseFormulario === "formulario-asistencia primaria") : ?>
                                <!-- Contenido del formulario para primaria -->
                                <label for="docentep" style='margin-left: 55px;'>Docente:</label>
                                <select style='width: 150px; height: 30px;' name='docentep[]'>
                                    <option value=''>Seleccionar docente</option> <!-- Agregar opción vacía -->
                                    <?php foreach ($docentes_array as $id => $docente) : ?>
                                        <option value='<?= $id ?>'><?= $docente ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                                            </tr>
                                        </thead>
                                        <!-- Cuerpo de la tabla -->
                                        <tbody>
                                            <!-- Filas de la tabla -->
                                            <?php
                                            if (isset($result) && $result->num_rows > 0) {
                                                $currentDate = date("Y-m-d");
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td><input type='hidden' name='id_estudiantep[]' value='" . $row['id_estudiante'] . "'>" . $row['id_estudiante'] . "</td>";
                                                    echo "<td><input type='hidden' name='nombre_estudiantep[]' value='" . $row['nombre_estudiante'] . "'>" . $row['nombre_estudiante'] . "</td>";
                                                    echo "<td>" . $currentDate . "</td>";
                                                    echo "<td>";
                                                    echo "<input type='radio' name='asistenciap[" . $row['id_estudiante'] . "]' value='Asistió' class='asistencia-radio'> Asistió";
                                                    echo "<input type='radio' name='asistenciap[" . $row['id_estudiante'] . "]' value='Fallo' class='asistencia-radio'> Fallo";
                                                    echo "</td>";
                                                    echo "<td><input type='hidden' name='id_cursop[]' value='" . $row['curso_estudiante'] . "'>" . $row['curso_estudiante'] . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No se encontraron resultados primaria.</td></tr>";
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
                                        <label for="docentes" style='margin-left: 55px;'>Docente:</label>
                                        <select style='width: 150px; height: 30px;' name='docentes[]'>
                                            <option value=''>Seleccionar docente</option>
                                            <?php foreach ($docentes_array as $id => $docente) : ?>
                                                <?php $id_docente = isset($id_docente) ? $id_docente : ''; ?>
                                                <option value='<?= $id ?>' <?= ($id == $id_docente) ? 'selected' : '' ?>><?= $docente ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <label for="materiap" style='margin-left: 55px;'>Materia:</label>
                                        <select style='width: 150px; height: 30px;' name='id_materias[]'>
                                            <option value=''>Seleccionar materia</option>
                                            <?php
                                            // Definir la variable $materia_estudiante
                                            $materia_estudiante = isset($materia_estudiante) ? $materia_estudiante : '';

                                            // Iterar sobre el array de materias para crear las opciones del select
                                            foreach ($materias as $id => $materia) : ?>
                                                <option value='<?= $id ?>' <?= ($id == $materia_estudiante) ? 'selected' : '' ?>><?= $materia ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <thead>
                                            <tr>
                                                <!-- Encabezados de las columnas -->
                                                <th><i class="fas fa-id-card"></i> Documento del estudiante</th>
                                                <th><i class="fas fa-user"></i> Nombre completo</th>
                                                <th><i class="far fa-calendar-alt"></i> Fecha</th>
                                                <th><i class="far fa-check-square"></i> Asistencia</th>
                                                <th><i class="far fa-calendar-alt"></i> Curso</th>
                                            </tr>
                                        </thead>
                                        <!-- Cuerpo de la tabla -->
                                        <tbody>
                                            <!-- Filas de la tabla -->
                                            <?php
                                            if ($result && $result->num_rows > 0) {
                                                $currentDate = date("Y-m-d");
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td><input type='hidden' name='id_estudiantes[]' value='" . $row['id_estudiante'] . "'>" . $row['id_estudiante'] . "</td>";
                                                    echo "<td><input type='hidden' name='nombre_estudiantes[]' value='" . $row['nombre_estudiante'] . "'>" . $row['nombre_estudiante'] . "</td>";
                                                    echo "<td>" . $currentDate . "</td>";
                                                    echo "<td>";
                                                    echo "<input type='radio' name='asistencias[" . $row['id_estudiante'] . "]' value='Asistió' class='asistencia-radio'> Asistió";
                                                    echo "<input type='radio' name='asistencias[" . $row['id_estudiante'] . "]' value='Fallo' class='asistencia-radio'> Fallo";
                                                    echo "</td>";
                                                    echo "<td><input type='hidden' name='id_cursos[]' value='" . $row['curso_estudiante'] . "'>" . $row['curso_estudiante'] . "</td>";
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
                    <div class="botones-asistencia" style="display: none;">
                        <a href="asistencia.php" class="boton-cancelar">Cancelar</a>
                        <button type="submit" class="boton-guardar" id="guardarBoton">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <script src="../js/validaciones.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/guardar.js"></script>
    <script src="../js/mostrar.js"></script>
    <script src="../js/estudiantes.js"></script>
</body>

</html>