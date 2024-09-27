<?php

require_once 'model/Database.php';
require_once 'model/Task.php';
require_once 'model/Personnel.php';

$taskObj = new Task();
$personnelObj = new Personnel();


$tachesEnAttente = $taskObj->getTasksByStatus('En Attente');
$tachesOk = $taskObj->getTasksByStatus('Termine');
$tachesRefusees = $taskObj->getTasksByStatus('Refusee');
$tachesAnnulees = $taskObj->getTasksByStatus('Annulee');


$nbTachesEnAttente = count($tachesEnAttente);
$nbTachesOk = count($tachesOk);
$nbTachesRefusees = count($tachesRefusees);
$nbTachesAnnulees = count($tachesAnnulees);

$nbTachesTotal = $nbTachesEnAttente + $nbTachesOk + $nbTachesRefusees + $nbTachesAnnulees;

// Initialiser les pourcentages
$percentTachesEnAttente = $percentTachesOk = $percentTachesRefusees = $percentTachesAnnulees = 0;

// Calcul des pourcentages si le total de tâches est supérieur à zéro
if ($nbTachesTotal > 0) {
    $percentTachesEnAttente = round(($nbTachesEnAttente / $nbTachesTotal) * 100, 2);
    $percentTachesOk = round(($nbTachesOk / $nbTachesTotal) * 100, 2);
    $percentTachesRefusees = round(($nbTachesRefusees / $nbTachesTotal) * 100, 2);
    $percentTachesAnnulees = round(($nbTachesAnnulees / $nbTachesTotal) * 100, 2);
}

$allTasks = $taskObj->getAllTasks();

// Préparation des labels (noms des tâches) et des données (durées en heures)
$taskNames = [];
$taskDurationsInHours = [];

foreach ($allTasks as $task) {
    // Ajouter le nom de la tâche
    $taskNames[] = $task['task_code']; // Supposons que le champ de nom de la tâche soit 'nom'
    
    // Conversion de la durée de secondes en heures
    $dureeEnHeures = $task['dureeEnSecondes'] / 3600;
    $taskDurationsInHours[] = round($dureeEnHeures, 2); // Arrondi au centième près
}

$attendanceData = $personnelObj->getAttendanceData();


//Trouver l'employé #1
$employe = $personnelObj->getPersonnelById(3); // Employé #1

?>