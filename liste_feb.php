<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['acces_rh'] != 1) {
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
$fiches = $ficheExpression->listerFiches();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Fiches d'Expression de Besoin (FEB)</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    </style>
</head>

<body>

    <!-- Menu de Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <a class="navbar-brand text-primary" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="liste_personnel.php">Personnel</a></li>
                <li class="nav-item"><a class="nav-link" href="pointage_personnel.php">Pointage</a></li>
                <li class="nav-item"><a class="nav-link" href="taches_en_attente.php">Tâches</a></li>
                <li class="nav-item"><a class="nav-link" href="demandes_report.php">Demandes de report</a></li>

                <li class="nav-item">
                    <a class="nav-link" href="liste_demande_avance.php">Demandes d'avances</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="liste_demande_pret.php">Demandes de prêt</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="liste_demande_absence.php">Demandes d'absence</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page de Liste des Fiches d'Expression de Besoin (FEB) -->
    <div class="container">
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

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>