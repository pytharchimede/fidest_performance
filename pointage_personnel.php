<?php 
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if($_SESSION['acces_rh']!=1){
  header('Location: acces_refuse.php');
}

// Inclure la classe Personnel pour lister le personnel
require_once 'model/Personnel.php';
$personnelObj = new Personnel();
$personnels = $personnelObj->listerPersonnel(); // Méthode qui retourne les données du personnel

// Traitement du pointage AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_personnel']) && isset($_POST['action'])) {
  $id_personnel = $_POST['id_personnel'];
  $date_pointage = date('Y-m-d');
  $heure_pointage = date('H:i:s');
  $action = $_POST['action'];
  
  // Vérifier si le pointage existe déjà pour aujourd'hui
  $pointageExistant = $personnelObj->verifierPointageDuJour($id_personnel, $date_pointage);
  
  if ($action === 'present') {
      if (!$pointageExistant) {
          // Enregistrer la présence (pointage) si elle n'a pas encore été faite aujourd'hui
          $personnelObj->enregistrerPresence($id_personnel, $date_pointage, $heure_pointage, 1);
          echo json_encode(['status' => 'success', 'message' => 'Pointage enregistré', 'id_personnel' => $id_personnel]);
      } else {
          echo json_encode(['status' => 'error', 'message' => 'Pointage déjà effectué pour aujourd\'hui']);
      }
  } elseif ($action === 'absent') {
      if (!$pointageExistant) {
          // Enregistrer l'absence (pointage) si elle n'a pas encore été faite aujourd'hui
          $personnelObj->enregistrerPresence($id_personnel, $date_pointage, '00:00:00', 0);
          echo json_encode(['status' => 'success', 'message' => 'Absence enregistrée', 'id_personnel' => $id_personnel]);
      } else {
          // Optionnel : mettre à jour l'entrée existante pour refléter l'absence si nécessaire
          $personnelObj->mettreAJourPresence($id_personnel, $date_pointage, '00:00:00', 0);
          echo json_encode(['status' => 'success', 'message' => 'Absence enregistrée', 'id_personnel' => $id_personnel]);
      }
  }
  exit;
}

//
$effectif_personnel = count($personnelObj->listerPersonnel());
$effectif_pointe_aujourdhui = count($personnelObj->verifierPointageDuJourPourToutLeMonde(date('Y-m-d')));


// Inclure la méthode pour vérifier les pointages existants pour aujourd'hui
$pointagesAujourdHui = $personnelObj->verifierPointageDuJourPourToutLeMonde(date('Y-m-d'));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pointage du Personnel</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    .list-group-item {
      cursor: pointer;
    }
    .list-group-item.clicked {
      opacity: 0.7;
    }
    .pointer-btn {
      cursor: pointer;
      display: inline-block;
      padding: 5px 10px;
      background-color: #007bff;
      color: #fff;
      border-radius: 5px;
    }
    .pointer-btn.success {
      background-color: #28a745;
    }
    .highlighted-title {
      border-bottom: 2px solid #fabd02;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .central-box {
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }

    /**Design badge avertissement */
    .wow-btn {
      background: linear-gradient(45deg, #1d2b57, #fabd02);
      color: #fff;
      padding: 10px 20px;
      border-radius: 30px;
      font-size: 18px;
      display: inline-block;
      transition: 0.3s ease-in-out;
      text-transform: uppercase;
  }
  
  .wow-btn i {
      margin-right: 8px;
  }

  .wow-btn:hover {
      background: linear-gradient(45deg, #fabd02, #1d2b57);
      color: #fff;
      transform: scale(1.1);
      box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
  }

  .wow-badge {
      background-color: #ffcc00;
      color: #22254b;
      font-weight: bold;
      border-radius: 20px;
      font-size: 16px;
      display: inline-block;
      padding: 10px 15px;
  }

  .wow-badge i {
      margin-right: 8px;
  }

  .wow-badge:hover {
      background-color: #ff9933;
      color: #fff;
      box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
  }
  
  .text-center {
      text-align: center;
  }

  /* Dans votre fichier CSS */

  .pointer-btn {
      border: none;
      color: white;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
  }

  .point-btn-present {
      background-color: #28a745; /* Vert */
  }

  .point-btn-absent {
      background-color: #dc3545; /* Rouge */
  }

  .status {
      font-weight: bold;
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
                <button class="pointer-btn point-btn-present <?php echo $pointageEffectue ? 'd-none' : ''; ?>" onclick="enregistrerPointage(this)" data-action="present">
                    Présent
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
