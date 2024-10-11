document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('boton-guardar').addEventListener('click', function () {
        // Crear un array para almacenar los datos de asistencia
        var asistenciaData = [];

        // Obtener todas las filas de la tabla de asistencia
        var rows = document.querySelectorAll('.styled-table tbody tr');

        // Iterar sobre cada fila de la tabla
        rows.forEach(function (row) {
            // Obtener los datos de cada celda de la fila actual
            var id_estudiante = row.cells[0].textContent;
            var asistencia_estudiante = row.querySelector('input[name^="asistencia"]:checked').value;
            var fecha_actual = row.cells[3].textContent;

            // Crear un objeto con los datos de la fila actual
            var rowData = {
                id_estudiante: id_estudiante,
                asistencia_estudiante: asistencia_estudiante,
                fecha_actual: fecha_actual
            };

            // Agregar el objeto a la matriz de datos de asistencia
            asistenciaData.push(rowData);
        });

        // Enviar los datos al servidor utilizando una solicitud HTTP (por ejemplo, fetch o AJAX)
        fetch('guardar_asistencia.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(asistenciaData)
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }
            return response.json();
        })        
        .catch(function (error) {
            console.error('Error:', error.name); // Muestra el tipo de error
            console.error('Message:', error.message); // Muestra el mensaje de error específico
            console.error('URL:', error.url); // Muestra la URL de la solicitud
            alert('Datos de asistencia guardados correctamente');
        });
        
        
    });
});
