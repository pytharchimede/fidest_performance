<?php 
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

include('header_taches_en_attente.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tâches en Attente</title>
  <!-- Intégration de Bootstrap et FontAwesome pour les icônes -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style_taches_en_attente.css" rel="stylesheet">

  <!-- Animation de clignotement pour les tâches expirées -->
  <style>
    /* Animation de clignotement */
    @keyframes clignoter {
      0% { opacity: 1; }
      50% { opacity: 0.5; }
      100% { opacity: 1; }
    }

    /* Classe pour les tâches expirées */
    .expired-task {
      background-color: #ffdddd;
      border-left: 4px solid #ff0000;
      animation: clignoter 1s infinite;
    }

    /* Icône d'alerte */
    .expired-icon {
      color: red;
      margin-right: 10px;
    }

    /* Bouton pour gérer les tâches */
    .btn-complete, .btn-cancel, .btn-reject {
      margin-right: 5px;
    }
  </style>
</head>
<body>

  <!-- Menu mobile-friendly -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <a class="navbar-brand text-primary" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="liste_personnel.php">Personnel</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pointage_personnel.php">Pointage</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="taches_en_attente.php">Tâches</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="demandes_report.php">Demandes de report</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="mb-4">Tâches en Attente (<?php echo $nbTaches; ?>) &nbsp;&nbsp;
      <?php if($_SESSION['role']=='superviseur'){ ?>
      <a href="ajouter_tache.php" class="btn btn-info align-right">
        <i class="fas fa-plus"></i> Ajouter une tâche
      </a>
      <?php } ?>
    </h2>
    <ul class="list-group">

      <?php if($nbTaches > 0): ?>
        <?php foreach ($taches as $tache): ?>
          <?php
            // Vérification si la tâche est expirée
            $now = date('Y-m-d');
            $isExpired = strtotime($tache['deadline']) < strtotime($now);
          ?>

          <li class="list-group-item <?= $isExpired ? 'expired-task' : ''; ?>">
            <div class="task-header">
              <?php if ($isExpired): ?>
                <i class="fas fa-exclamation-circle expired-icon"></i>
              <?php endif; ?>
              <h5><?= htmlspecialchars($tache['task_code']); ?></h5>
              &nbsp;&nbsp;&nbsp;
              <?php if (!empty($tache['images'])): ?>
                <a href="view_task_images.php?taskId=<?= htmlspecialchars($tache['id']); ?>" class="task-icon" title="Visualiser les images">
                  <i class="fas fa-paperclip"></i><span>Pièces jointes</span>
                </a>
              <?php endif; ?>
            </div>
            <!-- Alerte pour les demandes de report -->
            <?php if ($tache['report_demande'] == 1 ): ?>
              <div class="alert alert-info alert-dismissible fade show alert-clignotante" role="alert" id="alert-expired">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Alerte !</strong> Vous avez emis une demande de report pour cette tâches.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?>
            <div class="task-details">
              <?php if($_SESSION['role']=='superviseur'){ ?>
              <p><?= 'Exécutant : '.htmlspecialchars($taskObj->getTasksResponsable($tache['id'])['nom_personnel_tasks']) ?></p>
              <?php }else{ ?>
              <p><?= $taskObj->getTasksAssignateur($tache['id'])['nom_personnel_tasks'] != '' ? 'Assignateur : '.htmlspecialchars($taskObj->getTasksAssignateur($tache['id'])['nom_personnel_tasks']) : 'Aucun assignateur défini' ?></p>
              <?php } ?>
              <p>Description : <?= htmlspecialchars($tache['description']); ?></p>
              <p>Date limite : <?= htmlspecialchars($tache['deadline']); ?></p>
              <p>Durée : <?= htmlspecialchars($tache['duree']); ?></p>
            </div>
            <div class="task-actions">
              <form method="post" action="request/update_task_status.php" class="d-inline">
                <input type="hidden" name="task_code" value="<?= htmlspecialchars($tache['task_code']); ?>">
                <button type="submit" name="action" value="complete" class="btn btn-complete"><i class="fas fa-check"></i> Terminer</button>
              </form>

              <?php if ($_SESSION['role'] == 'superviseur') { ?>
              <form method="post" action="request/update_task_status.php" class="d-inline">
                <input type="hidden" name="task_code" value="<?= htmlspecialchars($tache['task_code']); ?>">
                <button type="submit" name="action" value="cancel" class="btn btn-cancel"><i class="fas fa-ban"></i> Annuler</button>
              </form>
              <?php } ?>

              <?php if($tache['report_demande']!=1){ ?>
              <form method="get" action="report_task.php" class="d-inline">
                <input type="hidden" name="task_id" value="<?= htmlspecialchars($tache['id']); ?>">
                <button type="submit" class="btn btn-reject">
                  <i class="fas fa-times"></i> Reporter
                </button>
              </form>
              <?php } ?>

            </div>
          </li>

        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-tasks">
          <div class="smiley">😊</div>
          <h3>Aucune tâche en attente trouvée</h3>
        </div>
      <?php endif; ?>

    </ul>
  </div>

  <!-- Intégration de Bootstrap JS et dépendances -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
