function openEditPopup(id, objet, quantite, prixUnitaire) {
  document.getElementById("editId").value = id;
  document.getElementById("editObjet").value = objet;
  document.getElementById("editQuantite").value = quantite;
  document.getElementById("editPrixUnitaire").value = prixUnitaire;
  document.getElementById("editPopup").style.display = "block";
}

function closeEditPopup() {
  document.getElementById("editPopup").style.display = "none";
}

document
  .getElementById("editForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(this);
    // Vous pouvez faire une requête AJAX ici pour envoyer les données à votre serveur
    fetch("update_besoin.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        // Gérer la réponse du serveur ici
        closeEditPopup();
        location.reload(); // Rafraîchir la page pour voir les changements
      })
      .catch((error) => console.error("Erreur:", error));
  });
