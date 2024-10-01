<?php include('header_demande_avance.php'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d'avance sur salaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="css/style_demande.css" rel="stylesheet" />
</head>
<body>

    <div class="main-container">

        <div class="container">
            <div class="main-title-container">
                <h1 class="main-title">Demande d'avance sur salaire</h1>
            </div>
            <h2>Remplissez les informations suivantes</h2>

            <form action="request/insert_demande_avance.php" method="post">
                <div class="form-group">
                    <label for="nom">NOM ET PRENOM(S)</label>
                    <i class="fas fa-user icon"></i>
                    <input type="text" id="nom" name="nom" value="<?=strtoupper($employeeDetails['nom_personnel_tasks'])?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="matricule">N° Matricule</label>
                    <i class="fas fa-id-card icon"></i>
                    <input type="text" id="matricule" name="matricule" value="<?=strtoupper($employeeDetails['matricule_personnel_tasks'])?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="fonction">FONCTION</label>
                    <i class="fas fa-briefcase icon"></i>
                    <input type="text" id="fonction" name="fonction" value="<?=strtoupper($fonction['lib_fonction_tasks'])?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="service">SERVICE</label>
                    <i class="fas fa-building icon"></i>
                    <input type="text" id="service" name="service" value="<?=strtoupper($service['lib_service_tasks'])?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="motif">Motif</label>
                    <i class="fas fa-edit icon"></i>
                    <textarea id="motif" name="motif" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="montant">MONTANT (en FCFA)</label>
                    <i class="fas fa-money-bill-wave icon"></i>
                    <input type="number" id="montant" name="montant" required>
                </div>

                <button type="submit" class="submit-btn">Soumettre</button>
            </form>
            <a href="index.php">
                <button class="back-btn">Retour à l'accueil</button>
            </a>
        </div>
    </div>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     $('.select-search').select2({
        //         placeholder: "Sélectionner une option",
        //         allowClear: true
        //     });

        //     // Form Submission
        //     $('form').on('submit', function(event) {
        //         event.preventDefault();

        //         var dataString = 'nom='+$('#nom').val()+'&matricule='+$('#matricule').val()+'&fonction='+$('#fonction').val()+'&service='+$('#service').val()+'&motif='+$('#motif').val()+'&montant='+$('#montant').val();
 
        //         console.log(dataString);
                
        //         $.ajax({
        //             url: 'request/insert_demande_avance.php',
        //             type: 'POST',
        //             data: dataString,
        //             processData: false,
        //             contentType: false,
        //             success: function(response) {
        //                 console.log('Réponse serveur:', response);
        //                // $(location).attr('href', 'dashboard.php');
        //             },
        //             error: function(jqXHR, textStatus, errorThrown) {
        //                 console.error('Erreur lors de la soumission de la demande :', textStatus, errorThrown);
        //             }
        //         });
        //     });
        // });
    </script>

</body>
</html>
