function openEditPopup(
  id,
  objet,
  quantite,
  prix_unitaire,
  fournisseur,
  telephone
) {
  console.log({ id, objet, quantite, prix_unitaire, fournisseur, telephone });

  document.getElementById("editId").value = id;
  document.getElementById("editObjet").value = objet || ""; // Vérifier la présence de 'besoin.objet'
  document.getElementById("editQuantite").value = quantite || "";
  document.getElementById("editPrixUnitaire").value = prix_unitaire || 0;
  document.getElementById("editFournisseur").value = fournisseur || "";
  document.getElementById("editTelephone").value = telephone || "";
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
    fetch("request/update_besoin.php", {
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
