<?php 
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

include('header_taches_terminees.php'); // Header spécifique aux tâches terminées
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tâches Terminées</title>
  <!-- Intégration de Bootstrap et FontAwesome pour les icônes -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style_taches_terminees.css" rel="stylesheet">
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
          <a class="nav-link" href="taches_terminees.php">Tâches Terminées</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="mb-4">Tâches Terminées</h2>

    <div class="container mt-4">
        <form id="filter-form" class="border p-4 rounded shadow">
            <h4 class="mb-3">Filtrer les Tâches</h4>
            
            <div class="form-row align-items-end">
                <div class="form-group col-md-6">
                    <label for="date_debut">Date de Début:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" id="date_debut" name="date_debut" class="form-control" value="<?php echo gmdate('Y-m-'.'01'); ?>" required>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="date_fin">Date de Fin:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" id="date_fin" name="date_fin" class="form-control" value="<?php echo gmdate('Y-m-d'); ?>" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mr-2">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            
            <a id="exportBtn" style="margin:4px;" href="request/export_tasks_terminees_list.php?date_debut=<?= htmlspecialchars($date_debut); ?>&date_fin=<?= htmlspecialchars($date_fin); ?>&status=Termine" class="btn btn-primary">
              <i class="fas fa-file-pdf"></i> Exporter en PDF
            </a>
        </form>
    </div>

    <div class="container mt-3">
      <div id="results-container">
      </div>
      <div id="loading" style="display:none;">
          <div class="loading-spinner"></div>
          <div>Chargement...</div>
      </div>
    </div>
  </div>

  <!-- Intégration de Bootstrap JS et dépendances -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/script_taches_terminees.js"></script>
</body>
</html>
