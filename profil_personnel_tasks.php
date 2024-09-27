<?php
require_once 'model/Personnel.php';
require_once 'model/Helper.php';

$personnelObj = new Personnel();
$helperObj = new Helper();

if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $employeeDetails = $personnelObj->getPersonnelById($employeeId);
    // Supposons que vous récupérez également les données d'assiduité et de rendement ici.
    $attendanceData = $personnelObj->getAttendanceDataById($employeeId);
    $tasksData = $personnelObj->getTasksDataById($employeeDetails['matricule_personnel_tasks']);

    $date_entree = $helperObj->dateEnFrancaisSansHeure($employeeDetails['date_recrutement']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'employé</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.2/jquery.knob.min.css">
    <link rel="stylesheet" href="css/style_profil_personnel_tasks.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <a class="navbar-brand text-primary" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
</nav>

<div class="container">
    <div class="profile-card">
        <img src="https://stock.fidest.ci/app/&_gestion/photo/<?= $employeeDetails['photo_personnel_tasks'] ?>" alt="Photo" class="profile-image">
        <h2 class="profile-header"><?= strtoupper($employeeDetails['nom_personnel_tasks']) ?></h2>
        <p class="text-muted"><?= $employeeDetails['matricule'] ?></p>
        <p>
            <strong>Contact:</strong> 
            <a class="badge badge-custom" href="tel:<?= $employeeDetails['tel_personnel_tasks'] ?>">
                <i class="fas fa-phone-alt fa-icon"></i><?= $employeeDetails['tel_personnel_tasks'] ?>
            </a>
        </p>
        <p>
            <strong>Email:</strong> 
            <a class="badge badge-custom" href="mailto:<?= $employeeDetails['email_personnel_tasks'] ?>">
                <i class="fas fa-envelope fa-icon"></i><?= $employeeDetails['email_personnel_tasks'] ?>
            </a>
        </p>
        <p>
            <strong>Date d'entrée:</strong> <?= $date_entree ?>
        </p>
        <p class="type_contrat_area">
            <strong>Type de contrat:</strong> <span class="badge badge-custom"><?= $employeeDetails['type_contrat'] ?></span>
        </p>
        <!-- Bouton d'impression -->
        <a href="#" onclick="window.print();" class="btn-print">
            <i class="fas fa-print"></i> Imprimer
        </a>
    </div>

    <div class="stats-card">
        <h5>Statistiques d'Assiduité</h5>
        <div class="jauge">
            <input type="text" class="knob" data-width="100" data-height="100" value="<?= $attendanceData['frequence_presences'] ?>" data-fgColor="#1d2b57" data-bgColor="#f8f9fa" data-readOnly="true" data-thickness=".1" />
            <p>Fréquence des Présences (%)</p>
            <p><strong>Nombre de Présences:</strong> <?= $attendanceData['total_presences'] ?></p>
        </div>
        <div class="jauge">
            <input type="text" class="knob" data-width="100" data-height="100" value="<?= $attendanceData['frequence_absences'] ?>" data-fgColor="#dc3545" data-bgColor="#f8f9fa" data-readOnly="true" data-thickness=".1" />
            <p>Fréquence des Absences (%)</p>
            <p><strong>Nombre d'Absences:</strong> <?= $attendanceData['total_absences'] ?></p>
        </div>
        <div class="jauge">
            <input type="text" class="knob" data-width="100" data-height="100" value="<?= $attendanceData['frequence_retards'] ?>" data-fgColor="#dc3545" data-bgColor="#f8f9fa" data-readOnly="true" data-thickness=".1" />
            <p>Fréquence des Retards (%)</p>
            <p><strong>Nombre de retards:</strong> <?= $attendanceData['total_retards'] ?></p>
        </div>
    </div>

    <div class="stats-card">
        <h5>Statistiques de Rendement</h5>
    
        <!-- Jauge pour les tâches terminées -->
        <div class="jauge">
            <input type="text" class="knob" data-width="100" data-height="100" 
                value="<?= $tasksData['taux_taches_executees'] ?>" 
                data-fgColor="#1d2b57" data-bgColor="#f8f9fa" 
                data-readOnly="true" data-thickness=".1" />
            <p>Performance (%)</p>
            <p><strong>Nombre de Tâches Terminées :</strong> <?= $tasksData['taches_executees'] ?></p>
        </div>
        
        <!-- Jauge pour les tâches en attente -->
        <div class="jauge">
            <input type="text" class="knob" data-width="100" data-height="100" 
                value="<?= $tasksData['taux_taches_en_attente'] ?>" 
                data-fgColor="#ffc107" data-bgColor="#f8f9fa" 
                data-readOnly="true" data-thickness=".1" />
            <p>Tâches En Attente (%)</p>
            <p><strong>Nombre de Tâches En Attente :</strong> <?= $tasksData['taches_en_attente'] ?></p>
        </div>
        
        <!-- Jauge pour les tâches en retard -->
        <div class="jauge">
            <input type="text" class="knob" data-width="100" data-height="100" 
                value="<?= $tasksData['taux_taches_en_retard'] ?>" 
                data-fgColor="#dc3545" data-bgColor="#f8f9fa" 
                data-readOnly="true" data-thickness=".1" />
            <p>Taux de Retard (%)</p>
            <p><strong>Nombre de Tâches en Retard :</strong> <?= $tasksData['taches_en_retard'] ?></p>
        </div>

        <div class="total-tasks-duration">
            <p><strong>Total de Tâches à Exécuter :</strong> <span><?= $tasksData['total_taches'] ?></span></p>

            <?php
            // Calcul du total en heures et minutes à partir des secondes
            $hours = floor($tasksData['total_heures'] / 3600);
            $minutes = floor(($tasksData['total_heures'] % 3600) / 60);
            ?>
            
            <p><strong>Durée Totale :</strong> <span><?= $hours ?></span> H <span><?= $minutes ?></span> min</p>
        </div>


    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.2/jquery.knob.min.js"></script>
<script>
    $(function() {
        $(".knob").knob();
    });
</script>

</body>
</html>
