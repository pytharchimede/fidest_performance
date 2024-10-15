<?php include('header_demande_pret.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Demande de prêt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="plugins/js/jquery-3.6.0.min.js"></script>
    <link href="plugins/css/select2.min.css" rel="stylesheet" />
    <link href="css/style_demande.css" rel="stylesheet" />
    <style>
        /* Styles généraux pour la fiche */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .main-title-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .main-title {
            font-size: 26px;
            font-weight: bold;
            color: #333;
        }

        h2 {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Style de la box pour la fiche */
        .fiche-container {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            margin-bottom: 30px;
        }

        .fiche-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eaeaea;
        }

        .fiche-section:last-child {
            border-bottom: none;
        }

        .fiche-section label {
            font-weight: bold;
            color: #333;
        }

        .fiche-section span {
            font-weight: normal;
            color: #555;
        }

        /* Style du bouton retour */
        .back-btn {
            display: block;
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            text-align: center;
            font-size: 16px;
            cursor: pointer;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        /* Style pour l'impression */
        @media print {
            body {
                background-color: white;
            }

            .container {
                box-shadow: none;
                border: none;
            }

            .back-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="main-title-container">
            <h1 class="main-title">Fiche Demande de prêt</h1>
        </div>
        <h2>Résumé de la demande</h2>

        <!-- Affichage des informations de la demande dans une box -->
        <div class="fiche-container">
            <div class="fiche-section">
                <label>Désignation du prêt:</label>
                <span><?= isset($demandeDetails['designation_pret']) ? $demandeDetails['designation_pret'] : 'Non spécifié' ?></span>
            </div>

            <div class="fiche-section">
                <label>Nom et Prénom(s):</label>
                <span><?= strtoupper($employeeDetails['nom_personnel_tasks']) ?></span>
            </div>

            <div class="fiche-section">
                <label>Matricule:</label>
                <span><?= isset($employeeDetails['matricule_personnel_tasks']) ? $employeeDetails['matricule_personnel_tasks'] : 'Non spécifié' ?></span>
            </div>

            <div class="fiche-section">
                <label>Montant demandé (en FCFA):</label>
                <span><?= isset($demandeDetails['montant_demande']) ? number_format($demandeDetails['montant_demande'], 0, ',', ' ') . ' FCFA' : 'Non spécifié' ?></span>
            </div>

            <div class="fiche-section">
                <label>Montant du recouvrement partiel (en FCFA):</label>
                <span><?= isset($demandeDetails['montant_recouvrement']) ? number_format($demandeDetails['montant_recouvrement'], 0, ',', ' ') . ' FCFA' : 'Non spécifié' ?></span>
                <span class="tooltip">Ce retranchement est irrévocable pendant la période de recouvrement.</span>
            </div>

            <div class="fiche-section">
                <label>Date de début du recouvrement:</label>
                <span><?= isset($demandeDetails['date_debut']) ? date("d/m/Y", strtotime($demandeDetails['date_debut'])) : 'Non spécifiée' ?></span>
            </div>

            <div class="fiche-section">
                <label>Date de fin du recouvrement:</label>
                <span><?= isset($demandeDetails['date_fin']) ? date("d/m/Y", strtotime($demandeDetails['date_fin'])) : 'Non spécifiée' ?></span>
            </div>

            <div class="fiche-section">
                <label>Nombre de mensualités:</label>
                <span><?= isset($demandeDetails['nombre_mensualites']) ? $demandeDetails['nombre_mensualites'] : 'Non spécifié' ?></span>
            </div>

            <div class="fiche-section">
                <label>Signature:</label>
                <span><?= isset($demandeDetails['signature']) ? $demandeDetails['signature'] : 'Non signée' ?></span>
            </div>
        </div>

        <!-- Bouton retour -->
        <a href="index.php">
            <button class="back-btn">Retour à l'accueil</button>
        </a>
    </div>

    <script src="plugins/js/select2.min.js"></script>
    <script src="js/style_script.js"></script>
</body>

</html>