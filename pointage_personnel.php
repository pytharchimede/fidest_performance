<?php 
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if($_SESSION['acces_rh']!=1){
  header('Location: acces_refuse.php');
}

include('header_pointage_personnel.php');

date_default_timezone_set('Africa/Abidjan'); // Définit le fuseau horaire

$heure_actuelle = date('H:i'); // Obtenir l'heure actuelle au format HH:MM
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pointage du Personnel</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/style_pointage_personnel.css" rel="stylesheet">
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
          <a class="nav-link active" href="liste_personnel.php">Personnel</a>
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

  <div class="container mt-5 central-box">
    <h2 class="highlighted-title">Pointage du Personnel - <span id="current-date"></span></h2>

   <!-- Bouton d'exportation avec une icône -->
  <?php if($effectif_personnel == $effectif_pointe_aujourdhui){ ?>
      <div class="mb-4 text-center">
        <a target="_blank" href="request/export_presences.php" class="export-btn wow-btn shadow-lg">
          <i class="fas fa-file-pdf fa-lg"></i> Exporter en PDF
        </a>
      </div>
  <?php } else { ?>
      <div class="mb-4 text-center">
        <span class="badge badge-warning p-3 wow-badge shadow-lg">
          <i class="fa fa-exclamation-triangle"></i> Veuillez finaliser le pointage afin de pouvoir exporter la liste de présence.
        </span>
      </div>
  <?php } ?>


        <!-- Liste des personnels -->
<ul class="list-group mb-4" id="personnel-list">
    <?php if(count($personnels) > 0): ?>
        <?php 
        $pointagesAujourdHui = $pointagesAujourdHui ?? [];
        ?>
        <?php foreach ($personnels as $personnel): ?>
        <?php
            $id_personnel = htmlspecialchars($personnel['id_personnel_tasks']);
            $photo_personnel = htmlspecialchars($personnel['photo_personnel_tasks']);
            $nom_personnel = htmlspecialchars($personnel['nom_personnel_tasks']);
            $pointageEffectue = in_array($id_personnel, $pointagesAujourdHui);
        ?>
        <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $pointageEffectue ? 'clicked' : ''; ?>" data-id="<?= $id_personnel; ?>">
            <div>
                <img src="https://stock.fidest.ci/app/&_gestion/photo/<?= $photo_personnel; ?>" alt="Photo de <?= strtoupper($nom_personnel); ?>" class="rounded-circle" style="width: 50px; height: 50px;">
                <strong><?= $nom_personnel; ?></strong>
            </div>
            <div>
                <button class="pointer-btn point-btn-present <?php echo ($heure_actuelle > '08:30') ? "point-btn-retard" : "point-btn-present"; ?>  <?php echo $pointageEffectue ? 'd-none' : ''; ?>" onclick="enregistrerPointage(this)" data-action="present">
                    <?php echo ($heure_actuelle > '08:30') ? "Retard" : "Présent"; ?>
                </button>
                <button class="pointer-btn point-btn-absent <?php echo $pointageEffectue ? 'd-none' : ''; ?>" onclick="enregistrerPointage(this)" data-action="absent">
                    Absent
                </button>
                <span class="status <?php echo $pointageEffectue ? '' : 'd-none'; ?>">
                    <?php echo $pointageEffectue ? ($pointagesAujourdHui[$id_personnel] == 1 ? 'Présent' : 'Absent') : ''; ?>
                </span>
            </div>
        </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li class="list-group-item">Aucun personnel à afficher.</li>
    <?php endif; ?>
</ul>



  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    document.getElementById('current-date').textContent = new Date().toLocaleDateString();

    function enregistrerPointage(button) {
    var idPersonnel = button.closest('.list-group-item').getAttribute('data-id');
    var action = button.getAttribute('data-action');
    
      // Requête AJAX pour enregistrer le pointage
      $.ajax({
          url: 'pointage_personnel.php',
          type: 'POST',
          data: { 
              id_personnel: idPersonnel,
              action: action
          },
          success: function(response) {
              var result = JSON.parse(response);
              if (result.status === 'success') {
                  var item = button.closest('.list-group-item');
                  var statusSpan = item.querySelector('.status');
                  var btnPresent = item.querySelector('.point-btn-present');
                  var btnAbsent = item.querySelector('.point-btn-absent');
                  
                  if (action === 'present') {
                      item.classList.add('clicked');
                      statusSpan.textContent = 'Présent';
                      statusSpan.classList.remove('d-none');
                      btnPresent.classList.add('d-none');
                      btnAbsent.classList.add('d-none');
                  } else if (action === 'absent') {
                      item.classList.remove('clicked');
                      statusSpan.textContent = 'Absent';
                      statusSpan.classList.remove('d-none');
                      btnPresent.classList.add('d-none');
                      btnAbsent.classList.add('d-none');
                  }
              } else {
                  alert(result.message);
              }
          },
          error: function() {
              alert('Une erreur est survenue.');
          }
      });
  }



  </script>
</body>
</html>
