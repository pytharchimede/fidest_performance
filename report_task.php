<?php
    session_start();

    if (!isset($_SESSION['id_personnel_tasks'])) {
        header("Location: index.php");
        exit();
    }

    include('header_report_task.php');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Demande de Report de Tâche</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  
  <style>
    .container {
      max-width: 600px;
      margin-top: 50px;
    }
    .soft-design {
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .soft-design h3 {
      color: #1d2b57;
    }
    .btn-submit {
      background-color: #1d2b57;
      color: #fff;
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

    <div class="container">
    <div class="soft-design">
        <h3>Demande Report Tâche N° <?=$task['task_code']?></h3>
        <p>Votre deadline actuelle pour cette tâche est <strong><?= htmlspecialchars($dateEnFrancais); ?></strong>.</p>
        <p>Vous désirez la reporter pour quand ?</p>
        
        <form action="request/submit_report_request.php" method="post">
            <div class="form-group">
                <input type="hidden" name="task_id" value="<?= $task_id; ?>">
                
                <label for="new_deadline">Nouvelle date proposée :</label>
                <input type="datetime-local" name="date_report_propose" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="motif_report">Motif de la demande de report :</label>
                <textarea name="motif_report" class="form-control" rows="3" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-paper-plane"></i> Envoyer la Demande
            </button>
        </form>

    </div>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
