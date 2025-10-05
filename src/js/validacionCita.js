function fechaActual(fechaInput, dataTempCitas = null) {
    const hoy = new Date();
    const formato = (d) => d.toISOString().split("T")[0];

     if ( dataTempCitas == null) {
         fechaInput.min = formato(hoy);
         const maxDate = new Date();
         maxDate.setDate(hoy.getDate() + 7);
         fechaInput.max = formato(maxDate);

     }

}

async function inicializarCitas() {
    const cargo = document.getElementById("cargo");
    const medico = document.getElementById("medico");
    const jornadaContainer = document.getElementById("jornada-container");
    const jornadaLabel = document.getElementById("jornada");
    const horarioDisponibles = document.getElementById("horario");
    const fechaInput = document.getElementById("fecha");
    const prioridad = document.getElementById("prioridad");
    const estado = document.getElementById("estado");

    fechaActual(fechaInput, dataTempCitas);

    // 1. Cargar lista de cargos
    try {
        const res = await fetch("../php/getCargos.php");
        const cargosHTML = await res.text();
        cargo.innerHTML += cargosHTML;
    } catch (err) {
        console.error("Error cargando cargos:", err);
    }

    // 2. Si estamos editando, precargar datos
    if (typeof dataTempCitas === "object" && dataTempCitas !== null) {
        console.log("🟡 Editando cita existente:", dataTempCitas);

        // Seleccionar cargo
        cargo.value = dataTempCitas.id_cargo;

        // Cargar médicos del cargo antes de marcar el seleccionado
        await cargarMedicos(dataTempCitas.id_cargo, dataTempCitas.id_medico);

        // Mostrar jornada
        const jornada = medico.options[medico.selectedIndex]?.dataset.jornada || "";
        mostrarJornada(jornada, jornadaContainer, jornadaLabel);

        // Asignar fecha
        fechaInput.value = dataTempCitas.fecha_cita;

        const horario  = dataTempCitas.hora_llegada + "|" + dataTempCitas.hora_finalizacion;
        // Cargar horarios según el médico y la fecha
        await cargarHorarios(dataTempCitas.id_medico, dataTempCitas.fecha_cita, horario, horarioDisponibles);

        // Asignar prioridad y estado
        prioridad.value = dataTempCitas.prioridad || "";
        estado.value = dataTempCitas.estado || "";

        return; // No agregamos listeners en modo edición
    }

    // 3. Listeners normales (modo creación)
    cargo.addEventListener("change", async () => {
        const cargoId = cargo.value;
        if (!cargoId) return;
        await cargarMedicos(cargoId);
    });

    medico.addEventListener("change", () => {
        const selected = medico.options[medico.selectedIndex];
        const jornada = selected?.dataset.jornada || "";
        mostrarJornada(jornada, jornadaContainer, jornadaLabel);
        horarioDisponibles.innerHTML = '<option value="">-- Selecciona un horario --</option>';
    });

    fechaInput.addEventListener("change", async () => {
        const medicoId = medico.value;
        const fecha = fechaInput.value.trim();
        if (!medicoId || !fecha) return;
        await cargarHorarios(medicoId, fecha, null, horarioDisponibles);
    });
}

// Función para cargar médicos según cargo
async function cargarMedicos(cargoId, medicoSeleccionado = null) {
    console.log(cargoId)
    const medico = document.getElementById("medico");
    const jornadaLabel = document.getElementById("jornada");
    const horarioDisponibles = document.getElementById("horario");

    try {
        const res = await fetch(`../php/getMedicos.php?id_cargo=${cargoId}`);
        console.log({res})
        const data = await res.json();
        medico.innerHTML = '<option value="">-- Selecciona un médico --</option>';
        jornadaLabel.textContent = "";
        horarioDisponibles.innerHTML = '<option value="">-- Selecciona un horario --</option>';

        data.forEach(({ id_medico, nombre_completo, horario_atencion }) => {
            const opt = document.createElement("option");
            opt.value = id_medico;
            opt.textContent = nombre_completo;
            opt.dataset.jornada = horario_atencion;
            medico.appendChild(opt);
        });

        if (medicoSeleccionado) {
            medico.value = medicoSeleccionado;
        }
    } catch (err) {
        console.error("Error cargando médicos:", err);
    }
}

// Función para mostrar jornada
function mostrarJornada(jornada, contenedor, label) {
    contenedor.style.display = "block";
    switch (jornada) {
        case "dia":
            label.textContent = "Mañana (8:00 AM - 12:00 PM)";
            break;
        case "tarde":
            label.textContent = "Tarde (2:00 PM - 6:00 PM)";
            break;
        default:
            label.textContent = "";
    }
}

// Función para cargar horarios disponibles
async function cargarHorarios(idMedico, fecha, horaSeleccionada = null, selectHorario) {
    try {
        const params = new URLSearchParams({
            id_medico: idMedico,
            fecha: fecha
        });

        // Si estamos editando una cita, agregar id_cita
        if (typeof dataTempCitas === "object" && dataTempCitas !== null) {
            params.append("id_cita", dataTempCitas.id);
        }

        const res = await fetch(`../php/getDisponibildadCitas.php?${params.toString()}`);
        const data = await res.text();

        selectHorario.innerHTML = '<option value="">-- Selecciona un horario --</option>' + data;

        // Marcar el horario actual si aplica
        if (horaSeleccionada) {
            [...selectHorario.options].forEach(opt => {
                if (opt.value.includes(horaSeleccionada)) {
                    opt.selected = true;
                }
            });
        }
    } catch (err) {
        console.error("Error cargando horarios disponibles:", err);
    }
}


document.addEventListener("DOMContentLoaded", inicializarCitas);
