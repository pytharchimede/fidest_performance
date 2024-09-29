<?php 
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if($_SESSION['role']!='superviseur'){
  header('Location: acces_refuse.php');
}

// Inclure la classe Task
require_once 'model/Task.php';
require_once 'model/Personnel.php';

//Récupérer le personnel
$personnelObj = new Personnel();
$personnelList = $personnelObj->listerPersonnel();


//Récupérer les tâches en attente 
$taskObj = new Task();
$taches = $taskObj->getTasksByStatus('En Attente');

$nbTaches = count($taches);

// Calculer l'heure actuelle
$now = new DateTime();

// Calculer les durées
$thirtyMinutes = (clone $now)->add(new DateInterval('PT30M'));
$twoHours = (clone $now)->add(new DateInterval('PT2H'));
$halfDay = (clone $now)->add(new DateInterval('PT4H')); // On considère ici une demi-journée comme 4h
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Tâches</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
  <link href="css/style_ajouter_tache.css" rel="stylesheet" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <a class="navbar-brand text-primary" href="dashboard.php"><i class="fas fa-tasks"></i> Gestion des Tâches</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Tableau de Bord</a>
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
          <a class="nav-link" href="logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="mb-4">Créer une Tâche</h2>
    <form action="#" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="taskCode">Code de la Tâche</label>
        <input type="text" class="form-control" id="taskCode" name="taskCode" value="FID/TSK/<?php echo $nbTaches+1; ?>" readonly required>
      </div>
      <div class="form-group">
        <label for="projet">Projet</label>
        <input type="text" class="form-control" id="projet" name="projet"  required>
      </div>
      <div class="form-group">
        <label for="matricule_assignateur">Assignateur</label>
        <select class="form-control select2" id="matricule_assignateur" name="matricule_assignateur" required>
            <option value="">Sélectionner le personnel</option>
            <?php foreach ($personnelList as $personnel) : ?>
            <option value="<?php echo $personnel['matricule_personnel_tasks']; ?>"><?php echo strtoupper($personnel['nom_personnel_tasks']); ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="taskDescription">Description de la Tâche</label>
        <textarea class="form-control" id="taskDescription" name="taskDescription" rows="3" required></textarea>
      </div>
      <div class="form-group">
        <label for="assignedTo">Assignée à</label>
        <select class="form-control select2" id="assignedTo" name="assignedTo" required>
            <option value="">Sélectionner le personnel</option>
            <?php foreach ($personnelList as $personnel) : ?>
            <option value="<?php echo $personnel['matricule_personnel_tasks']; ?>"><?php echo strtoupper($personnel['nom_personnel_tasks']); ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="deadline">Date Limite</label>
        <input type="datetime-local" class="form-control" id="deadline" name="deadline" required>
      </div>
      <!--
      <div class="form-group">
        <label for="deadline">Durée estimative</label>
        <input type="datetime-local" class="form-control" id="duree" name="duree" required>
      </div>
            -->
      <div class="form-group">
          <label for="duree">Durée estimative</label>
          <select class="form-control" id="duree" name="duree" required>
              <option value="0000-00-00 00:30:00">
                  30 min (<?php echo $thirtyMinutes->format('H:i'); ?>)
              </option>
              <option value="0000-00-00 02:00:00">
                  2h (<?php echo $twoHours->format('H:i'); ?>)
              </option>
              <option value="0000-00-00 04:00:00">
                  1/2 journée (<?php echo $halfDay->format('H:i'); ?>)
              </option>
          </select>
      </div>
      <div class="form-group">
        <label for="taskImage">Image de la Tâche</label>
        <div class="drop-zone" id="dropZone">
          <input type="file" id="fileInput" name="taskImage[]" multiple>
          <span>Glisser-déposer des images ici ou cliquez pour sélectionner</span>
        </div>
        <div class="image-select-container mt-3">
          <div class="image-preview" id="imagePreview"></div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Créer la Tâche</button>
    </form>
  </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script src="js/script_ajouter_tache.js"></script>


 <script>
    $(document).ready(function() {
      $('.select2').select2();

      // Drag and Drop
      const dropZone = $('#dropZone');
      const fileInput = $('#fileInput');
      const imagePreview = $('#imagePreview');

      dropZone.on('click', function() {
        fileInput.click();
      });

      dropZone.on('dragover', function(event) {
        event.preventDefault();
        dropZone.addClass('dragover');
      });

      dropZone.on('dragleave', function() {
        dropZone.removeClass('dragover');
      });

      dropZone.on('drop', function(event) {
        event.preventDefault();
        dropZone.removeClass('dragover');
        handleFiles(event.originalEvent.dataTransfer.files);
      });

      fileInput.on('change', function() {
        handleFiles(this.files);
      });

      function handleFiles(files) {
        Array.from(files).forEach(file => {
          const reader = new FileReader();
          reader.onload = function(e) {
            const imageItem = $('<div class="preview-item"><img src="' + e.target.result + '" alt="Image Preview"/><button class="remove-img">&times;</button></div>');
            imageItem.find('.remove-img').on('click', function() {
              $(this).parent().remove();
            });
            imagePreview.append(imageItem);
          };
          reader.readAsDataURL(file);
        });
      }
    });


   $(document).ready(function() {
    $('.select2').select2();

    // Drag and Drop
    const dropZone = $('#dropZone');
    const fileInput = $('#fileInput');
    const imagePreview = $('#imagePreview');

    dropZone.on('click', function() {
        fileInput.click();
    });

    dropZone.on('dragover', function(event) {
        event.preventDefault();
        dropZone.addClass('dragover');
    });

    dropZone.on('dragleave', function() {
        dropZone.removeClass('dragover');
    });

    dropZone.on('drop', function(event) {
        event.preventDefault();
        dropZone.removeClass('dragover');
        handleFiles(event.originalEvent.dataTransfer.files);
    });

    fileInput.on('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageItem = $('<div class="preview-item"><img src="' + e.target.result + '" alt="Image Preview"/><button class="remove-img">&times;</button></div>');
                imageItem.find('.remove-img').on('click', function() {
                    $(this).parent().remove();
                });
                imagePreview.append(imageItem);
            };
            reader.readAsDataURL(file);
        });
    }

    // Form Submission
    $('form').on('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        console.log('FormData:', formData);

        $.ajax({
            url: 'request/insert_task.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                //alert('Tâche ajoutée avec succès !');
                // Réinitialisez le formulaire ou effectuez d'autres actions
                console.log('Réponse serveur:', response);
                $(location).attr('href', 'taches_en_attente.php');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Erreur lors de l\'insertion de la tâche :', textStatus, errorThrown);
            }
        });
    });
});
    </script> 

</body>
</html>
