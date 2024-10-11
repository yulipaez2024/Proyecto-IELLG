
const body = document.querySelector("body"),
  sidebar = body.querySelector("nav"),
  toggle = body.querySelector(".toggle"),
  searchBtn = body.querySelector(".search-box"),
  modeSwitch = body.querySelector(".toggle-switch"),
  modeText = body.querySelector(".mode-text");

toggle.addEventListener("click", () => {
  sidebar.classList.toggle("close");
});

searchBtn.addEventListener("click", () => {
  sidebar.classList.remove("close");
});

modeSwitch.addEventListener("click", () => {
  body.classList.toggle("dark");

  if (body.classList.contains("dark")) {
    modeText.innerText = "Light mode";
  } else {
    modeText.innerText = "Dark mode";
  }
});

/*ESTUDIANTES*/
document.getElementById("agregar-btn").addEventListener("click", function () {
  var formularioAgregarDiv = document.querySelector(".formulario-agregar");
  var formularioActualizarDiv = document.querySelector(
    ".formulario-actualizar"
  );
  var formularioEliminarDiv = document.querySelector(".formulario-eliminar");

  formularioAgregarDiv.style.display = "block";
  formularioActualizarDiv.style.display = "none";
  formularioEliminarDiv.style.display = "none";
});

document
  .getElementById("actualizar-btn")
  .addEventListener("click", function () {
    var formularioAgregarDiv = document.querySelector(".formulario-agregar");
    var formularioActualizarDiv = document.querySelector(
      ".formulario-actualizar"
    );
    var formularioEliminarDiv = document.querySelector(".formulario-eliminar");

    formularioAgregarDiv.style.display = "none";
    formularioActualizarDiv.style.display = "block";
    formularioEliminarDiv.style.display = "none";
  });

document.getElementById("eliminar-btn").addEventListener("click", function () {
  var formularioAgregarDiv = document.querySelector(".formulario-agregar");
  var formularioActualizarDiv = document.querySelector(
    ".formulario-actualizar"
  );
  var formularioEliminarDiv = document.querySelector(".formulario-eliminar");

  formularioAgregarDiv.style.display = "none";
  formularioActualizarDiv.style.display = "none";
  formularioEliminarDiv.style.display = "block";
});

//validar formulario estudiantes

function validarFormulario() {
  var id_estudiante = document.getElementById("id-agregar").value;
  var nombre_estudiente = document.getElementById("nombre-estudiante").value;
  var curso_estudiante = document.getElementById("curso-agregar").value;

  // Realiza las validaciones necesarias
  if (id_estudiante == "") {
    alert("Por favor, ingresa el numero de identificación del estudiante.");
    return false; // Detiene el envío del formulario
  }

  if (nombre_estudiente == "") {
    alert("Por favor, ingresa nombre completo del estudiante.");
    return false; // Detiene el envío del formulario
  }

  if (curso_estudiante === "" || curso_estudiante === "a") {
    alert("Por favor, ingresa el curso del estudiante.");
    return false; // Detiene el envío del formulario
  }

  // Si todas las validaciones pasan, puedes enviar el formulario
  return true;
}

function validarFormulario_actualizar() {
  var id_actualizar = document.getElementById("id-actualizar").value;

  if (id_actualizar == "") {
    alert(
      "Por favor, ingresa el numero de identificación del estudiante a actualizar."
    );
    return false; // Detiene el envío del formulario
  }

  // Si todas las validaciones pasan, puedes enviar el formulario
  return true;
}

function validarFormulario_eliminar() {
  var id_eliminar = document.getElementById("id-eliminar").value;

  if (id_eliminar == "") {
    alert(
      "Por favor, ingresa el numero de identificación del estudiante a eliminar."
    );
    return false; // Detiene el envío del formulario
  }

  // Si todas las validaciones pasan, puedes enviar el formulario
  return true;
}

//validar formulario cursos

function validarFormulario_cursoagregar() {
  var id_agregarcurso = document.getElementById("id_curso").value;
  var nombre_curso = document.getElementById("nombre_curso").value;

  if (id_agregarcurso == "") {
    alert("Por favor, ingresa id del curso.");
    return false; // Detiene el envío del formulario
  }
  if (nombre_curso == "") {
    alert("Por favor, ingresa el nombre del curso.");
    return false; // Detiene el envío del formulario
  }

  // Si todas las validaciones pasan, puedes enviar el formulario
  return true;
}

function validarFormulario_cursoactualizar() {
  var actualizar_curso = document.getElementById("id-actualizar-curso").value;
  var actualizar_nombre = document.getElementById("actualizar_curso").value;

  if (actualizar_curso == "") {
    alert("Por favor, ingresa el numero del curso a actualizar.");
    return false; // Detiene el envío del formulario
  }
  if (actualizar_nombre == "") {
    alert("Por favor, ingresa el nombre del curso a actualizar.");
    return false; // Detiene el envío del formulario
  }

  // Si todas las validaciones pasan, puedes enviar el formulario
  return true;
}

function validarFormulario_cursoeliminar() {
  var id_eliminarcurso = document.getElementById("id-eliminar-curso").value;

  if (id_eliminarcurso == "") {
    alert("Por favor, ingresa el numero del curso a eliminar.");
    return false; // Detiene el envío del formulario
  }

  // Si todas las validaciones pasan, puedes enviar el formulario
  return true;
}

//cerrar sesion
function confirmLogout() {
  var confirmLogout = confirm("¿Estás seguro de que deseas cerrar sesión?");
  if (confirmLogout) {
      window.location.href = "../logeo.html";
  }
}


