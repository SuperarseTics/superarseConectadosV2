document
  .getElementById("payphone-link")
  .addEventListener("click", function (e) {
    e.preventDefault(); // Previene la acción por defecto del botón

    // 1. Validar y obtener datos
    const cantidadInput = document
      .getElementById("cantidad")
      .value.trim()
      .replace(",", ".");

    const cantidad = parseFloat(cantidadInput);

    if (!cantidad || isNaN(cantidad) || cantidad <= 0) {
      alert("Por favor, ingresa una cantidad válida y mayor a cero.");
      document.getElementById("cantidad").focus();
      return;
    }

    const referencia = document.getElementById("referencia").value.trim();
    const studentIdElement = document.getElementById("student-id-data");
    const identificacion = studentIdElement
      ? studentIdElement.getAttribute("data-id")
      : "SIN_ID";
    const referenciaFinal = `ID: ${identificacion} | Ref: ${
      referencia || "Pago Estudiantil"
    }`;

    // 2. Construir la URL que llama al CONTROLADOR (Ruta: /public/pago)
    const url =
      `/superarseconectadosv2/public/pago?cantidad=` +
      encodeURIComponent(cantidad) +
      `&referencia=` +
      encodeURIComponent(referenciaFinal) +
      `&vista=pasarela`; // 👈 **AÑADIMOS UNA BANDERA/FLAG**

    // 3. Abrir la nueva pestaña (usa el target blank que ya tienes)
    window.open(url, "_blank");
  });
