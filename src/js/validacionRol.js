function togglePassword() {
  const input = document.getElementById("contrasena");
  const icon = document.getElementById("toggleIcon");

  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  }
}

function validacionRol() {
    const rolSelect = document.getElementById("rol");
    const especialidadContainer = document.getElementById("especialidad-container");
    const especialidadSelect = document.getElementById("especialidad");
    const horarioAtencionSelect = document.getElementById("horario_atencion");

    function toggleEspecialidad() {
        if (rolSelect.value === "medico") {
            // Mostrar contenedor
            especialidadContainer.style.display = "block";
            // Cargar opciones solo si aún no se han cargado
            if (especialidadSelect.options.length <= 1) {
                fetch("../php/getEspecialidades.php")
                    .then(res => res.text())
                    .then(data => {
                        especialidadSelect.innerHTML += data;
                    })
                    .catch(err => console.error("Error cargando especialidades:", err));
            }
        } else {
            // Ocultar si no es médico
            especialidadContainer.style.display = "none";
            especialidadSelect.innerHTML = '<option value="">-- Selecciona una especialidad --</option>';
        }
    }
    function toggleTurnos() {
        if (rolSelect.value === "medico") {
            // Mostrar contenedor
            especialidadContainer.style.display = "block";
            // Cargar opciones solo si aún no se han cargado
            if (horarioAtencionSelect.options.length <= 1) {
                
                fetch("../php/getTurnos.php")
                    .then(res => res.text())
                    .then(data => {
                        horarioAtencionSelect.innerHTML += data;
                    })
                    .catch(err => console.error("Error cargando especialidades:", err));
            }
        } else {
            // Ocultar si no es médico
            especialidadContainer.style.display = "none";
            horarioAtencionSelect.innerHTML = '<option value="">-- Selecciona una especialidad --</option>';
        }
    }

    // Escuchar cambios
    rolSelect.addEventListener("change", toggleEspecialidad);
    rolSelect.addEventListener("change", toggleTurnos);

    // Ejecutar validación inicial al cargar
    toggleEspecialidad();
    toggleTurnos();
}

// Ejecutar al cargar la página
document.addEventListener("DOMContentLoaded", validacionRol);
