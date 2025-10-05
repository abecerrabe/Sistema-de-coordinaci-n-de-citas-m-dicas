async function inicializarDasboard() {
    try {
        const res = await fetch("../php/getCargos.php");
        const cargosHTML = await res.text();
        cargo.innerHTML += cargosHTML;
    } catch (err) {
        console.error("Error cargando cargos:", err);
    }

}
document.addEventListener("DOMContentLoaded", inicializarDasboard);