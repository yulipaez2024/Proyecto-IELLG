$(document).ready(function() {
    $('#filtro-niveles select').change(function() {
        var nivelSeleccionado = $(this).val();
        if (nivelSeleccionado == 0) {
            $('#jornada').val(1);
        } else if (nivelSeleccionado >= 1 && nivelSeleccionado <= 5) {
            $('#jornada').val(1);
        } else if (nivelSeleccionado >= 6 && nivelSeleccionado <= 11) {
            $('#jornada').val(2);
        }
    });
});

function cambiarFormulario() {
    var jornadaSeleccionada = document.getElementById("jornada").value;
    var formularios = document.querySelectorAll(".formulario-asistencia");

    for (var i = 0; i < formularios.length; i++) {
        var formulario = formularios[i];
        if (formulario.classList.contains(jornadaSeleccionada)) {
            formulario.style.display = "block";
        } else {
            formulario.style.display = "none";
        }
    }
}
// Mostrar los botones y el formulario cuando se seleccione el nivel educativo
document.addEventListener('DOMContentLoaded', function() {
    var formulario = document.querySelector('.formulario-asistencia');
    var botones = document.querySelector('.botones-asistencia');
    formulario.style.display = 'block';
    botones.style.display = 'block';
});