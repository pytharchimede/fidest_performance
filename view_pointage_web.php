<?php
session_start();
require_once 'model/Database.php';

// Vérifier si l'utilisateur est connecté et a les droits nécessaires
if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['acces_rh'] != 1) {
    header('Location: acces_refuse.php');
    exit();
}

// Connexion à la base de données
$pdo = Database::getConnection();

// Récupérer le mois et l'année courant
$moisCourant = date('m');
$anneeCourante = date('Y');
$dateDebut = "$anneeCourante-$moisCourant-01";
$dateFin = date('Y-m-t'); // Dernier jour du mois courant

// Récupérer tous les pointages
$req = "SELECT p.nom_personnel_tasks, pt.date_pointage, pt.present 
        FROM personnel_tasks AS p
        LEFT JOIN pointage_personnel AS pt ON p.id_personnel_tasks = pt.personnel_tasks_id 
        WHERE pt.date_pointage BETWEEN :dateDebut AND :dateFin";
$records = $pdo->prepare($req);
$records->execute([':dateDebut' => $dateDebut, ':dateFin' => $dateFin]);

// Créer un tableau pour stocker les données
$pointages = [];
while ($row = $records->fetch()) {
    $pointages[$row['nom_personnel_tasks']][$row['date_pointage']] = $row['present'] ? 'Présent' : 'Absent';
}

// Afficher le pointage dans le navigateur
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointage du Personnel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        table {
            border: 1px solid;
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: 500;
        }

        td {
            color: white;
        }
    </style>
</head>

<body>
    <!-- Menu mobile-friendly -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <a class="navbar-brand text-primary" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="liste_personnel.php">Personnel</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Pointage du Personnel - <?php echo htmlspecialchars($moisCourant . '/' . $anneeCourante); ?></h1>
        <div class="mb-3">
            <a href="request/export_pointage_pdf.php" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Exporter en PDF</a>
            <a href="request/export_pointage_excel.php" class="btn btn-success"><i class="fas fa-file-excel"></i> Exporter en Excel</a>
            <a href="liste_personnel.php" class="btn btn-primary" style="margin-left: 10px;">Retour vers la Liste du Personnel</a>
        </div>
        <table>
            <tr>
                <th>#</th>
                <th>Nom Personnel</th>
                <?php foreach (new DatePeriod(new DateTime($dateDebut), new DateInterval('P1D'), new DateTime($dateFin . ' +1 day')) as $date): ?>
                    <th><?php echo htmlspecialchars($date->format("Y-m-d")); ?></th>
                <?php endforeach; ?>
                <th>Total Jours Travaillés</th>
            </tr>
            <?php $i = 0; ?>
            <?php foreach ($pointages as $nom => $statuts): $i++; ?>
                <tr>
                    <td style="color:#000;"><?php echo htmlspecialchars($i); ?></td>
                    <td style="color:#000;"><?php echo htmlspecialchars($nom); ?></td>
                    <?php
                    $totalJoursTravailles = 0;
                    foreach (new DatePeriod(new DateTime($dateDebut), new DateInterval('P1D'), new DateTime($dateFin . ' +1 day')) as $date):
                        $dateFormat = $date->format("Y-m-d");
                        $statut = isset($statuts[$dateFormat]) ? $statuts[$dateFormat] : 'Absent';
                        $couleur = ($statut === 'Présent') ? '#28a745' : '#dc3545'; // Vert pour présent, rouge pour absent
                    ?>
                        <td style="background-color: <?php echo $couleur; ?>;"><?php echo htmlspecialchars($statut); ?></td>
                        <?php if ($statut === 'Présent') $totalJoursTravailles++; ?>
                    <?php endforeach; ?>
                    <td style="color:#000; font-weight:bold;"><?php echo htmlspecialchars($totalJoursTravailles); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/style_script.js"></script>
</body>

</html>

<?php
unset($pdo);
?>