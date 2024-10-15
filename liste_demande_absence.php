<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['acces_absence'] != 1) {
    header('Location: acces_refuse.php');
}

require_once 'model/DemandeAbsence.php';

// Instanciation de la classe DemandeAbsence
$demandeAbsence = new DemandeAbsence();

// Récupérer toutes les demandes d'absence
$demandes = $demandeAbsence->lireDemandesAbsences();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes d'Absence</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border: none;
            margin-bottom: 20px;
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .statut-en-attente {
            background-color: #f9c74f;
            color: white;
        }

        .statut-accepte {
            background-color: #43aa8b;
            color: white;
        }

        .statut-refuse {
            background-color: #f94144;
            color: white;
        }

        .btn-export {
            background-color: #457b9d;
            color: white;
        }

        .icon-large {
            font-size: 24px;
        }

        .container-fluid {
            padding-top: 70px;
            /* Ajustez cette valeur selon la hauteur de votre navbar */
        }


        body {
            background-color: #ffffff;
            color: #333333;
            /* Couleur du texte principal en gris foncé */
            font-family: 'Montserrat', sans-serif;
        }

        .card {
            background-color: #f9f9f9;
            border: 1px solid #dddddd;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f1f1f1;
            border-bottom: 1px solid #dddddd;
            font-weight: bold;
            color: #333333;
            /* Couleur du texte dans l'en-tête des cartes */
        }

        .statut-en-attente {
            background-color: #f9c74f;
            color: #ffffff;
            /* Texte en blanc pour contraster avec le fond jaune */
        }

        .statut-accepte {
            background-color: #43aa8b;
            color: #ffffff;
            /* Texte en blanc pour contraster avec le fond vert */
        }

        .statut-refuse {
            background-color: #f94144;
            color: #ffffff;
            /* Texte en blanc pour contraster avec le fond rouge */
        }

        p {
            color: #333333;
            /* Couleur du texte dans les paragraphes */
        }

        .btn {
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .btn-success {
            background-color: #28a745;
            color: #ffffff;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #ffffff;
            border: none;
        }

        .btn-export {
            background-color: #457b9d;
            color: #ffffff;
            border: none;
        }

        .icon-large {
            font-size: 24px;
            color: #555555;
            /* Couleur de l'icône plus visible */
        }

        .actions button {
            margin-right: 10px;
        }

        .container-fluid {
            padding-top: 70px;
        }

        h1.main-title {
            color: #2c3e50;
            /* Couleur sombre pour le titre principal */
            font-size: 28px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <?php include 'menu.php'; ?>


    <div class="container-fluid mt-5">
        <div class="row">
            <?php foreach ($demandes as $demande): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm 
                      <?php
                        if ($demande['statut'] === 'En Attente') {
                            echo 'statut-en-attente';
                        } elseif ($demande['statut'] === 'Acceptee') {
                            echo 'statut-accepte';
                        } elseif ($demande['statut'] === 'Refusee') {
                            echo 'statut-refuse';
                        }
                        ?>">
                        <div class="card-header d-flex justify-content-between">
                            <span><?= htmlspecialchars($demande['nom']) ?> - <?= htmlspecialchars($demande['matricule']) ?></span>
                            <i class="fas <?= $demande['statut'] === 'Acceptee' ? 'fa-check-circle' : ($demande['statut'] === 'Refusee' ? 'fa-times-circle' : 'fa-clock') ?> icon-large"></i>
                        </div>
                        <div class="card-body">
                            <p><strong>Fonction :</strong> <?= htmlspecialchars($demande['fonction']) ?></p>
                            <p><strong>Service :</strong> <?= htmlspecialchars($demande['service']) ?></p>
                            <p><strong>Motif :</strong> <?= htmlspecialchars($demande['motif']) ?></p>
                            <p><strong>Date de départ :</strong> <?= htmlspecialchars($demande['date_depart']) ?></p>
                            <p><strong>Date de retour :</strong> <?= htmlspecialchars($demande['date_retour']) ?></p>
                            <p><strong>Nombre de jours :</strong> <?= htmlspecialchars($demande['nombre_jours']) ?></p>
                            <div class="actions">
                                <?php if ($demande['statut'] === 'En Attente'): ?>
                                    <button class="btn btn-success">Accepter</button>
                                    <button class="btn btn-danger">Refuser</button>
                                <?php elseif ($demande['statut'] === 'Acceptee'): ?>
                                    <button class="btn btn-danger">Refuser</button>
                                <?php else: ?>
                                    <button class="btn btn-success">Accepter</button>
                                <?php endif; ?>
                                <button class="btn btn-export">Exporter</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="plugins/js/jquery-3.5.1.slim.min.js"></script>
    <script src="plugins/js/popper.min.js"></script>
    <script src="plugins/js/bootstrap.min.js"></script>
    <script src="js/style_script.js"></script>
</body>

</html>