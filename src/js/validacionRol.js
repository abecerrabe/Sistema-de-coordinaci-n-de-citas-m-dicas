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
    const tipo_sangre = document.getElementById("tipo_sangre");
  

    const pacienteContainer = document.getElementById("paciente-container");
    /*  
     const alergiaTextArea = document.getElementById("alergia");
     const discapacidadTextArea = document.getElementById("discapacidad"); */

    function toggleEspecialidad() {
        if (rolSelect.value === "medico") {
            // Mostrar el contenedor de especialidades
            especialidadContainer.style.display = "block";

            if (especialidadSelect.options.length <= 1) {
                fetch("../php/getCargos.php")
                    .then(res => res.text())
                    .then(data => {
                        especialidadSelect.innerHTML += data;
                    })
                    .catch(err => console.error("Error cargando especialidades:", err));
            }

            // Ocultar datos de paciente si está visible
            pacienteContainer.style.display = "none";

        } else if (rolSelect.value === "paciente") {
            // Mostrar campos de paciente
            pacienteContainer.style.display = "block";
            especialidadContainer.style.display = "none";            
            tipo_sangre.required = true;

        } else {
            // Si es administrador u otro rol, ocultar ambos
            especialidadContainer.style.display = "none";
            pacienteContainer.style.display = "none";
        }
    }
    // Escuchar cambios
    rolSelect.addEventListener("change", toggleEspecialidad);

    // Ejecutar validación inicial al cargar
    toggleEspecialidad();
}

// Ejecutar al cargar la página
document.addEventListener("DOMContentLoaded", validacionRol);
