<?php include('header_demande_pret.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de prêt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="css/style_demande.css" rel="stylesheet" />
</head>

<body>

    <div class="main-container">
        <div class="container">
            <div class="main-title-container">
                <h1 class="main-title">Demande de prêt</h1>
            </div>
            <h2>Remplissez les informations suivantes</h2>

            <form action="request/insert_demande_pret.php" method="post">
                <!-- Champ hidden pour le matricule -->
                <input type="hidden" name="matricule" value="<?= isset($employeeDetails['matricule_personnel_tasks']) ? $employeeDetails['matricule_personnel_tasks'] : '' ?>">

                <div class="form-group">
                    <label for="designation_pret">Désignation du prêt</label>
                    <i class="fas fa-file-signature icon"></i>
                    <input type="text" id="designation_pret" name="designation_pret" required>
                </div>

                <div class="form-group">
                    <label for="nom">NOM ET PRENOM(S)</label>
                    <i class="fas fa-user icon"></i>
                    <input type="text" id="nom" name="nom" value="<?= strtoupper($employeeDetails['nom_personnel_tasks']) ?>" readonly required>
                </div>

                <div class="form-group">
                    <label for="montant_demande">MONTANT DEMANDÉ (en FCFA)</label>
                    <i class="fas fa-money-bill-wave icon"></i>
                    <input type="number" id="montant_demande" name="montant_demande" required>
                </div>

                <div class="form-group montant-recouvrement-container">
                    <label for="montant_recouvrement">MONTANT DU RECOUVREMENT PARTIEL (en FCFA)</label>
                    <i class="fas fa-coins icon"></i>
                    <input type="number" id="montant_recouvrement" name="montant_recouvrement" class="red-input" required>
                    <span class="tooltip">Ce retranchement est irrévocable pendant la période de recouvrement</span>
                </div>

                <div class="form-group">
                    <label for="date_debut">DATE DE DÉBUT DU RECOUVREMENT</label>
                    <i class="fas fa-calendar-alt icon"></i>
                    <input type="date" id="date_debut" name="date_debut" readonly required>
                </div>

                <div class="form-group">
                    <label for="date_fin">DATE DE FIN DU RECOUVREMENT</label>
                    <i class="fas fa-calendar-alt icon"></i>
                    <input type="date" id="date_fin" name="date_fin" readonly required>
                </div>

                <!-- Section dynamique pour afficher le nombre de mensualités -->
                <div class="form-group">
                    <label for="nombre_mensualites">NOMBRE DE MENSUALITÉS</label>
                    <input type="text" id="nombre_mensualites" name="nombre_mensualites" readonly>
                </div>

                <button type="submit" class="submit-btn">Soumettre</button>
            </form>
            <a href="index.php">
                <button class="back-btn">Retour à l'accueil</button>
            </a>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Fonction pour calculer la date de début de recouvrement (le 5 du mois suivant)
            function calculerDateDebutRecouvrement() {
                let today = new Date();
                let moisSuivant = today.getMonth() + 1;
                let annee = today.getFullYear();

                if (moisSuivant > 11) { // Si mois est décembre
                    moisSuivant = 0;
                    annee++;
                }

                return new Date(annee, moisSuivant, 5); // Le 5 du mois suivant
            }

            // Fonction pour calculer le nombre de mensualités
            function calculerMensualites() {
                let montantDemande = parseFloat($('#montant_demande').val());
                let montantRecouvrement = parseFloat($('#montant_recouvrement').val());

                if (montantDemande && montantRecouvrement && montantRecouvrement > 0) {
                    return Math.ceil(montantDemande / montantRecouvrement);
                }

                return 0;
            }

            // Fonction pour calculer la date de fin de recouvrement
            function calculerDateFinRecouvrement(nombreMensualites) {
                let dateDebut = calculerDateDebutRecouvrement();
                let dateFin = new Date(dateDebut);
                dateFin.setMonth(dateDebut.getMonth() + nombreMensualites);
                return dateFin;
            }

            // Met à jour les dates et le nombre de mensualités
            function updateRecouvrement() {
                let nombreMensualites = calculerMensualites();
                $('#nombre_mensualites').val(nombreMensualites);

                let dateDebutRecouvrement = calculerDateDebutRecouvrement().toISOString().split('T')[0];
                $('#date_debut').val(dateDebutRecouvrement);

                if (nombreMensualites > 0) {
                    let dateFinRecouvrement = calculerDateFinRecouvrement(nombreMensualites).toISOString().split('T')[0];
                    $('#date_fin').val(dateFinRecouvrement);
                } else {
                    $('#date_fin').val('');
                }
            }

            // Appeler la fonction updateRecouvrement lors du changement des montants
            $('#montant_demande, #montant_recouvrement').on('input', function() {
                updateRecouvrement();
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="js/style_script.js"></script>
</body>

</html>