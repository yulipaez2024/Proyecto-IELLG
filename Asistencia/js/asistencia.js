/*REPORTE ANTERIOR Y SIGUIENTE*/
// Obtener los botones
const botonAnterior = document.querySelector(".boton-anterior");
const botonSiguiente = document.querySelector(".boton-siguiente");

// Agregar escuchadores de eventos a los botones
botonAnterior.addEventListener("click", () => {
  history.back(); // Regresar a la página anterior
});

botonSiguiente.addEventListener("click", () => {
  history.forward(); // Avanzar a la página siguiente
});
