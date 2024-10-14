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
  <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
  <link href="plugins/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style_taches_en_attente.css" rel="stylesheet">
  <!-- Animation de clignotement pour les tâches expirées -->
  <style>

  </style>
</head>

<body>

  <!-- Menu mobile-friendly -->
  <?php include 'menu.php'; ?>


  <div class="container mt-5">
    <h2 class="mb-4">Tâches en Attente &nbsp;&nbsp;
      <?php if ($_SESSION['role'] == 'superviseur') { ?>
        <a href="ajouter_tache.php" class="btn btn-info align-right">
          <i class="fas fa-plus"></i> Ajouter une tâche
        </a>
      <?php } ?>
    </h2>

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
              <input type="date" id="date_debut" name="date_debut" class="form-control" value="<?php echo gmdate('Y-m-' . '01'); ?>" required>
            </div>
          </div>

          <div class="form-group col-md-6">
            <label for="date_fin">Date de Fin:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
              <input type="date" id="date_fin" name="date_fin" class="form-control" value="<?php echo gmdate('Y-m-' . '27'); ?>" required>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary mr-2">
          <i class="fas fa-filter"></i> Filtrer
        </button>

        <a id="exportBtn" style="margin:4px;" href="request/export_tasks_list.php?date_debut=<?= $date_debut; ?>&date_fin=<?= $date_fin; ?>" class="btn btn-primary">
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
  <script src="plugins/js/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="plugins/js/bootstrap.min.js"></script>
  <script src="plugins/js/jquery-3.6.0.min.js"></script>
  <script src="js/script_taches_en_attente.js"></script>

  <script>

  </script>

  <script src="js/style_script.js"></script>
</body>

</html>