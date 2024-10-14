<?php include('header_demande_absence.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de permission d'absence</title>
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
                <h1 class="main-title">Demande de permission d'absence</h1>
            </div>
            <h2>Remplissez les informations suivantes</h2>

            <form id="demandeForm" action="request/insert_demande_absence.php" method="post">
                <div class="form-group">
                    <label for="nom">NOM ET PRENOM(S)</label>
                    <i class="fas fa-user icon"></i>
                    <input type="text" id="nom" name="nom" value="<?= strtoupper($employeeDetails['nom_personnel_tasks']) ?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="matricule">N° Matricule</label>
                    <i class="fas fa-id-card icon"></i>
                    <input type="text" id="matricule" name="matricule" value="<?= strtoupper($employeeDetails['matricule_personnel_tasks']) ?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="fonction">FONCTION</label>
                    <i class="fas fa-briefcase icon"></i>
                    <input type="text" id="fonction" name="fonction" value="<?= strtoupper($fonction['lib_fonction_tasks']) ?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="service">SERVICE</label>
                    <i class="fas fa-building icon"></i>
                    <input type="text" id="service" name="service" value="<?= strtoupper($service['lib_service_tasks']) ?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="date_depart">Date de départ</label>
                    <i class="fas fa-calendar-alt icon"></i>
                    <input type="date" id="date_depart" name="date_depart" required>
                </div>

                <div class="form-group">
                    <label for="nombre_jours">Nombre de jours</label>
                    <i class="fas fa-calendar-check icon"></i>
                    <input type="number" id="nombre_jours" name="nombre_jours" min="1" required>
                </div>

                <div class="form-group">
                    <label for="date_retour">Date de retour</label>
                    <i class="fas fa-calendar-alt icon"></i>
                    <input type="date" id="date_retour" name="date_retour" readonly required>
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
        $(document).ready(function() {
            // Calculer la date de retour lors du changement de la date de départ ou du nombre de jours
            $('#date_depart, #nombre_jours').on('change', function() {
                const dateDepart = new Date($('#date_depart').val());
                const nombreJours = parseInt($('#nombre_jours').val());

                if (!isNaN(dateDepart.getTime()) && !isNaN(nombreJours)) {
                    dateDepart.setDate(dateDepart.getDate() + nombreJours);
                    const options = {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    };
                    $('#date_retour').val(dateDepart.toLocaleDateString('fr-FR', options).split('/').reverse().join('-'));
                }
            });

            // Form Submission (Optional AJAX)
            // $('#demandeForm').on('submit', function(event) {
            //     event.preventDefault();
            //     // AJAX code can go here
            // });
        });
    </script>

    <script src="js/style_script.js"></script>
</body>

</html>