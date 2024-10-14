<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['valid_besoin'] != 1) {
    header('Location: acces_refuse.php');
}
require_once 'model/Database.php';
require_once 'model/FicheExpressionBesoin.php';
require_once 'model/Service.php';
require_once 'model/Personnel.php';


//Instanciations
$databaseObj = new Database();
$pdo = $databaseObj->getConnection();

$serviceObj = new Service();
$personnelObj = new Personnel();

// Instanciation de la classe FicheExpression
$ficheExpression = new FicheExpressionBesoin($pdo);

// Récupérer toutes les fiches d'expression de besoin
$fiches = $ficheExpression->listerFichesEnAttente();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Fiches d'Expression de Besoin (FEB)</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="plugins/css/all.min.css">
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

        .btn-details {
            background-color: #fabd02;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s;
            display: block;
            margin-top: 10px;
            text-decoration: none;
        }

        .btn-details:hover {
            background-color: #e5a302;
        }

        .page-title {
            text-align: center;
            color: #1d2b57;
            font-size: 32px;
            margin-bottom: 40px;
        }

        .navbar {
            margin-bottom: 40px;
        }

        .navbar-brand {
            font-size: 24px;
            color: #1d2b57 !important;
        }

        .nav-link {
            color: #1d2b57 !important;
        }

        .nav-link.active {
            font-weight: bold;
        }


        .container-fluid {
            padding-top: 70px;
            /* Ajustez cette valeur selon la hauteur de votre navbar */
        }
    </style>
</head>

<body>

    <!-- Menu de Navigation -->
    <?php include 'menu.php'; ?>

    <!-- Page de Liste des Fiches d'Expression de Besoin (FEB) -->
    <div class="container-fluid mt-5">
        <h1 class="page-title">Liste des Fiches d'Expression de Besoin (FEB)</h1>

        <div class="row">
            <?php foreach ($fiches as $fiche): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-lg">
                        <div class="card-body">
                            <h3 class="card-title d-flex align-items-center">
                                <i class="fas fa-building text-primary mr-2"></i>
                                <span class="text-truncate" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= htmlspecialchars($serviceObj->obtenirServiceParId($fiche['departement'])['lib_service_tasks']) ?>
                                </span>
                            </h3>
                            <p class="card-text"><strong>Demandeur :</strong> <?= $personnelObj->getPersonnelByMatricule($fiche['matricule'])['nom_personnel_tasks'] ?></p>
                            <p class="card-text"><strong>Description :</strong> <?= htmlspecialchars($fiche['description']) ?></p>
                            <p class="card-text"><strong>Montant :</strong> <?= number_format($fiche['montant'], 2) ?> FCFA</p>
                            <p class="card-text"><strong>Créée le :</strong> <?= htmlspecialchars($fiche['date']) ?></p>
                            <a href="details_feb.php?id=<?= $fiche['id'] ?>" class="btn btn-primary d-flex align-items-center">
                                <i class="fas fa-info-circle mr-2"></i> Voir les détails
                            </a>
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