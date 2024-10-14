<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
  header("Location: index.php");
  exit();
}


if ($_SESSION['acces_rh'] != 1) {
  header('Location: acces_refuse.php');
}
// Inclure la classe Personnel
require_once 'model/Personnel.php';
$personnelObj = new Personnel();
$personnels = $personnelObj->listerPersonnel(); // Assurez-vous que cette méthode retourne les données du personnel

$nbPersonnels = count($personnels);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste du Personnel</title>
  <!-- Intégration de Bootstrap et FontAwesome pour les icônes -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <style>
    .personnel-header {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .personnel-photo {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 15px;
    }

    .personnel-icon {
      font-size: 16px;
      color: #007bff;
      display: flex;
      align-items: center;
      text-decoration: none;
    }

    .personnel-icon span {
      font-size: 12px;
      color: #6c757d;
      margin-left: 5px;
    }

    .btn-view,
    .btn-edit,
    .btn-remove {
      font-size: 14px;
      padding: 8px 12px;
    }

    .btn-view {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-edit {
      background-color: #ffc107;
      border-color: #ffc107;
    }

    .btn-remove {
      background-color: #dc3545;
      border-color: #dc3545;
    }

    .btn-view:hover,
    .btn-edit:hover,
    .btn-remove:hover {
      opacity: 0.9;
    }

    .list-group-item {
      display: flex;
      flex-direction: column;
      padding: 15px;
      margin-bottom: 10px;
    }

    .personnel-details {
      margin-bottom: 10px;
    }

    .personnel-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .btn-group {
      display: flex;
      flex-direction: row;
      gap: 10px;
    }

    @media (max-width: 576px) {
      .btn-group {
        flex-direction: column;
        align-items: stretch;
      }

      .btn-group .btn {
        width: 100%;
        margin-bottom: 5px;
      }
    }

    /* Style pour le message quand il n'y a pas de personnel */
    .no-personnel {
      text-align: center;
      margin-top: 50px;
    }

    .no-personnel .smiley {
      font-size: 50px;
      animation: bounce 2s infinite;
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    .no-personnel h3 {
      font-size: 24px;
      color: #6c757d;
    }
  </style>
</head>

<body>

  <!-- Menu mobile-friendly -->
  <?php include 'menu.php'; ?>


  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Liste du Personnel (<?php echo $nbPersonnels; ?>)</h2>
      <a href="ajouter_personnel.php" class="btn btn-info"><i class="fas fa-plus"></i> Ajouter</a>
      <a href="ajouter_personnel.php" class="btn btn-success"><i class="fa fa-file-excel"></i> Salaires</a>
      <a href="view_pointage_web.php" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Pointages</a>
      <a href="print_all_badge.php" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Badges</a>
    </div>
    <ul class="list-group">

      <?php if ($nbPersonnels > 0): ?>
        <?php foreach ($personnels as $personnel): ?>

          <li class="list-group-item">
            <div class="personnel-header">
              <?php if (!empty($personnel['photo_personnel_tasks'])): ?>
                <img src="https://stock.fidest.ci/app/&_gestion/photo/<?= htmlspecialchars($personnel['photo_personnel_tasks']); ?>" alt="Photo de <?= htmlspecialchars($personnel['nom_personnel_tasks']); ?>" class="personnel-photo">
              <?php else: ?>
                <div class="personnel-photo" style="background-color: #e9ecef;"></div>
              <?php endif; ?>
              <div>
                <h5><?= htmlspecialchars($personnel['nom_personnel_tasks']); ?></h5>
                <?php if (!empty($personnel['photo_personnel_tasks'])): ?>
                  <a target="_blank" href="https://stock.fidest.ci/app/&_gestion/photo/<?= htmlspecialchars($personnel['photo_personnel_tasks']); ?>" class="personnel-icon" title="Voir la photo">
                    <i class="fas fa-image"></i><span>Voir la photo</span>
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="personnel-details">
              <p>Matricule : <?= htmlspecialchars($personnel['matricule_personnel_tasks']); ?></p>
              <p>Sexe : <?php if ($personnel['sexe_personnel_tasks'] == 1) {
                          echo 'Masculin';
                        } else {
                          echo 'Feminin';
                        }; ?></p>
              <p>Date de naissance : <?= htmlspecialchars($personnel['date_nais_personnel_tasks']); ?></p>
              <p>Téléphone : <?= htmlspecialchars($personnel['tel_personnel_tasks']); ?></p>
              <p>Email : <?= htmlspecialchars($personnel['email_personnel_tasks']); ?></p>
            </div>
            <div class="personnel-actions">
              <div class="btn-group">
                <a href="modifier_personnel.php?id=<?= htmlspecialchars($personnel['id_personnel_tasks']); ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Modifier</a>
                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#prelevementModal" data-id="<?= htmlspecialchars($personnel['id_personnel_tasks']); ?>">
                  <i class="fas fa-minus"></i> Prélèvements
                </a>
              </div>
            </div>
          </li>

        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-personnel">
          <div class="smiley">😊</div>
          <h3>Aucun personnel trouvé</h3>
        </div>
      <?php endif; ?>

    </ul>
  </div>


  <!-- Modal -->
  <!-- Modal -->
  <div class="modal fade" id="prelevementModal" tabindex="-1" role="dialog" aria-labelledby="prelevementModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="prelevementModalLabel">Choisir le Type de Prélèvement</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="prelevementForm" action="request/ajouter_prelevement.php" method="post">
            <input type="hidden" name="personnel_id" id="personnelId" value="">
            <div class="form-group">
              <label for="type_prelevement">Type de Prélèvement</label>
              <select id="type_prelevement" name="type_prelevement" class="form-control" required>
                <option value="pret">Prêt</option>
                <option value="avance">Avance</option>
              </select>
            </div>
            <div id="pret-fields" class="form-group d-none">
              <label for="montant_total">Montant Total du Prêt</label>
              <input type="number" id="montant_total" name="montant_total" class="form-control" placeholder="Montant Total du Prêt">
              <label for="montant_recurrent">Montant Récurrent</label>
              <input type="number" id="montant_recurrent" name="montant_recurrent" class="form-control" placeholder="Montant Récurrent">
              <label for="nombre_prelevements_restants">Nombre de Prélèvements Restants</label>
              <input type="number" id="nombre_prelevements_restants" name="nombre_prelevements_restants" class="form-control" placeholder="Nombre de Prélèvements Restants" readonly>
              <label for="date_fin_prelevement">Le prélèvement prendra fin en</label>
              <input type="text" id="date_fin_prelevement" name="date_fin_prelevement" class="form-control" placeholder="Date de Fin" readonly>
            </div>
            <div id="avance-fields" class="form-group d-none">
              <label for="montant">Montant de l'Avance</label>
              <input type="number" id="montant" name="montant" class="form-control" placeholder="Montant de l'Avance">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </form>
        </div>
      </div>
    </div>
  </div>




  <!-- Intégration de Bootstrap JS et dépendances -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#prelevementModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var modal = $(this);
        modal.find('#personnelId').val(id);
      });

      document.getElementById('type_prelevement').addEventListener('change', function() {
        var type = this.value;
        if (type === 'pret') {
          document.getElementById('pret-fields').classList.remove('d-none');
          document.getElementById('avance-fields').classList.add('d-none');
        } else if (type === 'avance') {
          document.getElementById('pret-fields').classList.add('d-none');
          document.getElementById('avance-fields').classList.remove('d-none');
        }
      });

      document.getElementById('montant_total').addEventListener('input', function() {
        calculateRemainingPayments();
        calculateEndDate();
      });
      document.getElementById('montant_recurrent').addEventListener('input', function() {
        calculateRemainingPayments();
        calculateEndDate();
      });

      function calculateRemainingPayments() {
        var montantTotal = parseFloat(document.getElementById('montant_total').value) || 0;
        var montantRecurrent = parseFloat(document.getElementById('montant_recurrent').value) || 0;
        var nombrePrelevementsRestants = montantRecurrent > 0 ? Math.ceil(montantTotal / montantRecurrent) : 0;
        document.getElementById('nombre_prelevements_restants').value = nombrePrelevementsRestants;
      }

      function calculateEndDate() {
        var montantRecurrent = parseFloat(document.getElementById('montant_recurrent').value) || 0;
        var nombrePrelevementsRestants = parseInt(document.getElementById('nombre_prelevements_restants').value) || 0;

        if (montantRecurrent > 0 && nombrePrelevementsRestants > 0) {
          var today = new Date();
          var endDate = new Date(today.getFullYear(), today.getMonth() + nombrePrelevementsRestants, 25);

          // Adjust the end date to the 25th of the next month if necessary
          if (endDate.getDate() < 25) {
            endDate = new Date(endDate.getFullYear(), endDate.getMonth(), 25);
          } else {
            endDate = new Date(endDate.getFullYear(), endDate.getMonth() + 1, 25);
          }

          var options = {
            year: 'numeric',
            month: 'long'
          };
          var formattedDate = endDate.toLocaleDateString('fr-FR', options);
          document.getElementById('date_fin_prelevement').value = `le ${formattedDate}`;
        } else {
          document.getElementById('date_fin_prelevement').value = '';
        }
      }
    });
  </script>



  <script src="js/style_script.js"></script>
</body>

</html>