const addButton = document.getElementById("agregarMedicamento");
const medicamentoForm = document.getElementById("medicamentoForm");
const modalTitle = document.getElementById("exampleModalLabel");
const submitButton = document.getElementById("guardarMedicamento");
const fields = {
  codigo: document.getElementById("codigo"),
  nombre: document.getElementById("nombre"),
  laboratorio: document.getElementById("laboratorio"),
  fecha_vencimiento: document.getElementById("fecha_vencimiento"),
  precio: document.getElementById("precio"),
  stock: document.getElementById("stock")
};

function renderFieldError(name, message = "") {
  const field = fields[name];
  const feedback = document.getElementById(`error-${name}`);
  field.classList.toggle("is-invalid", Boolean(message));
  feedback.textContent = message;
}

function renderErrors(errors = {}) {
  Object.keys(fields).forEach((name) => {
    renderFieldError(name, errors[name] || "");
  });
}

function clearForm() {
  medicamentoForm.reset();
  renderErrors();
}

addButton.addEventListener("click", () => {
  clearForm();
  modalTitle.textContent = "Agregar nuevo medicamento";
  submitButton.name = "Agregar";
  submitButton.value = "Agregar";
  submitButton.textContent = "Guardar";
});

document.querySelectorAll(".editarMedicamento").forEach((button) => {
  button.addEventListener("click", () => {
    document.getElementById("id_medicamento").value = button.dataset.id;
    fields.codigo.value = button.dataset.codigo;
    fields.nombre.value = button.dataset.nombre;
    fields.laboratorio.value = button.dataset.laboratorio;
    fields.fecha_vencimiento.value = button.dataset.fecha;
    fields.precio.value = button.dataset.precio;
    fields.stock.value = button.dataset.stock;
    renderErrors();
    modalTitle.textContent = "Actualizar medicamento";
    submitButton.name = "Actualizar";
    submitButton.value = "Actualizar";
    submitButton.textContent = "Actualizar";
  });
});

medicamentoForm.addEventListener("submit", async (event) => {
  event.preventDefault();
  renderErrors();

  const data = new FormData(medicamentoForm);
  const submitter = event.submitter || submitButton;
  data.append(submitter.name, submitter.value);
  submitButton.disabled = true;

  try {
    const response = await fetch(window.location.href, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" },
      body: data
    });
    const result = await response.json();

    if (!response.ok) {
      renderErrors(result.errors || {});
      return;
    }

    renderErrors();
    alert(result.message || "Operación realizada correctamente.");
  } catch (error) {
    alert("No se pudo procesar la solicitud.");
  } finally {
    submitButton.disabled = false;
  }
});