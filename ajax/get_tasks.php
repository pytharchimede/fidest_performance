<?php
session_start();
require_once '../model/Task.php';

$taskObj = new Task();

$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : null;
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : null;

// Récupérer les tâches par défaut (en attente) si aucune date n'est fournie
if (!$date_debut && !$date_fin) {
    if ($_SESSION['role'] == 'superviseur') {
        // Si l'utilisateur est superviseur, récupérer toutes les tâches en attente
        $taches = $taskObj->getTasksByStatus('En Attente');
    } else {
        // Sinon, récupérer les tâches assignées à l'utilisateur qui sont en attente
        $matricule = $_SESSION['matricule_personnel_tasks'];
        $taches = $taskObj->getTasksByMatriculeAndStatus($matricule, 'En Attente');
    }
} else {
    // Vérification de la présence des dates pour filtrer les tâches
    if ($_SESSION['role'] == 'superviseur') {
        // Si l'utilisateur est superviseur, récupérer toutes les tâches dans la plage de dates
        $taches = $taskObj->getTasksByDateRange($date_debut, $date_fin);
    } else {
        // Sinon, récupérer les tâches assignées à l'utilisateur dans la plage de dates
        $matricule = $_SESSION['matricule_personnel_tasks'];
        $taches = $taskObj->getTasksByEnAttenteMatriculeAndDateRange($matricule, $date_debut, $date_fin);
    }
}

$nbTaches = count($taches);
?>

<ul class="list-group">
    <?php if ($nbTaches > 0): ?>
        <?php foreach ($taches as $tache): ?>
            <?php
            // Vérification si la tâche est expirée
            $now = date('Y-m-d');
            $isExpired = strtotime($tache['deadline']) < strtotime($now); // Vérification de l'expiration
            ?>
            <li class="list-group-item <?= $isExpired ? 'expired-task' : ''; ?>">
                <div class="task-header">
                    <?php if ($isExpired): ?>
                        <i class="fas fa-exclamation-circle expired-icon"></i>
                    <?php endif; ?>
                    <h5><?= htmlspecialchars($tache['task_code']); ?></h5>
                    &nbsp;&nbsp;&nbsp;
                    <?php if (!empty($tache['images'])): ?>
                        <a href="view_task_images.php?taskId=<?= htmlspecialchars($tache['id']); ?>" class="task-icon" title="Visualiser les images">
                            <i class="fas fa-paperclip"></i><span>Pièces jointes</span>
                        </a>
                    <?php endif; ?>
                </div>
                <!-- Alerte pour les demandes de report -->
                <?php if ($tache['report_demande'] == 1): ?>
                    <div class="alert alert-info alert-dismissible fade show alert-clignotante" role="alert" id="alert-expired">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Alerte !</strong> Vous avez émis une demande de report pour cette tâche.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <div class="task-details">
                    <?php if ($_SESSION['role'] == 'superviseur'): ?>
                        <p><?= 'Exécutant : ' . htmlspecialchars($taskObj->getTasksResponsable($tache['id'])['nom_personnel_tasks']) ?></p>
                    <?php else: ?>
                        <p><?= $taskObj->getTasksAssignateur($tache['id'])['nom_personnel_tasks'] != '' ? 'Assignateur : ' . htmlspecialchars($taskObj->getTasksAssignateur($tache['id'])['nom_personnel_tasks']) : 'Aucun assignateur défini' ?></p>
                    <?php endif; ?>
                    <p>Description : <?= htmlspecialchars($tache['description']); ?></p>
                    <p>Date limite : <?= htmlspecialchars($tache['deadline']); ?></p>
                    <p>Durée : <?= htmlspecialchars($tache['duree']); ?></p>
                </div>
                <div class="task-actions">
                    <form method="post" action="request/update_task_status.php" class="d-inline">
                        <input type="hidden" name="task_code" value="<?= htmlspecialchars($tache['task_code']); ?>">
                        <button type="submit" name="action" value="complete" class="btn btn-complete"><i class="fas fa-check"></i> Terminer</button>
                    </form>

                    <?php if ($_SESSION['role'] == 'superviseur'): ?>
                        <form method="post" action="request/update_task_status.php" class="d-inline">
                            <input type="hidden" name="task_code" value="<?= htmlspecialchars($tache['task_code']); ?>">
                            <button type="submit" name="action" value="cancel" class="btn btn-cancel"><i class="fas fa-ban"></i> Annuler</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($tache['report_demande'] != 1): ?>
                        <form method="get" action="report_task.php" class="d-inline">
                            <input type="hidden" name="task_id" value="<?= htmlspecialchars($tache['id']); ?>">
                            <button type="submit" class="btn btn-reject">
                                <i class="fas fa-times"></i> Reporter
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-tasks">
            <div class="smiley">😊</div>
            <h3>Aucune tâche en attente trouvée</h3>
        </div>
    <?php endif; ?>
</ul>

