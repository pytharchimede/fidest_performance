<?php 
// Inclure la classe Task
require_once 'model/Task.php';
require_once 'model/Personnel.php';

$taskObj = new Task();
$personnelObj = new Personnel();
$personnelId = $_SESSION['id_personnel_tasks'];

if ($_SESSION['role'] == 'superviseur') {
  $taches = $taskObj->getTasksByStatus('En Attente');
} else {
  $matricule = $_SESSION['matricule_personnel_tasks'];
  $taches = $taskObj->getTasksByMatriculeAndStatus($matricule, 'En Attente');
}

$nbTaches = count($taches);
?>