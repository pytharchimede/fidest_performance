<?php 
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

include('header_dashboard.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord Salarié</title>
  <!-- Intégration de Bootstrap et FontAwesome pour les icônes -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style_dashboard.css" rel="stylesheet">
  <style>
    body {
  background-color: #f8f9fa;
    }

    h2 {
      color: #1d2b57; /* Couleur bleue */
    }

    canvas {
      border: 1px solid #ddd; /* Bordure des graphiques */
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
          <a class="nav-link" href="taches_en_attente.php">Tâches</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="demandes_report.php">Demandes de report</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="liste_demande_avance.php">Demandes d'avances</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="liste_demande_pret.php">Demandes de prêt</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="liste_demande_absence.php">Demandes d'absence</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">

    <!-- Alerte pour les tâches expirées -->
    <?php if ($nbTachesExpired > 0): ?>
      <div class="alert alert-danger alert-dismissible fade show alert-clignotante" role="alert" id="alert-expired">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <strong>Alerte !</strong> Vous avez <?= $nbTachesExpired; ?> tâches avec un délai expiré.
        <a href="taches_en_attente.php" class="btn btn-warning btn-sm ml-3">
          <i class="fas fa-tasks"></i> Voir les tâches expirées
        </a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <audio id="alert-sound" src="audio/alerte.mp3" preload="auto"></audio>
    <?php endif; ?>

    <!-- Ajout du bouton pour ouvrir le modal -->
    <div class="text-center mb-4">
        <button class="btn btn-primary" id="demandeButton" data-toggle="modal" data-target="#demandeModal">
            <i class="fas fa-plus-circle mr-2"></i> Faire une demande
        </button>
    </div>

    <!-- Section Profil avec photo, nom et poste -->
    <div class="profile-section mb-4 p-3 bg-white rounded shadow-sm d-flex align-items-center">

      <img src="<?php echo htmlspecialchars($_SESSION['photo_personnel_tasks'] ? 'https://stock.fidest.ci/app/&_gestion/photo/' . htmlspecialchars($_SESSION['photo_personnel_tasks']) : 'https://via.placeholder.com/60'); ?>" alt="Photo de profil" class="rounded-circle">
      
      <div class="profile-info ml-3">
        <h5 class="text-primary"><?php echo strtoupper($_SESSION['nom_personnel_tasks']); ?></h5>
        <p class="text-muted">Membre du personnel</p>
      </div>
    
    </div>

    <div class="row">
      <!-- Tâches section -->
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card bg-white shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-secondary">Tâches</h5>
            <ul class="list-group">
              <li class="list-group-item en-attente">
                <i class="fas fa-hourglass-half icon-en-attente"></i>
                <a href="taches_en_attente.php">En Attente:
                  <span class="badge badge-warning"><?php echo $nbTachesEnAttente; ?></span></a>
              </li>
              <li class="list-group-item effectuees">
                <i class="fas fa-check-circle icon-effectuees"></i>
                <a href="taches_terminees.php">Effectuées: 
                  <span class="badge badge-success"><?php echo $nbTachesOk; ?></span></a>
              </li>
              <li class="list-group-item rejetees">
                <i class="fas fa-times-circle icon-rejetees"></i>
                <h5 class="card-title text-secondary">Rejetées:
                  <span class="badge badge-danger"><?php echo $nbTachesRefusees; ?></span></h5>
              </li>
              <li class="list-group-item annulees">
                <i class="fas fa-ban icon-annulees"></i>
                <h5 class="card-title text-secondary">Annulées:
                  <span class="badge badge-secondary"><?php echo $nbTachesAnnulees; ?></span></h5>
              </li>
            </ul>
          </div>
        </div>
      </div>


      <!-- Section pour le temps de travail -->
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card bg-white shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-secondary">Temps de Travail</h5>
            <ul class="list-group">
              <li class="list-group-item">
                <i class="fas fa-hourglass-half"></i> Temps En Attente: 
                <span class="badge badge-warning"><?= $totalTimeEnAttenteHrs ?>h <?= $totalTimeEnAttenteMin ?>m</span>
              </li>
              <li class="list-group-item">
                <i class="fas fa-check-circle"></i> Temps Effectué: 
                <span class="badge badge-success"><?= $totalTimeEffectueesHrs ?>h <?= $totalTimeEffectueesMin ?>m</span>
              </li>
              <li class="list-group-item">
                <i class="fas fa-times-circle"></i> Temps Rejeté: 
                <span class="badge badge-danger"><?= $totalTimeRejeteesHrs ?>h <?= $totalTimeRejeteesMin ?>m</span>
              </li>
              <li class="list-group-item">
                <i class="fas fa-clock"></i> Temps Restant: 
                <span class="badge badge-secondary"><?= $totalTimeRestantHrs ?>h <?= $totalTimeRestantMin ?>m</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

      <!-- Score et classement -->
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card bg-light shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-secondary">Score</h5>
            <p class="card-text <?=$scoreClass?> display-4"><?= number_format(floatval($score), 2) ?>%</p>
            <div class="progress">
              <div class="progress-bar <?=$progressBarClass?>" role="progressbar"
                style="width: <?= number_format(floatval($score), 2) ?>%" 
                aria-valuenow="<?= number_format(floatval($score), 2) ?>"
                aria-valuemin="0" aria-valuemax="100">
                <?= number_format(floatval($score), 2) ?>%
              </div>
            </div>
            <h5 class="card-title text-secondary">Classement</h5>
            <p class="card-text display-4">
                #<?=$ranking?> <span class="ranking-effectif">/ <?=$effectif?></span>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Popup Modal -->
    <div class="modal fade" id="demandeModal" tabindex="-1" role="dialog" aria-labelledby="demandeModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="demandeModalLabel">Faire une Demande</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group">
              <li class="list-group-item">
                <a href="formulaire_introuvable.php?nom_form=absence" class="d-flex align-items-center">
                  <i class="fas fa-user-slash mr-2"></i> Demande de Permission d'Absence
                </a>
              </li>
              <li class="list-group-item">
                <a href="formulaire_introuvable.php?nom_form=pret" class="d-flex align-items-center">
                  <i class="fas fa-hand-holding-usd mr-2"></i> Demande de Prêt
                </a>
              </li>
              <li class="list-group-item">
                <a href="formulaire_introuvable.php?nom_form=avance" class="d-flex align-items-center">
                  <i class="fas fa-money-bill-alt mr-2"></i> Demande d'Avance
                </a>
              </li>
              <!-- <li class="list-group-item">
                <a href="formulaire_introuvable.php?nom_form=conge" class="d-flex align-items-center">
                  <i class="fas fa-plane mr-2"></i> Demande de Congés
                </a>
              </li> -->
            </ul>
          </div>
        </div>
      </div>
    </div>


  </div>

      <?php if($_SESSION['is_directeur']==1){ ?>
      <!-- Bouton flottant vers l'espace superviseur -->
      <a href="superviseur_dashboard.php" class="btn-float" title="Espace Superviseur">
        <i class="fas fa-user-tie"></i> Espace Superviseur
      </a>
      <?php }else{ ?>
      <!-- Bouton flottant vers la fiche d'évaluation du personnel connecté -->
      <a href="https://fidest.ci/performance/profil_personnel_tasks.php?id=<?=$monId?>" class="btn-float" title="Ma fiche d'évaluation">
        <i class="fas fa-file-pdf"></i> Ma fiche d'évaluation
      </a>
      <?php } ?>


  <!-- Alerte gênante pour tâches expirées -->
  <script>
    // Vérifier s'il y a des tâches expirées et afficher une alerte
    var nbTachesExpired = <?php echo $nbTachesExpired; ?>;
    if (nbTachesExpired > 0) {
      alert('Vous avez ' + nbTachesExpired + ' tâche(s) avec délai expiré!');
    }
  </script>

  <!-- Intégration de Bootstrap JS et dépendances -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="js/script_dashboard.js"></script>
</body>
</html>
