<?php 
// Inclure la classe Task
require_once 'model/Task.php';
require_once 'model/Personnel.php';

$taskObj = new Task();
$personnelObj = new Personnel();
$personnelId = $_SESSION['id_personnel_tasks'];

$matriculeAssignateur = $_SESSION['matricule_personnel_tasks'];

$demandes_reports = $taskObj->getReportRequestedTasksByMatriculeAndAssignateur($matriculeAssignateur);

$nbTaches = count($demandes_reports);
?>