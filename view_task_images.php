<?php
session_start();
if (!isset($_SESSION['id_personnel_tasks'])) {
  header("Location: index.php");
  exit();
}

// Inclure la classe Task
require_once 'model/Task.php';

// Récupérer l'ID de la tâche
if (isset($_GET['taskId'])) {
  $taskId = intval($_GET['taskId']);

  // Récupérer les détails de la tâche
  $taskObj = new Task();
  $tache = $taskObj->getTaskById($taskId);

  // Récupérer les images stockées dans la tâche
  $images = !empty($tache['images']) ? json_decode($tache['images'], true) : [];
  $code_tache = $tache['task_code'];
  $nb_images = count($images);
} else {
  header("Location: taches_en_attente.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Images de la Tâche N° <?= $code_tache ?></title>
  <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
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

    <h2>Images de la Tâche N° <?= $code_tache ?></h2>

    <h4>Nombre d'images trouvées : <?= $nb_images ?></h4>

    <?php if (!empty($images)) : ?>
      <div id="carouselTaskImages" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <?php foreach ($images as $index => $image): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
              <img src="request/uploads/<?php echo htmlspecialchars($image); ?>" class="d-block w-100" alt="Image de la tâche">
            </div>
          <?php endforeach; ?>
        </div>
        <a class="carousel-control-prev" href="#carouselTaskImages" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Précédent</span>
        </a>
        <a class="carousel-control-next" href="#carouselTaskImages" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Suivant</span>
        </a>
      </div>
    <?php else : ?>
      <p>Aucune image disponible pour cette tâche.</p>
    <?php endif; ?>

    <a href="taches_en_attente.php" class="btn btn-primary mt-4">Retour aux Tâches</a>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="plugins/js/bootstrap.min.js"></script>
  <script src="js/style_script.js"></script>
</body>

</html>