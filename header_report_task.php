<?php 
// Inclure la classe Task
require_once 'model/Task.php';
require_once 'model/Personnel.php';
require_once 'model/Helper.php';


$taskObj = new Task();
$personnelObj = new Personnel();
$personnelId = $_SESSION['id_personnel_tasks'];

$helperObj = new Helper();

if ($_SESSION['role'] == 'superviseur') {
  $taches = $taskObj->getTasksByStatus('En Attente');
} else {
  $matricule = $_SESSION['matricule_personnel_tasks'];
  $taches = $taskObj->getTasksByMatriculeAndStatus($matricule, 'En Attente');
}

$nbTaches = count($taches);
if (isset($_GET['task_id'])) {
    $task_id=$_GET['task_id'];
    $task = $taskObj->getTaskById($task_id);
    if (!$task) {
        die('Tâche introuvable.');
    }

    $dateEnFrancais = $helperObj->dateEnFrancais($task['deadline']);
} else {
    die('ID de la tâche manquant.');
}

?>