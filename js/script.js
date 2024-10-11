// Obtener el formulario por su ID
const formulario = document.getElementById("formulario");

// Agregar un evento de escucha al formulario cuando se envíe
formulario.addEventListener("submit", function (event) {
  // Detener el envío del formulario
  event.preventDefault();

  // Validar los campos
  if (
    validarNombre() &&
    validarEmail() &&
    validarTelefono() &&
    validarMensaje()
  ) {
    // Si todos los campos son válidos, enviar el formulario
    formulario.submit();
  }
});

// Función para validar el nombre
function validarNombre() {
  const nombreInput = document.getElementById("nombre");
  const nombre = nombreInput.value.trim();

  if (nombre === "") {
    alert("Por favor, ingresa tu nombre completo.");
    nombreInput.focus();
    return false;
  }

  return true;
}

// Función para validar el email
function validarEmail() {
  const emailInput = document.getElementById("email");
  const email = emailInput.value.trim();

  // Expresión regular para validar el formato del email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (email === "") {
    alert("Por favor, ingresa tu dirección de email.");
    emailInput.focus();
    return false;
  } else if (!emailRegex.test(email)) {
    alert("Por favor, ingresa una dirección de email válida.");
    emailInput.focus();
    return false;
  }

  return true;
}

// Función para validar el teléfono
function validarTelefono() {
  const telefonoInput = document.getElementById("telefono");
  const telefono = telefonoInput.value.trim();

  // Expresión regular para validar el formato del teléfono
  const telefonoRegex = /^\d{10}$/;

  if (telefono === "") {
    alert("Por favor, ingresa tu número de teléfono.");
    telefonoInput.focus();
    return false;
  } else if (!telefonoRegex.test(telefono)) {
    alert("Por favor, ingresa un número de teléfono válido (10 dígitos).");
    telefonoInput.focus();
    return false;
  }

  return true;
}

// Función para validar el mensaje
function validarMensaje() {
  const mensajeInput = document.getElementById("mensaje");
  const mensaje = mensajeInput.value.trim();

  if (mensaje === "") {
    alert("Por favor, ingresa tu mensaje.");
    mensajeInput.focus();
    return false;
  }

  return true;
}

// Función para enviar el formulario por correo electrónico
function enviarFormulario() {
  // Obtener los valores de los campos del formulario
  const nombre = document.getElementById("nombre").value;
  const email = document.getElementById("email").value;
  const telefono = document.getElementById("telefono").value;
  const mensaje = document.getElementById("mensaje").value;

  // Crear los datos del formulario a enviar
  const data = {
    nombre,
    email,
    telefono,
    mensaje,
  };

  // Enviar los datos del formulario al servidor
  fetch("/enviar-email", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        alert("El formulario se ha enviado correctamente.");
        // Restablecer el formulario después de enviarlo
        document.getElementById("formulario").reset();
      } else {
        alert(
          "Ocurrió un error al enviar el formulario. Por favor, intenta nuevamente."
        );
      }
    })
    .catch((error) => {
      console.error("Error al enviar el formulario:", error);
      alert(
        "Ocurrió un error al enviar el formulario. Por favor, intenta nuevamente."
      );
    });
}

