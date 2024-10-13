$("#btn-to-top").click(function () {
  $("html, body").animate(
    {
      scrollTop: 0,
    },
    "slow"
  );
});

// Vérifie le mode actuel et l'applique
const currentTheme = localStorage.getItem("theme") || "light"; // 'light' par défaut
document.body.classList.toggle("dark", currentTheme === "dark");

// Charge la feuille de style appropriée
function loadTheme(theme) {
  const link = document.createElement("link");
  link.rel = "stylesheet";
  link.href =
    theme === "dark"
      ? "css/style_dashboard_pc_dark.css"
      : "css/style_dashboard_pc_light.css";
  document.head.appendChild(link);
}

loadTheme(currentTheme);

// Gestion de l'événement de clic sur le bouton
document.getElementById("modeToggle").addEventListener("click", () => {
  const newTheme = currentTheme === "dark" ? "light" : "dark";
  localStorage.setItem("theme", newTheme); // Enregistre le thème dans localStorage
  location.reload(); // Recharge la page pour appliquer le nouveau thème
});

// Changer la couleur de fond de la navbar lors du défilement
window.addEventListener("scroll", function () {
  const navbar = document.querySelector(".navbar");
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});
