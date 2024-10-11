function togglePasswordVisibility() {
  var passwordField = document.getElementById("input");
  var verPasswordIcon = document.getElementById("verPassword");

  if (passwordField.type === "password") {
    passwordField.type = "text";
    verPasswordIcon.classList.remove("fa-eye");
    verPasswordIcon.classList.add("fa-eye-slash");
  } else {
    passwordField.type = "password";
    verPasswordIcon.classList.remove("fa-eye-slash");
    verPasswordIcon.classList.add("fa-eye");
  }
}

function validarFormulario() {
  var nombre_usuario = document.getElementById("usuario").value;
  var contrasena_usuario = document.getElementById("input").value;

  if (nombre_usuario === "") {
    alert("Por favor, ingresa tu usuario.");
    return false;
  }

  if (contrasena_usuario === "") {
    alert("Por favor, ingresa tu contraseña.");
    return false;
  }
  return true;
}
