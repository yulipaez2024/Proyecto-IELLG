<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../../reportes/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Imagen en la esquina superior izquierda
        $this->Image('../../images/triangulosrecortados.png', 0, 0, 70); // x=0, y=0, tamaño=70
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'B', 10);
        // Número de página
        $this->Cell(170, 10, 'Todos los derechos reservados', 0, 0, 'C');
        $this->Cell(25, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        // Llamar al método Footer de la clase padre
        parent::Footer();
    }
}

date_default_timezone_set("America/Bogota");

$pdf = new PDF();
$pdf->AliasNbPages(); // Activar el número de páginas
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Celda para la Institución Educativa
$pdf->Cell(0, 10, utf8_decode('Institución Educativa Llano Grande'), 0, 1, 'C');
$pdf->Ln(10);

// Título del reporte
$pdf->Cell(0, 10, 'REPORTE DE ASISTENCIA', 0, 1, 'C');
$pdf->Ln(10);

$pdf->Cell(90, 20, 'Fecha: ' . date('d/m/Y'), 0, 0, 'C');
$pdf->Cell(90, 20, 'Hora: ' . date('H:i:s'), 0, 1, 'C');
$pdf->Ln(20);

// Establecer la conexión a la base de datos
require('../conexion/conexion.php');

$selectedNivel = $_GET['nivel'] ?? '';

// Establecer una consulta predeterminada para evitar que $query esté vacía
$query = '';
if (!empty($selectedNivel)) {
    if ($selectedNivel >= 1 && $selectedNivel <= 6) {
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 10, 'ID', 1, 0, "C");
        $pdf->Cell(60, 10, 'Nombre', 1, 0, "C");
        $pdf->Cell(20, 10, 'Fecha', 1, 0, "C");
        $pdf->Cell(20, 10, 'Nivel', 1, 0, "C");
        $pdf->Cell(20, 10, 'Asistencia', 1, 0, "C");
        $pdf->Cell(20, 10, 'Docente', 1, 1, "C");

        $query = "SELECT a.id_estudiante, e.nombre_estudiante, a.fecha_actual, a.asistencia_estudiante, a.id_docente, a.id_curso
                  FROM asistencia a
                  INNER JOIN estudiantes e ON e.id_estudiante = a.id_estudiante";
    } elseif ($selectedNivel >= 7 && $selectedNivel <= 12) {
        // Agregar el Cell de Materia solo para nivel secundario
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 10, 'ID', 1, 0, "C");
        $pdf->Cell(60, 10, 'Nombre', 1, 0, "C");
        $pdf->Cell(20, 10, 'Fecha', 1, 0, "C");
        $pdf->Cell(20, 10, 'Nivel', 1, 0, "C");
        $pdf->Cell(20, 10, 'Asistencia', 1, 0, "C");
        $pdf->Cell(20, 10, 'Docente', 1, 0, "C");
        $pdf->Cell(20, 10, 'Materia', 1, 1, "C");
        
        $query = "SELECT a.id_estudiante, e.nombre_estudiante, a.fecha_actual, a.asistencia_estudiante, c.nombre_curso, d.id_docente, m.nombre_materia
                  FROM asistencia_secundaria a
                  INNER JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
                  LEFT JOIN cursos c ON c.id_curso = a.id_curso
                  LEFT JOIN docentes d ON d.id_docente = a.id_docente
                  LEFT JOIN materias m ON m.id_materia = a.id_materia";
    }
}

// Prepara la consulta SQL
$stmt = $conn->prepare($query);

// Verifica si hay algún error al preparar la consulta
if (!$stmt) {
    die('Error al preparar la consulta SQL: ' . $conn->error);
}

// Ejecuta la consulta preparada
if (!$stmt->execute()) {
    die('Error al ejecutar la consulta SQL: ' . $stmt->error);
}

// Obtiene el resultado de la consulta
$result = $stmt->get_result();

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Imprimir los datos de la matriz en el PDF
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(20, 10, $row['id_estudiante'], 1, 0, 'C');
        $pdf->Cell(60, 10, utf8_decode($row['nombre_estudiante']), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode($row['fecha_actual']), 1, 0, 'C');
        if ($selectedNivel >= 1 && $selectedNivel <= 6) {
            $pdf->Cell(20, 10, utf8_decode($row['id_curso']), 1, 0, 'C');
            $pdf->Cell(20, 10, utf8_decode($row['asistencia_estudiante']), 1, 0, 'C');
            $pdf->Cell(20, 10, utf8_decode($row['id_docente']), 1, 1, 'C');
        } elseif ($selectedNivel >= 7 && $selectedNivel <= 12) {
            $pdf->Cell(20, 10, utf8_decode($row['nombre_curso']), 1, 0, 'C');
            $pdf->Cell(20, 10, utf8_decode($row['asistencia_estudiante']), 1, 0, 'C');
            $pdf->Cell(20, 10, utf8_decode($row['id_docente']), 1, 0, 'C');
            $pdf->Cell(20, 10, utf8_decode($row['nombre_materia']), 1, 1, 'C');
        }
    }
} else {
    // No se encontraron resultados
    echo "No se encontraron resultados.";
}

$pdf->Output();
$stmt->close();
$conn->close();
