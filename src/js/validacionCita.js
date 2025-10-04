function fechaActual(fechaInput) {
    const hoy = new Date();

    // Formatear fecha en YYYY-MM-DD
    const formato = (d) => d.toISOString().split("T")[0];

    fechaInput.min = formato(hoy);

    // Fecha máxima (hoy + 7 días)
    const maxDate = new Date();
    maxDate.setDate(hoy.getDate() + 7);
    fechaInput.max = formato(maxDate);
}

function inicializarCitas() {
    
    const cargo = document.getElementById("cargo");
    const medico = document.getElementById("medico");
    const jornadaContainer = document.getElementById("jornada-container");
    const jornadaLabel = document.getElementById("jornada");
    const horarioDisponibles = document.getElementById("horario");
    const fechaInput = document.getElementById("fecha");

    // Cargar lista de cargos solo una vez
    if (cargo.options.length <= 1) {
        fetch("../php/getCargos.php")
            .then((res) => res.text())
            .then((data) => (cargo.innerHTML += data))
            .catch((err) => console.error("Error cargando cargos:", err));
    }

    //Cargar médicos según el cargo seleccionado
    cargo.addEventListener("change", async () => {
        const cargoId = cargo.value;
        if (!cargoId) return;

        try {
            const res = await fetch(`../php/getMedicos.php?id_cargo=${cargoId}`);
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
        } catch (err) {
            console.error("Error cargando médicos:", err);
        }
    });

    //Mostrar jornada según el médico seleccionado
    medico.addEventListener("change", () => {
        const selected = medico.options[medico.selectedIndex];
        const jornada = selected?.dataset.jornada || "";

        fechaActual(fechaInput);
        jornadaContainer.style.display = "block";

        switch (jornada) {
            case "dia":
                jornadaLabel.textContent = "Mañana (8:00 AM - 12:00 PM)";
                break;
            case "tarde":
                jornadaLabel.textContent = "Tarde (2:00 PM - 6:00 PM)";
                break;
            default:
                jornadaLabel.textContent = "";
                break;
        }

        // Limpiar horarios disponibles cuando se cambie el médico
        horarioDisponibles.innerHTML = '<option value="">-- Selecciona un horario --</option>';
    });

    //Cargar horarios disponibles al elegir fecha
    fechaInput.addEventListener("change", async () => {
        const medicoId = medico.value;
        const fecha = fechaInput.value.trim();

        if (!medicoId || !fecha) return;

        try {
            const res = await fetch(`../php/getDisponibildadCitas.php?id_medico=${encodeURIComponent(medicoId)}&fecha=${encodeURIComponent(fecha)}`);
            const data = await res.text();
            horarioDisponibles.innerHTML = '<option value="">-- Selecciona un horario --</option>' + data;
        } catch (err) {
            console.error("Error cargando horarios disponibles:", err);
        }
    });
}

// Ejecutar al cargar la página
document.addEventListener("DOMContentLoaded", inicializarCitas);