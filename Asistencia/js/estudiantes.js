document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("miFormulario")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      // Aquí colocas la lógica de validación que mencionaste anteriormente
      var claseFormulario = document.querySelector(
        'input[name="tipo_asistencia"]'
      ).value;

      // Validar el docente seleccionado
      var docenteSelect;
      if (claseFormulario === "primaria") {
        docenteSelect = document.querySelector('select[name="docentep[]"]');
      } else if (claseFormulario === "secundaria") {
        docenteSelect = document.querySelector('select[name="docentes[]"]');
      }
      var docenteSeleccionado = docenteSelect.value;
      if (docenteSeleccionado === "") {
        alert("Por favor, seleccione un docente.");
        return;
      }

      // Validar que al menos un radio button esté seleccionado
      var radioButtons;
      if (claseFormulario === "primaria") {
        radioButtons = document.querySelectorAll('input[name^="asistenciap"]');
      } else if (claseFormulario === "secundaria") {
        radioButtons = document.querySelectorAll('input[name^="asistencias"]');
      }

      var alMenosUnoSeleccionado = false;
      radioButtons.forEach(function (radioButton) {
        if (radioButton.checked) {
          alMenosUnoSeleccionado = true;
        }
      });

      if (!alMenosUnoSeleccionado) {
        alert("Por favor, seleccione la asistencia de los estudiante.");
        return;
      }

      // Si es clase secundaria, validar la selección de materia
      if (claseFormulario === "secundaria") {
        var materiaSelect = document.querySelector(
          'select[name="id_materias[]"]'
        );
        var materiaSeleccionada = materiaSelect.value;
        if (materiaSeleccionada === "") {
          alert("Por favor, seleccione una materia.");
          return;
        }
      }

      // Si todo está validado, enviar el formulario
      this.submit();
    });
});
