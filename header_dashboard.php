<?php

// Inclure les classes Task et Personnel
require_once 'model/Task.php';
require_once 'model/Personnel.php';
require_once 'model/Helper.php';


// Récupérer les informations de salaire pour un personnel spécifique
$personnelId = $_SESSION['id_personnel_tasks']; // Remplacer par l'ID du personnel connecté
$monId = $personnelId;
$personnelObj = new Personnel();
$salaireInfo = $personnelObj->getSalaireByPersonnelId($personnelId);
$avanceInfo = $personnelObj->getAvanceByPersonnelId($personnelId);
$presenceInfo = $personnelObj->getPresenceByPersonnelId($personnelId);

$salaireMensuel = $salaireInfo['salaire_mensuel_personnel_tasks'];
$avanceDeductible = $avanceInfo['avance_deductible'];
$joursTravailles = $presenceInfo['jours_travailles'];

//Récupérer les tâches en attente 
$taskObj = new Task();


// Récupérer le rôle du personnel
$role = $personnelObj->getRoleById($personnelId);

// var_dump($role);

$matricule = $_SESSION['matricule_personnel_tasks'];

/*
if ($role == 'superviseur') {
  $tachesEnAttente = $taskObj->getTasksByStatus('En Attente');
  $tachesOk = $taskObj->getTasksByStatus('Termine');
  $tachesRefusees = $taskObj->getTasksByStatus('Refusee');
  $tachesAnnulees = $taskObj->getTasksByStatus('Annulee');
} else {
  */
  $tachesEnAttente = $taskObj->getThisMonthTasksByMatriculeAndStatus($matricule, 'En Attente');
  $tachesOk = $taskObj->getThisMonthTasksByMatriculeAndStatus($matricule, 'Termine');
  $tachesRefusees = $taskObj->getThisMonthTasksByMatriculeAndStatus($matricule, 'Refusee');
  $tachesAnnulees = $taskObj->getThisMonthTasksByMatriculeAndStatus($matricule, 'Annulee');
/*}*/

$nbTachesEnAttente = count($tachesEnAttente);
$nbTachesOk = count($tachesOk);
$nbTachesRefusees = count($tachesRefusees);
$nbTachesAnnulees = count($tachesAnnulees);

$salaireAcquis = ($salaireMensuel/20)*$joursTravailles-$avanceDeductible;

//Nombre total de taches 
$nbTachesTotal = count($taskObj->getTasksByMatricule($matricule));

//Nombre de taches expirées
$nbTachesExpired = count($taskObj->getTaskExpiredByMatricule($matricule));

// Créer une instance de Helper
$helper = new Helper();

// Calculer le score en fonction des tâches en attente et des tâches terminées
$score = $helper->calculerScore($nbTachesOk, $nbTachesTotal);

// Déterminer la couleur du score et de la barre de progression
if ($score >= 80) {
  $scoreClass = 'text-success';
  $progressBarClass = 'bg-success';
} elseif ($score >= 50) {
  $scoreClass = 'text-warning';
  $progressBarClass = 'bg-warning';
} else {
  $scoreClass = 'text-danger';
  $progressBarClass = 'bg-danger';
}

// Récupérer et trier les personnels
$allPersonnel = $personnelObj->getAllPersonnelWithTotalWorkedTimeAndRanking();

// Trier les employés en fonction du temps de travail (du plus grand au plus petit)
usort($allPersonnel, function ($a, $b) {
  return $b['totalWorkedTime'] <=> $a['totalWorkedTime'];
});

// Trouver le classement du personnel connecté
$ranking = array_search($personnelId, array_column($allPersonnel, 'id_personnel_tasks')) + 1;

//Effectif de l'entreprise
$effectif = count($personnelObj->listerPersonnelSaufDirecteurs());

// Temps total des tâches en attente
$totalTimeEnAttenteSec = $taskObj->getThisMonthTotalTimeByStatus($matricule, 'En Attente');
$totalTimeEnAttenteHrs = floor($totalTimeEnAttenteSec / 3600);
$totalTimeEnAttenteMin = floor(($totalTimeEnAttenteSec % 3600) / 60);

// Temps total des tâches effectuées
$totalTimeEffectueesSec = $taskObj->getThisMonthTotalTimeByStatus($matricule, 'Termine');
$totalTimeEffectueesHrs = floor($totalTimeEffectueesSec / 3600);
$totalTimeEffectueesMin = floor(($totalTimeEffectueesSec % 3600) / 60);

// Temps total des tâches rejetées
$totalTimeRejeteesSec = $taskObj->getThisMonthTotalTimeByStatus($matricule, 'Refusee');
$totalTimeRejeteesHrs = floor($totalTimeRejeteesSec / 3600);
$totalTimeRejeteesMin = floor(($totalTimeRejeteesSec % 3600) / 60);

// Temps restant
$totalTimeTotalSec = $totalTimeEnAttenteSec + $totalTimeEffectueesSec + $totalTimeRejeteesSec;
$totalTimeRestantSec = $totalTimeTotalSec - $totalTimeEffectueesSec;
$totalTimeRestantHrs = floor($totalTimeRestantSec / 3600);
$totalTimeRestantMin = floor(($totalTimeRestantSec % 3600) / 60);

?>