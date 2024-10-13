<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'superviseur') {
    header('Location: acces_refuse.php');
    exit();
}

// Inclure les modèles
require_once 'model/Task.php';
require_once 'model/Personnel.php';

// Vérifier si un ID de tâche est fourni
if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Récupérer les informations de la tâche
    $taskObj = new Task();
    $task = $taskObj->getTaskById($taskId);

    // Si la tâche n'existe pas, rediriger
    if (!$task) {
        header("Location: taches_en_attente.php");
        exit();
    }
} else {
    header("Location: taches_en_attente.php");
    exit();
}

// Récupérer la liste du personnel pour les assignateurs et les assignés
$personnelObj = new Personnel();
$personnelList = $personnelObj->listerPersonnel();

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
    <title>Modifier Tâche</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'menu.php'; ?>


    <div class="container mt-5">
        <h2 class="mb-4">Modifier la Tâche</h2>
        <form action="request/update_task.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_tache" value="<?php echo $taskId; ?>">
            <div class="form-group">
                <label for="taskCode">Code de la Tâche</label>
                <input type="text" class="form-control" id="taskCode" name="taskCode" value="<?php echo $task['task_code']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="projet">Projet</label>
                <input type="text" class="form-control" id="projet" name="projet" value="<?php echo $task['projet']; ?>" required>
            </div>
            <div class="form-group">
                <label for="matricule_assignateur">Assignateur</label>
                <select class="form-control select2" id="matricule_assignateur" name="matricule_assignateur" required>
                    <option value="">Sélectionner le personnel</option>
                    <?php foreach ($personnelList as $personnel) : ?>
                        <option value="<?php echo $personnel['matricule_personnel_tasks']; ?>" <?php echo ($personnel['matricule_personnel_tasks'] == $task['matricule_assignateur']) ? 'selected' : ''; ?>>
                            <?php echo strtoupper($personnel['nom_personnel_tasks']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="taskDescription">Description de la Tâche</label>
                <textarea class="form-control" id="taskDescription" name="taskDescription" rows="3" required><?php echo $task['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="assignedTo">Assignée à</label>
                <select class="form-control select2" id="assignedTo" name="assignedTo" required>
                    <option value="">Sélectionner le personnel</option>
                    <?php foreach ($personnelList as $personnel) : ?>
                        <option value="<?php echo $personnel['matricule_personnel_tasks']; ?>" <?php echo ($personnel['matricule_personnel_tasks'] == $task['assigned_to']) ? 'selected' : ''; ?>>
                            <?php echo strtoupper($personnel['nom_personnel_tasks']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="deadline">Date Limite</label>
                <input type="datetime-local" class="form-control" id="deadline" name="deadline" value="<?php echo date('Y-m-d\TH:i', strtotime($task['deadline'])); ?>" required>
            </div>

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
            <!-- Continue with the rest of the fields... -->
            <button type="submit" class="btn btn-primary">Modifier la Tâche</button>
        </form>
    </div>
    <script src="js/style_script.js"></script>
</body>

</html>