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
    <link rel="stylesheet" href="css/style_liste_demande_absence.css">
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
                                <a style="margin-top:19px;" target="_blank" href="request/export_demande_absence.php?id=<?= $demande['id']; ?>" class="btn btn-export">Exporter</a>
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