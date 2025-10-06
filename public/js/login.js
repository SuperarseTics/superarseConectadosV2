// public/js/login.js

document.addEventListener("DOMContentLoaded", function () {
  // Función para obtener parámetros de la URL
  function getUrlParameter(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
    var results = regex.exec(location.search);
    // Retorna el valor decodificado o null si no existe
    return results === null
      ? null
      : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

  // 1. Verificar si hay un error en la URL al cargar la página
  var error = getUrlParameter("error");

  if (error === "cedula_no_encontrada") {
    // Crea una nueva instancia del modal de Bootstrap
    var errorModal = new bootstrap.Modal(document.getElementById("errorModal"));

    // *** CAMBIO AQUÍ: Usamos las IDs 'errorModalLabel' y 'errorMessage' de tu HTML ***

    // Personalizar el título
    document.getElementById("errorModalLabel").textContent = "Error de Acceso";

    // Personalizar el mensaje en el cuerpo del modal
    document.getElementById("errorMessage").textContent =
      "Estimado/a, la cédula ingresada no se encuentra registrada en nuestros sistemas. Por favor, verifique el número e intente de nuevo.";

    // Muestra el modal
    errorModal.show();

    // Limpiar el parámetro de la URL
    if (window.history.replaceState) {
      var urlSinError =
        window.location.protocol +
        "//" +
        window.location.host +
        window.location.pathname;
      window.history.replaceState({ path: urlSinError }, "", urlSinError);
    }
  }
});
