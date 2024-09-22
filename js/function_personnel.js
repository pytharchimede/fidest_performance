$(document).ready(function() {
    // Écouteur d'événement pour la soumission du formulaire
    $('#form-ajouter-personnel').on('submit', function(event) {
        event.preventDefault(); // Empêche le rechargement de la page

        // Validation du numéro de téléphone (exactement 10 chiffres, commençant par 0)
        var telephone = $('input[name="telephone"]').val();
        var phoneRegex = /^0[0-9]{9}$/;

        if (!phoneRegex.test(telephone)) {
            alert("Le numéro de téléphone doit comporter exactement 10 chiffres et commencer par 0.");
            return false; // Arrête l'envoi du formulaire si la validation échoue
        }

        // Création de l'objet FormData pour inclure les fichiers et autres données
        var formData = new FormData(this);

        $.ajax({
            url: 'request/insert_personnel.php', // L'URL du fichier de traitement PHP
            type: 'POST',
            data: formData,
            contentType: false, // Important pour envoyer des fichiers
            processData: false, // Important pour empêcher jQuery de traiter les données
            success: function(response) {
                // Gérer la réponse après le succès de la requête
                // alert('Le personnel a été ajouté avec succès !');
                // Réinitialise le formulaire après soumission
                console.log(response);
                $('#form-ajouter-personnel')[0].reset();
                $(location).attr('href', 'liste_personnel.php');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Gérer les erreurs
                console.error('Erreur lors de l\'envoi des données : ' + textStatus, errorThrown);
                alert('Une erreur est survenue, veuillez réessayer.');
            }
        });
    });



    // Écouteur d'événement pour la soumission du formulaire de modification de personnel
    $('#form-modifier-personnel').on('submit', function(event) {
        event.preventDefault(); // Empêche le rechargement de la page

        // Validation du numéro de téléphone (exactement 10 chiffres, commençant par 0)
        var telephone = $('input[name="telephone"]').val();
        var phoneRegex = /^0[0-9]{9}$/;

        if (!phoneRegex.test(telephone)) {
            alert("Le numéro de téléphone doit comporter exactement 10 chiffres et commencer par 0.");
            return false; // Arrête l'envoi du formulaire si la validation échoue
        }

        // Création de l'objet FormData pour inclure les fichiers et autres données
        var formData = new FormData(this);

        $.ajax({
            url: 'request/update_personnel.php', // L'URL du fichier de traitement PHP pour la mise à jour
            type: 'POST',
            data: formData,
            contentType: false, // Important pour envoyer des fichiers
            processData: false, // Important pour empêcher jQuery de traiter les données
            success: function(response) {
                // Gérer la réponse après le succès de la requête
                //alert('Les informations du personnel ont été mises à jour avec succès !');
                // Réinitialise le formulaire après soumission
                $('#form-modifier-personnel')[0].reset();
                $(location).attr('href', 'liste_personnel.php')
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Gérer les erreurs
                console.error('Erreur lors de l\'envoi des données : ' + textStatus, errorThrown);
                alert('Une erreur est survenue, veuillez réessayer.');
            }
        });
    });

});