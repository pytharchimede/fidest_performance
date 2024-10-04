<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if($_SESSION['acces_rh']!=1){
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .card { border: none; margin-bottom: 20px; transition: transform 0.2s ease-in-out; }
        .card:hover { transform: translateY(-5px); }
        .statut-en-attente { background-color: #f9c74f; color: white; }
        .statut-accepte { background-color: #43aa8b; color: white; }
        .statut-refuse { background-color: #f94144; color: white; }
        .btn-export { background-color: #457b9d; color: white; }
        .icon-large { font-size: 24px; }
    </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <a class="navbar-brand text-primary" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
              <li class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
              <li class="nav-item"><a class="nav-link active" href="liste_personnel.php">Personnel</a></li>
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
        </li>              <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
          </ul>
      </div>
  </nav>

  <div class="container mt-5">
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
                              <button class="btn btn-export">Exporter</button>
                          </div>
                      </div>
                  </div>
              </div>
          <?php endforeach; ?>
      </div>
  </div>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
