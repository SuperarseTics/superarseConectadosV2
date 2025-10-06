// public/js/transferencias.js

document.addEventListener("DOMContentLoaded", function () {
  // --- 1. Obtener Elementos del DOM ---
  const bankButtons = document.querySelectorAll(".bank-tab-button");
  const selectedBankNameDisplay = document.getElementById("selected-bank-name");
  const selectedAccountType = document.getElementById("selected-account-type");
  const selectedAccountNumber = document.getElementById(
    "selected-account-number"
  );
  const studentIdDataElement = document.getElementById("student-id-data");
  const sendComprobanteBtn = document.getElementById("send-comprobante-btn");

  // --- 2. Variables de Estado ---
  let selectedBankId = null;
  let selectedBankName = "";

  // Asignación segura del ID del estudiante
  const currentStudentId = studentIdDataElement
    ? studentIdDataElement.dataset.id
    : "ID_NO_ENCONTRADO";

  // Función para actualizar los detalles del banco y llamar a la validez
  function updateBankDetails(button) {
    selectedBankNameDisplay.textContent = button.dataset.bankName;
    selectedAccountType.textContent = button.dataset.accountType;
    selectedAccountNumber.textContent = button.dataset.accountNumber;
    selectedBankName = button.dataset.bankName;
    selectedBankId = button.dataset.bankId;

    checkFormValidity();
  }

  // --- Función Principal: Construye y Habilita el Enlace Mailto ---
  function checkFormValidity() {
    const hasBankSelected = selectedBankId !== null;

    if (hasBankSelected) {
      // Construir el cuerpo del correo
      const subject = encodeURIComponent(
        `Notificación de Pago - ID: ${currentStudentId}`
      );

      const body = encodeURIComponent(
        `Adjunto mi comprobante de pago.\n\n` +
          `✅ RECUERDE ADJUNTAR EL COMPROBANTE.\n\n` +
          `--- Detalles de la Operación ---\n` +
          `Estudiante ID: ${currentStudentId}\n` +
          `Banco Seleccionado: ${selectedBankName}`
      );

      // Construir el enlace mailto:
      const mailtoLink = `mailto:matriculas@superarse.edu.ec?subject=${subject}&body=${body}`;

      // 3. Habilitar el enlace
      sendComprobanteBtn.setAttribute("href", mailtoLink);
      sendComprobanteBtn.classList.remove("opacity-50", "cursor-not-allowed");
      sendComprobanteBtn.classList.add("hover:bg-superarse-morado-medio");
    } else {
      // 4. Deshabilitar el enlace
      sendComprobanteBtn.setAttribute("href", "#");
      sendComprobanteBtn.classList.add("opacity-50", "cursor-not-allowed");
      sendComprobanteBtn.classList.remove("hover:bg-superarse-morado-medio");
    }
  }

  // --- 3. Event Listeners e Inicialización ---

  // Event Listeners para los botones de banco
  bankButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Lógica de estilos de botones
      bankButtons.forEach((btn) => {
        btn.classList.remove(
          "bg-superarse-morado-oscuro",
          "hover:bg-superarse-morado-medio"
        );
        btn.classList.add("bg-gray-400", "hover:bg-gray-500");
      });
      this.classList.add(
        "bg-superarse-morado-oscuro",
        "hover:bg-superarse-morado-medio"
      );
      this.classList.remove("bg-gray-400", "hover:bg-gray-500");

      updateBankDetails(this);
    });
  });

  // Inicializar con el primer banco si existe
  if (bankButtons.length > 0) {
    // Simular un click en el primer botón para mostrar los detalles y habilitar el enlace
    bankButtons[0].click();
  } else {
    // Si no hay bancos, deshabilitar el enlace
    checkFormValidity();
  }
});
