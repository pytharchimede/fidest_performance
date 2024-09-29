<?php
session_start();
require_once '../model/Task.php';

$taskObj = new Task();

$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : null;
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : null;

// Récupérer les tâches par défaut (Terminees) si aucune date n'est fournie
if (!$date_debut && !$date_fin) {
    if ($_SESSION['role'] == 'superviseur') {
        // Si l'utilisateur est superviseur, récupérer toutes les tâches Terminees
        $taches = $taskObj->getTasksByStatus('Termine');
    } else {
        // Sinon, récupérer les tâches assignées à l'utilisateur qui sont Terminees
        $matricule = $_SESSION['matricule_personnel_tasks'];
        $taches = $taskObj->getTasksByMatriculeAndStatus($matricule, 'Termine');
    }
} else {
    // Vérification de la présence des dates pour filtrer les tâches
    if ($_SESSION['role'] == 'superviseur') {
        // Si l'utilisateur est superviseur, récupérer toutes les tâches dans la plage de dates
        $taches = $taskObj->getTasksByDateRange($date_debut, $date_fin);
    } else {
        // Sinon, récupérer les tâches assignées à l'utilisateur dans la plage de dates
        $matricule = $_SESSION['matricule_personnel_tasks'];
        $taches = $taskObj->getTasksByTermineMatriculeAndDateRange($matricule, $date_debut, $date_fin);
    }
}

$nbTaches = count($taches);
?>

<ul class="list-group" style="padding: 0; margin: 0;">
    <?php if ($nbTaches > 0): ?>
        <?php foreach ($taches as $tache): ?>
            <?php
            // Vérification si la tâche est expirée
            $now = date('Y-m-d');
            $isExpired = strtotime($tache['deadline']) < strtotime($now); // Vérification de l'expiration
            ?>
            <li class="list-group-item" style="background-color: #f7f7f7; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                <div class="task-header d-flex justify-content-between align-items-center" style="font-weight: bold; color: #333;">
                    <div class="d-flex align-items-center">
                        <?php if ($isExpired): ?>
                            <i class="fas fa-exclamation-circle" style="color: #dc3545; margin-right: 10px;"></i>
                        <?php endif; ?>
                        <h5 style="margin: 0;"><?= htmlspecialchars($tache['task_code']); ?></h5>
                    </div>
                    <?php if (!empty($tache['images'])): ?>
                        <a href="view_task_images.php?taskId=<?= htmlspecialchars($tache['id']); ?>" class="task-icon" title="Visualiser les images" style="color: #007bff;">
                            <i class="fas fa-paperclip"></i><span style="margin-left: 5px;">Pièces jointes</span>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if ($tache['report_demande'] == 1): ?>
                    <div class="alert alert-info alert-dismissible fade show mt-2" role="alert" style="background-color: #d9edf7; color: #31708f;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Alerte !</strong> Vous avez émis une demande de report pour cette tâche.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="task-details mt-3" style="font-size: 14px; color: #555;">
                    <p style="margin-bottom: 8px;"><strong>Projet :</strong> <?= $tache['projet'] ? htmlspecialchars($tache['projet']) : 'Activités courantes'; ?></p>
                    <?php if ($_SESSION['role'] == 'superviseur'): ?>
                        <p style="margin-bottom: 8px;"><strong>Exécutant :</strong> <?= htmlspecialchars($taskObj->getTasksResponsable($tache['id'])['nom_personnel_tasks']); ?></p>
                    <?php else: ?>
                        <p style="margin-bottom: 8px;"><strong>Assignateur :</strong> <?= $taskObj->getTasksAssignateur($tache['id'])['nom_personnel_tasks'] != '' ? htmlspecialchars($taskObj->getTasksAssignateur($tache['id'])['nom_personnel_tasks']) : 'Aucun assignateur défini'; ?></p>
                    <?php endif; ?>
                    <p style="margin-bottom: 8px;"><strong>Description :</strong> <?= htmlspecialchars($tache['description']); ?></p>
                    <p style="margin-bottom: 8px;"><strong>Date limite :</strong> <?= htmlspecialchars($tache['deadline']); ?></p>
                    <p style="margin-bottom: 0;"><strong>Durée :</strong> <?= htmlspecialchars($tache['duree']); ?></p>
                </div>

                <div class="task-actions mt-3">
                    <p style="font-style: italic; color: #28a745;">Tâche déjà effectuée</p>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-tasks text-center" style="color: #888; padding: 20px; border: 2px dashed #ccc; border-radius: 10px;">
            <div class="smiley" style="font-size: 50px;">😊</div>
            <h3 style="margin-top: 15px; font-weight: normal;">Aucune tâche terminée trouvée</h3>
        </div>
    <?php endif; ?>
</ul>


