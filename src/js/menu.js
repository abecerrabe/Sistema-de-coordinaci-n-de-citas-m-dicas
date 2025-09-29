
const ROL = "<?php echo $rol; ?>";
const USUARIO = "<?php echo $usuarioNombre; ?>";

function toggleMenu(id) {
  const menu = document.getElementById(id);
  document.querySelectorAll("[id^='menu-']").forEach(m => {
    if (m !== menu) m.classList.add("hidden");
  });
  menu.classList.toggle("hidden");
}

document.addEventListener("click", (e) => {
  if (!e.target.closest("nav")) {
    document.querySelectorAll("[id^='menu-']").forEach(m => m.classList.add("hidden"));
  }
});

const btnLogout = document.getElementById("btnLogout");
if (btnLogout) {
  btnLogout.addEventListener("click", () => {
    const url = btnLogout.dataset.url;
    window.location.href = url;
  });
}