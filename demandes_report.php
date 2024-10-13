<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
  header("Location: index.php");
  exit();
}

include('header_demandes_report.php'); // Inclure le header
require_once 'model/Helper.php';

$helperObj = new Helper();

// Supposons que $demandes_reports soit un tableau contenant les demandes de report à afficher
// Vous devez le remplir avec les données appropriées depuis votre base de données

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Demandes de Report</title>
  <!-- Intégration de Bootstrap et FontAwesome pour les icônes -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <style>
    /* Styles supplémentaires pour les demandes de report */
    .report-request {
      background-color: #f9f9f9;
      border-left: 4px solid #007bff;
      padding: 15px;
      margin-bottom: 10px;
    }

    .report-actions button {
      margin-right: 5px;
    }

    .report-alert {
      color: #ff0000;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <!-- Menu mobile-friendly -->
  <?php include 'menu.php'; ?>


  <div class="container mt-5">
    <h2 class="mb-4">Demandes de Report</h2>

    <ul class="list-group">
      <?php if (count($demandes_reports) > 0): ?>
        <?php foreach ($demandes_reports as $report): ?>
          <li class="list-group-item report-request">
            <div class="report-header">
              <h5>Demande de report pour la tâche : <?= htmlspecialchars($report['task_code']); ?></h5>
              <p><strong>Exécutant :</strong> <?= htmlspecialchars($taskObj->getTasksResponsable($report['id'])['nom_personnel_tasks']); ?></p>
              <p><strong>Description :</strong> <?= htmlspecialchars($report['description']); ?></p>
              <p><strong>Date limite actuelle :</strong> <?= htmlspecialchars($helperObj->dateEnFrancais($report['deadline'])); ?></p>
              <p><strong>Date de report proposée :</strong> <?= htmlspecialchars($helperObj->dateEnFrancais($report['date_report_propose'])); ?></p>
            </div>

            <div class="report-actions mt-3 row">
              <div class="col-12 col-md-4 mb-2">
                <form method="post" action="request/refuser_report.php?task_id=<?= $report['id'] ?>">
                  <input type="hidden" name="task_id" value="<?= htmlspecialchars($report['id']); ?>">
                  <button type="submit" name="action" value="refuse" class="btn btn-danger btn-block"><i class="fas fa-times"></i> Refuser</button>
                </form>
              </div>
              <div class="col-12 col-md-4 mb-2">
                <form method="get" action="reporter_autre_date.php">
                  <input type="hidden" name="task_id" value="<?= htmlspecialchars($report['id']); ?>">
                  <button type="submit" class="btn btn-success btn-block"><i class="fas fa-calendar-alt"></i> Reporter à une autre date</button>
                </form>
              </div>
            </div>


          </li>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-reports">
          <h3>Aucune demande de report en attente</h3>
        </div>
      <?php endif; ?>
    </ul>
  </div>

  <!-- Intégration de Bootstrap JS et dépendances -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="js/style_script.js"></script>
</body>

</html>