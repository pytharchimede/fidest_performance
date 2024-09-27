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

// Trouver l'employé ayant le plus grand temps de travail

// Récupérer tous les personnels avec leur temps total travaillé
$allPersonnel = $personnelObj->getAllPersonnelWithTotalWorkedTimeAndRanking();

// Préparation de la variable $employees pour utilisation dans le tableau de classement
$employees = $allPersonnel;

// Trier les employés en fonction du temps de travail (du plus grand au plus petit)
usort($employees, function ($a, $b) {
    return $b['totalWorkedTime'] <=> $a['totalWorkedTime'];
});

$employeTopWorker = null;
$maxWorkedTime = -1;

foreach ($allPersonnel as $personnel) {
    // Vérifier si la clé 'totalWorkedTime' est définie
    if (isset($personnel['totalWorkedTime']) && $personnel['totalWorkedTime'] > $maxWorkedTime) {
        $maxWorkedTime = $personnel['totalWorkedTime'];
        $employeTopWorker = $personnel;
        $maxWorkedTimeInHours = round($maxWorkedTime / 3600, 2);
    }
    //echo 'Nom du personnel : ' . $personnel['nom_personnel_tasks'] . ' | Temps total travaillé : ' . $personnel['totalWorkedTimeInHours'] . ' heures<br>';
}

// Récupérer les infos de l'employé avec le plus grand temps travaillé
if ($employeTopWorker) {
    $employe = $personnelObj->getPersonnelById($employeTopWorker['id_personnel_tasks']);
}



?>