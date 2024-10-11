function validarFormulario_contrasena() {
    var id_correo = document.getElementById("correo").value;
    var id_password = document.getElementById("password").value;
  
    if (id_correo == "") {
      alert(
        "Por favor, ingresa su correo electronico."
      );
      return false; // Detiene el envío del formulario
    }

    if (id_password  == "") {
        alert(
          "Por favor, ingresa tu nueva contraseña."
        );
        return false; // Detiene el envío del formulario
      }
  
    // Si todas las validaciones pasan, puedes enviar el formulario
    return true;
  }


  function validarFormulario_registro() {
    var id_nombres = document.getElementById("nombres").value;
    var id_correoele =document.getElementById("correo").value;
    var id_passwordr = document.getElementById("password").value;
  
    if (id_nombres == "") {
      alert(
        "Por favor, ingresa tu nombre completo."
      );
      return false; // Detiene el envío del formulario
    }

    if (id_correoele == "") {
        alert(
          "Por favor, ingresa tu correo electronico."
        );
        return false; // Detiene el envío del formulario
      }
      if (id_passwordr == "") {
        alert(
          "Por favor, ingresa tu contraseña."
        );
        return false; // Detiene el envío del formulario
      }
  
    // Si todas las validaciones pasan, puedes enviar el formulario
    return true;
  }