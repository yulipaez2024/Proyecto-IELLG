<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
<?php
error_reporting(E_ALL); // Mostrar todos los tipos de errores
ini_set('display_errors', 1); // Mostrar los errores en la página
// Establecer la conexión a la base de datos 
require('../conexion/conexion.php');

// Recibe los datos a través del formulario utilizando el método POST

if (!empty($_POST["btningresar"])) {
    if (!empty($_POST["usuario"]) && !empty($_POST["password"])) {
        $usuario = $_POST["usuario"];
        $contraseña = $_POST["password"];

         // Comprobar si la cuenta está bloqueada
         if (isAccountLocked($usuario)) {
            mostrarError("La cuenta está bloqueada. Por favor, contacte al administrador.");
            exit();
        }

        // Utilizar una sentencia preparada para evitar inyecciones SQL
        $stmt = $conn->prepare("SELECT * FROM docentes WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $contraseñaAlmacenada = $row["password"];

            // Verificar la contraseña ingresada con la contraseña almacenada utilizando password_verify
            if (password_verify($contraseña, $contraseñaAlmacenada)) {
                // La contraseña es correcta, continuar con la autenticación

                // Reiniciar el contador de intentos fallidos después de una autenticación exitosa
                resetFailedAttempts($usuario);

                // Registrar la fecha y hora de entrada en la base de datos
                $fechaEntrada = date("Y-m-d H:i:s");
                $stmtInsertEntrada = $conn->prepare("UPDATE docentes SET ultima_entrada = ? WHERE usuario = ?");
                $stmtInsertEntrada->bind_param("ss", $fechaEntrada, $usuario);
                $stmtInsertEntrada->execute();
                $stmtInsertEntrada->close();

                header("location: ../inicio.html");
                exit(); // Importante salir después de redirigir para evitar que el código siguiente se ejecute
            } else {
                // Incrementar el contador de intentos fallidos y bloquear la cuenta si es necesario
                handleFailedLogin($usuario);
                // La contraseña es incorrecta
                mostrarError("Contraseña incorrecta");
            }
        } else {
            // Usuario no encontrado
            mostrarError("Usuario no encontrado");
        }

        $stmt->close();
    }
}

function mostrarError($mensaje) {
?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Error</h4>
                <button class="close" onclick="location.href='../logeo.html'">&times;</button>
            </div>
            <div class="modal-body">
                <?php echo $mensaje; ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="location.href='../logeo.html'">Cerrar</button>
            </div>
        </div>
    </div>
<?php
}
function isAccountLocked($usuario) {
    global $conn; //conexión a la base de datos disponible en esta función

    $stmt = $conn->prepare("SELECT COUNT(*) FROM bloqueos_docentes bd INNER JOIN docentes d ON bd.id_docente = d.id_docente WHERE d.usuario = ? AND bd.cuenta_bloqueada = 1");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Retorna true si la cuenta está bloqueada (si count es mayor que 0), false si no lo está
    return ($count > 0);
}


function handleFailedLogin($usuario) {
    global $conn; // Asegúrate de tener la conexión a la base de datos disponible en esta función

    // Obtén el contador de intentos fallidos actual
    $stmtSelect = $conn->prepare("SELECT intentos_fallidos FROM docentes WHERE usuario = ?");
    $stmtSelect->bind_param("s", $usuario);
    $stmtSelect->execute();
    $stmtSelect->bind_result($intentosFallidos);
    $stmtSelect->fetch();
    $stmtSelect->close();

    // Incrementa el contador de intentos fallidos
    $intentosFallidos++;

    // Actualiza el contador de intentos fallidos en la base de datos
    $stmtUpdate = $conn->prepare("UPDATE docentes SET intentos_fallidos = ? WHERE usuario = ?");
    $stmtUpdate->bind_param("is", $intentosFallidos, $usuario);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    // Si el límite de intentos fallidos se alcanza, bloquea la cuenta
    if ($intentosFallidos >= 3) {
        lockAccount($usuario);
    }
}

function resetFailedAttempts($usuario) {
    global $conn; // Asegúrate de tener la conexión a la base de datos disponible en esta función

    // Reinicia el contador de intentos fallidos
    $stmt = $conn->prepare("UPDATE docentes SET intentos_fallidos = 0 WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->close();
}

function lockAccount($usuario) {
    global $conn; // conexión a la base de datos disponible en esta función

    // Obtener el id del docente
    $stmt = $conn->prepare("SELECT id_docente FROM docentes WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($id_docente);
    $stmt->fetch();
    $stmt->close();

    // Establecer la cuenta como bloqueada en la tabla bloqueos_docentes
    $stmt = $conn->prepare("INSERT INTO bloqueos_docentes (id_docente, cuenta_bloqueada) VALUES (?, 1)");
    $stmt->bind_param("i", $id_docente);
    $stmt->execute();
    $stmt->close();
}

?>

